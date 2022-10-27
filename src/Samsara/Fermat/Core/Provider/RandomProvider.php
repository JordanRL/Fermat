<?php

namespace Samsara\Fermat\Core\Provider;

use Exception;
use Random\RandomException;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\RandomMode;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Random\Randomizer;
use Random\Engine\Secure;
use Random\Engine\PcgOneseq128XslRr64;
use Random\Engine\Xoshiro256StarStar;

/**
 * @package Samsara\Fermat\Core
 */
class RandomProvider
{

    /**
     * @param int|float|string|Decimal $min
     * @param int|float|string|Decimal $max
     * @param RandomMode $mode
     * @param int|null $seed
     * @return ImmutableDecimal
     * @throws RandomException
     * @throws IntegrityConstraint
     * @throws OptionalExit
     * @throws IncompatibleObjectState
     */
    public static function randomInt(
        int|float|string|Decimal $min,
        int|float|string|Decimal $max,
        RandomMode $mode = RandomMode::Entropy,
        ?int $seed = null
    ): ImmutableDecimal
    {
        /** @var ImmutableDecimal $minDecimal */
        $minDecimal = Numbers::makeOrDont(Numbers::IMMUTABLE, $min);
        /** @var ImmutableDecimal $maxDecimal */
        $maxDecimal = Numbers::makeOrDont(Numbers::IMMUTABLE, $max);

        /**
         * We want to prevent providing non-integer values for min and max, even in cases where
         * the supplied value is a string or a Decimal.
         */
        if ($minDecimal->isFloat() || $maxDecimal->isFloat()) {
            throw new IntegrityConstraint(
                'Random integers cannot be generated with boundaries which are floats',
                'Provide only whole number, integer values for min and max.',
                'An attempt was made to generate a random integer with boundaries which are non-integers. Min Provided: '.$min->getValue(NumberBase::Ten).' -- Max Provided: '.$max->getValue(NumberBase::Ten)
            );
        }

        /**
         * Because of optimistic optimizing with the rand() and random_int() functions, we do
         * need the arguments to be provided in the correct order.
         */
        if ($minDecimal->isGreaterThan($maxDecimal)) {
            $tempDecimal = $minDecimal;
            $minDecimal = $maxDecimal;
            $maxDecimal = $tempDecimal;
            unset($tempDecimal);
        }

        /**
         * For some applications it might be better to throw an exception here, however it
         * would probably be hard to recover in most applications from a situation which
         * resulted in this situation.
         *
         * So instead we will trigger a language level warning and return the only valid
         * value for the parameters given.
         */
        if ($minDecimal->isEqual($maxDecimal)) {
            trigger_error(
                'Attempted to get a random value for a range of no size, with minimum of '.$minDecimal->getValue(NumberBase::Ten).' and maximum of '.$maxDecimal->getValue(NumberBase::Ten),
                E_USER_WARNING
            );

            /** @codeCoverageIgnore  */
            return $minDecimal;
        }

        if ($minDecimal->isLessThanOrEqualTo(PHP_INT_MAX) && $minDecimal->isGreaterThanOrEqualTo(PHP_INT_MIN)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $min = $minDecimal->asInt();
        }

        if ($maxDecimal->isLessThanOrEqualTo(PHP_INT_MAX) && $maxDecimal->isGreaterThanOrEqualTo(PHP_INT_MIN)) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $max = $maxDecimal->asInt();
        }

        $randomizer = self::getRandomizer($mode, $seed);

        if (is_int($min) && is_int($max)) {
            $num = $randomizer->getInt($min, $max);
            return new ImmutableDecimal($num);
        } else {
            /**
             * We only need to request enough bytes to find a number within the range, since we
             * will be adding the minimum value to it at the end.
             */
            /** @noinspection PhpUnhandledExceptionInspection */
            $range = $maxDecimal->subtract($minDecimal);

            do {
                try {
                    $randomValue = '';

                    for ($i = 0; $i < $max->numberOfIntDigits();$i++) {
                        $randomValue .= $randomizer->getInt(0, 9);
                    }

                /** @codeCoverageIgnore  */
                } catch (Exception $e) {
                    /** @codeCoverageIgnore  */
                    throw new OptionalExit(
                        'System error from random_bytes().',
                        'Ensure your system is configured correctly.',
                        'A call to random_bytes() threw a system level exception. Most often this is due to a problem with entropy sources in your configuration. Original exception message: ' . $e->getMessage()
                    );
                }

                /**
                 * @var ImmutableDecimal $num
                 */
                $num = Numbers::make(
                    type: Numbers::IMMUTABLE,
                    value: $randomValue
                );
            } while ($num->isGreaterThan($range));
            /**
             * It is strictly speaking possible for this to loop infinitely. In the worst case
             * scenario where 50% of possible values are invalid, it takes 7 loops for there to
             * be a less than a 1% chance of still not having an answer.
             *
             * After only 10 loops the chance is less than 1/1000.
             *
             * NOTE: Worst case scenario is a range of size 2^n + 1.
             */

            /**
             * Add the minimum since we effectively subtracted it by finding a random number
             * bounded between 0 and range. If our requested range included negative numbers,
             * this operation will also return those values into our data by effectively
             * shifting the result window.
             */
            /** @noinspection PhpUnhandledExceptionInspection */
            return $num->add($minDecimal);
        }
    }

    /**
     * @param int $scale
     * @param RandomMode $mode
     * @return ImmutableDecimal
     * @throws RandomException
     */
    public static function randomDecimal(
        int $scale = 10,
        RandomMode $mode = RandomMode::Entropy
    ): ImmutableDecimal
    {
        $randomizer = self::getRandomizer($mode);

        $result = '0.';
        for ($i = 0; $i < $scale; $i++) {
            $result .= $randomizer->getInt(0, 9);
        }

        return new ImmutableDecimal($result, $scale);
    }

    /**
     * @param int|float|string|Decimal $min
     * @param int|float|string|Decimal $max
     * @param int $scale
     * @param RandomMode $mode
     * @return ImmutableDecimal
     * @throws RandomException
     */
    public static function randomReal(
        int|float|string|Decimal $min,
        int|float|string|Decimal $max,
        int $scale,
        RandomMode $mode = RandomMode::Entropy
    ): ImmutableDecimal
    {
        $min = new ImmutableDecimal($min);
        $max = new ImmutableDecimal($max);

        if ($min->isEqual($max)) {
            trigger_error(
                'Attempted to get a random value for a range of no size, with minimum of '.$min->getValue(NumberBase::Ten).' and maximum of '.$max->getValue(NumberBase::Ten),
                E_USER_WARNING
            );

            /** @codeCoverageIgnore  */
            return $min;
        }

        $intScale = $scale + 2;

        $range = $max->subtract($min);

        $intScale = $intScale + $range->numberOfTotalDigits();

        $randomDecimal = self::randomDecimal($intScale, $mode);

        return $randomDecimal->multiply($range)->add($min)->truncateToScale($scale);
    }

    /**
     * @param RandomMode $mode
     * @param int|null $seed
     * @return Randomizer
     * @throws RandomException
     */
    private static function getRandomizer(RandomMode $mode, ?int $seed = null): Randomizer
    {
        $engine = match ($mode) {
            RandomMode::Speed => new Xoshiro256StarStar($seed),
            RandomMode::Entropy => new PcgOneseq128XslRr64($seed),
            RandomMode::Secure => new Secure(),
        };

        return new Randomizer($engine);
    }

}