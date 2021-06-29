<?php

namespace Samsara\Fermat\Provider;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

class RandomProvider
{

    const MODE_ENTROPY = 1;
    const MODE_SPEED = 2;

    /**
     * @param int|string|DecimalInterface $min
     * @param int|string|DecimalInterface $max
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws OptionalExit
     */
    public static function randomInt(
        int|string|DecimalInterface $min,
        int|string|DecimalInterface $max,
        int $mode = self::MODE_ENTROPY
    ): ImmutableDecimal
    {
        $minDecimal = Numbers::makeOrDont(Numbers::IMMUTABLE, $min);
        $maxDecimal = Numbers::makeOrDont(Numbers::IMMUTABLE, $max);

        /**
         * We want to prevent providing non-integer values for min and max, even in cases where
         * the supplied value is a string or a DecimalInterface.
         */
        if ($minDecimal->isFloat() || $maxDecimal->isFloat()) {
            throw new IntegrityConstraint(
                'Random integers cannot be generated with boundaries which are floats',
                'Provide only whole number, integer values for min and max.',
                'An attempt was made to generate a random integer with boundaries which are non-integers. Min Provided: '.$min->getValue().' -- Max Provided: '.$max->getValue()
            );
        }

        /**
         * Because of optimistic optimizing with the rand() and random_int() functions, we do
         * need the arguments to be provided in the correct order.
         */
        if ($minDecimal->isGreaterThan($maxDecimal)) {
            throw new IntegrityConstraint(
                'Minimum is larger than maximum.',
                'Please provide your arguments in the correct order.',
                'The supplied minimum value for randomInt() was greater than the supplied maximum value.'
            );
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
                'Attempted to get a random value for a range of no size, with minimum of '.$minDecimal->getValue().' and maximum of '.$maxDecimal->getValue(),
                E_USER_WARNING
            );

            return $minDecimal;
        }

        if ($minDecimal->isLessThanOrEqualTo(PHP_INT_MAX) && $minDecimal->isGreaterThanOrEqualTo(PHP_INT_MIN)) {
            $min = $minDecimal->asInt();
        }

        if ($maxDecimal->isLessThanOrEqualTo(PHP_INT_MAX) && $maxDecimal->isGreaterThanOrEqualTo(PHP_INT_MIN)) {
            $max = $maxDecimal->asInt();
        }

        if (is_int($min) && is_int($max)) {
            if ($mode == self::MODE_ENTROPY || $max > getrandmax() || $max < 0 || $min < 0) {
                /**
                 * The random_int() function is cryptographically secure, and takes somewhere on the order
                 * of 15 times as long to execute as rand(). However, rand() also has a smaller range than
                 * the entire PHP integer size, so there are some situations where we need to use this
                 * function even if MODE_SPEED is selected.
                 *
                 * In those cases, random_int() is still faster than calls to random_bytes() and manual
                 * masking.
                 */
                try {
                    $num = random_int($min, $max);
                    return new ImmutableDecimal($num);
                } catch (\Exception $e) {
                    throw new OptionalExit(
                        'System error from random_bytes().',
                        'Ensure your system is configured correctly.',
                        'A call to random_bytes() threw a system level exception. Most often this is due to a problem with entropy sources in your configuration. Original exception message: ' . $e->getMessage()
                    );
                }
            } elseif ($mode == self::MODE_SPEED) {
                /**
                 * If it is possible to do so with the range given and the program has indicated that it
                 * would prefer speed over true randomness in the result, then we will use the deterministic
                 * pseudo-random function rand() as it is faster to reach completion.
                 */
                $num = rand($min, $max);
                return new ImmutableDecimal($num);
            } else {
                throw new IntegrityConstraint(
                    'Mode on random functions must be an implemented mode.',
                    'Choose modes using the class constants.',
                    'A mode was provided to randomInt() that does not correspond to any implementation. Please only use the class constants for selecting the mode.'
                );
            }
        } else {
            $two = Numbers::make(Numbers::IMMUTABLE, 2, 0);

            /**
             * We only need to request enough bytes to find a number within the range, since we
             * will be adding the minimum value to it at the end.
             */
            $range = $maxDecimal->subtract($minDecimal);
            $bitsNeeded = $range->ln(1)->divide($two->ln(1), 1)->floor()->add(1);
            $bytesNeeded = $bitsNeeded->divide(8)->ceil();

            do {
                try {
                    /**
                     * Returns random bytes based on sources of entropy within the system.
                     *
                     * For documentation on these sources please see:
                     *
                     * https://www.php.net/manual/en/function.random-bytes.php
                     */
                    $entropyBytes = random_bytes($bytesNeeded->asInt());
                } catch (\Exception $e) {
                    throw new OptionalExit(
                        'System error from random_bytes().',
                        'Ensure your system is configured correctly.',
                        'A call to random_bytes() threw a system level exception. Most often this is due to a problem with entropy sources in your configuration. Original exception message: ' . $e->getMessage()
                    );
                }

                $randomValue = Numbers::make(
                    type: Numbers::IMMUTABLE,
                    value: $entropyBytes,
                    base: 2
                );

                /**
                 * Since the number of digits is equal to the bits needed, but random_bytes() only
                 * returns in chunks of 8 bits (duh, bytes), we can substr() from the right to
                 * select only the correct number of digits by multiplying the number of bits
                 * needed by -1 and using that as the starting point.
                 */
                $num = Numbers::make(
                    type: Numbers::IMMUTABLE,
                    value: substr($randomValue->getValue(), $bitsNeeded->multiply(-1)->asInt())
                );
            } while ($num->isGreaterThan($range));
            /**
             * It is strictly speaking possible for this to loop infinitely. In the worst case
             * scenario where 50% of possible values are invalid, it takes 7 loops for there to
             * be a less than a 1% chance of still not having an answer.
             *
             * After only 10 loops the chance is less than 1/1000.
             */

            /**
             * Add the minimum since we effectively subtracted it by finding a random number
             * bounded between 0 and range. If our requested range included negative numbers,
             * this operation will also return those values into our data by effectively
             * shifting the result window.
             */
            return $num->add($minDecimal);
        }
    }

    public static function randomDecimal(int $scale = 10, int $mode = self::MODE_ENTROPY): ImmutableDecimal
    {
        $min = new ImmutableDecimal(0);
        $max = new ImmutableDecimal(str_pad('1', $scale+1, '0', STR_PAD_RIGHT));

        $randomValue = self::randomInt($min, $max, $mode);

        if ($randomValue->isEqual($min) || $randomValue->isEqual($max)) {
            return $randomValue->isPositive() ? new ImmutableDecimal(1) : $min;
        }

        return new ImmutableDecimal('0.'.str_pad($randomValue->getValue(), $scale, '0', STR_PAD_LEFT));
    }

    public static function randomReal(
        int|string|DecimalInterface $min,
        int|string|DecimalInterface $max,
        int $scale,
        int $mode = self::MODE_ENTROPY
    ): ImmutableDecimal
    {
        $min = new ImmutableDecimal($min);
        $max = new ImmutableDecimal($max);

        $intPart = self::randomInt($min->floor(), $max->floor(), $mode);
        $decPart = self::randomDecimal($scale, $mode);

        $num = $intPart->add($decPart);

        $num = $num->isGreaterThan($max) ? $max : $num;
        $num = $num->isLessThan($min) ? $min : $num;

        return $num;
    }

}