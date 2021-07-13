<?php

namespace Samsara\Fermat\Provider;

use Exception;
use JetBrains\PhpStorm\ExpectedValues;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

class RandomProvider
{

    const MODE_ENTROPY = 1;
    const MODE_SPEED = 2;

    /** @noinspection PhpDocMissingThrowsInspection */
    /**
     * @param int|string|DecimalInterface $min
     * @param int|string|DecimalInterface $max
     * @param int $mode
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws OptionalExit
     * @throws IncompatibleObjectState
     */
    public static function randomInt(
        int|string|DecimalInterface $min,
        int|string|DecimalInterface $max,
        #[ExpectedValues([self::MODE_ENTROPY, self::MODE_SPEED])]
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
            /** @noinspection PhpUnhandledExceptionInspection */
            $min = $minDecimal->asInt();
        }

        if ($maxDecimal->isLessThanOrEqualTo(PHP_INT_MAX) && $maxDecimal->isGreaterThanOrEqualTo(PHP_INT_MIN)) {
            /** @noinspection PhpUnhandledExceptionInspection */
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
                } catch (Exception $e) {
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
            /** @noinspection PhpUnhandledExceptionInspection */
            $range = $maxDecimal->subtract($minDecimal);
            /** @noinspection PhpUnhandledExceptionInspection */
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
                    $baseTwoBytes = '';
                    for($i = 0; $i < strlen($entropyBytes); $i++){
                        $baseTwoBytes .= decbin( ord( $entropyBytes[$i] ) );
                    }
                } catch (Exception $e) {
                    throw new OptionalExit(
                        'System error from random_bytes().',
                        'Ensure your system is configured correctly.',
                        'A call to random_bytes() threw a system level exception. Most often this is due to a problem with entropy sources in your configuration. Original exception message: ' . $e->getMessage()
                    );
                }

                $randomValue = Numbers::make(
                    type: Numbers::IMMUTABLE,
                    value: $baseTwoBytes,
                    base: 2
                );

                /**
                 * @var ImmutableDecimal $num
                 *
                 * Since the number of digits is equal to the bits needed, but random_bytes() only
                 * returns in chunks of 8 bits (duh, bytes), we can substr() from the right to
                 * select only the correct number of digits by multiplying the number of bits
                 * needed by -1 and using that as the starting point.
                 */
                $num = Numbers::make(
                    type: Numbers::IMMUTABLE,
                    value: substr($randomValue->getValue(), $bitsNeeded->multiply(-1)->asInt()),
                    base: 2
                )->convertToBase(10);
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
            /** @noinspection PhpUnhandledExceptionInspection */
            return $num->add($minDecimal);
        }
    }

    /**
     * @param int $scale
     * @param int $mode
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws OptionalExit
     * @throws IncompatibleObjectState
     */
    public static function randomDecimal(
        int $scale = 10,
        #[ExpectedValues([self::MODE_ENTROPY, self::MODE_SPEED])]
        int $mode = self::MODE_ENTROPY
    ): ImmutableDecimal
    {
        /**
         * Select the min and max as if we were looking for the decimal part as an integer.
         */
        $min = new ImmutableDecimal(0);
        $max = new ImmutableDecimal(str_pad('1', $scale+1, '0', STR_PAD_RIGHT));

        /**
         * This allows us to utilize the same randomInt() function.
         */
        $randomValue = self::randomInt($min, $max, $mode);

        /**
         * If the random value exactly equals our min or max, that means we need to return
         * either 1 or 0.
         */
        if ($randomValue->isEqual($min) || $randomValue->isEqual($max)) {
            return $randomValue->isPositive() ? new ImmutableDecimal(1) : $min;
        }

        /**
         * In all other cases we need to reformat our integer as being the decimal portion
         * of our number at the given scale.
         */
        return new ImmutableDecimal('0.'.str_pad($randomValue->getValue(), $scale, '0', STR_PAD_LEFT));
    }

    /** @noinspection PhpDocMissingThrowsInspection */
    /**
     * @param int|string|DecimalInterface $min
     * @param int|string|DecimalInterface $max
     * @param int $scale
     * @param int $mode
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws OptionalExit
     * @throws IncompatibleObjectState
     */
    public static function randomReal(
        int|string|DecimalInterface $min,
        int|string|DecimalInterface $max,
        int $scale,
        #[ExpectedValues([self::MODE_ENTROPY, self::MODE_SPEED])]
        int $mode = self::MODE_ENTROPY
    ): ImmutableDecimal
    {
        $min = new ImmutableDecimal($min);
        $max = new ImmutableDecimal($max);

        if ($min->isEqual($max)) {
            trigger_error(
                'Attempted to get a random value for a range of no size, with minimum of '.$min->getValue().' and maximum of '.$max->getValue(),
                E_USER_WARNING
            );

            return $min;
        }

        /**
         * We do this because randomDecimal() can return 1, so if max is a natural number we need to
         * remove it from the result set. Otherwise, we would be grabbing extra values and be shifting
         * them to somewhere else in the result set, which skews the relative probabilities.
         */
        if ($max->isNatural()) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $maxIntRange = $max->subtract(1);
        } else {
            $maxIntRange = $max->floor();
        }

        if (!$min->floor()->isEqual($maxIntRange)) {
            $intPart = self::randomInt($min->floor(), $maxIntRange, $mode);
            $repeatProbability = Numbers::makeZero();

            /**
             * If min and max aren't bounded by the same integers, then we need to adjust the likelihood
             * of an integer on the ends of the range being selected according to the percentage of
             * numbers within that range which are available.
             */
            if ($min->ceil()->isEqual($max->floor())) {
                /**
                 * This is a special case where min and max are less than 1 apart, but they straddle an
                 * integer. In this case, we want to consider the relative likelihood, instead of the
                 * portion of real numbers available.
                 */
                $minCeil = $min->ceil();
                $minRepeat = $minCeil->subtract($min);
                $maxFloor = $max->floor();
                /** @noinspection PhpUnhandledExceptionInspection */
                $maxRepeat = $max->subtract($maxFloor);
                $one = Numbers::makeOne(10);

                /** @noinspection PhpUnhandledExceptionInspection */
                $repeatProbability = $one->subtract($maxRepeat->divide($minRepeat, 10));
            } elseif ($intPart->isEqual($min->floor())) {
                /**
                 * In this case, the integer includes the min. Since it's possible that not all reals
                 * in this range are actually available to choose from, the likelihood that this integer
                 * was chosen relative to any other integer in the range can be adjusted by making a
                 * recursive call with probability X, where X is min - floor(min).
                 */
                $minFloor = $min->floor();
                /** @noinspection PhpUnhandledExceptionInspection */
                $repeatProbability = $min->subtract($minFloor);
            } elseif ($intPart->isEqual($max->floor())) {
                /**
                 * In this case, the integer includes the max. Since it's possible that not all reals
                 * in this range are actually available to choose from, the likelihood that this integer
                 * was chosen relative to any other integer in the range can be adjusted by making a
                 * recursive call with probability X, where X is ceil(max) - max.
                 */
                $maxCeil = $max->ceil();
                $repeatProbability = $maxCeil->subtract($max);
            }

            /**
             * This will never be true unless one of the special cases above occurred. We use short circuiting
             * to prevent a needless additional random generation in situations where there is zero probability
             * adjustment.
             */
            if ($repeatProbability->isGreaterThan(0) && $repeatProbability->isGreaterThan(self::randomDecimal(10, $mode))) {
                return self::randomReal($min, $max, $scale, $mode);
            }
        } else {
            /**
             * In the case where min and max are bounded by the same integers, we can just set the integer
             * part to floor(min) without any further calculation. All of the randomness of the value will
             * come from the decimal part.
             */
            $intPart = $min->floor();
        }

        if (!$intPart->isEqual($max->floor()) && !$intPart->isEqual($min->floor())) {
            /**
             * Because we know at this point that min and max are not equal prior to the conditions in
             * this statement, we can be certain that the entire decimal range is available for selection
             * if it passes these checks.
             *
             * The situations in which the entire decimal is a valid part of the result set are all covered
             * by checking that intPart isn't equal to the floor of either min or max, since those are the only
             * two integers which have bounded decimal ranges.
             */
            $decPart = self::randomDecimal($scale, $mode);
        } else {
            if ($min->isNatural() || $intPart->isGreaterThan($min->floor())) {
                /**
                 * The greater than check is also true any time min is a natural number (integer), however the check
                 * for min being an integer is much faster, so we're taking advantage of short circuiting.
                 */
                $minDecimal = Numbers::makeZero();
            } else {
                /**
                 * The min is guaranteed to have a decimal portion here, since we already checked if it's natural.
                 *
                 * First we use string manipulation to extract the decimal portion as an integer value, the we right
                 * pad with zeroes to make sure that the entire scale is part of the valid result set.
                 */
                $minDecimal = substr($min->getValue(), strpos($min->getValue(), '.') + 1);
                $minDecimal = str_pad($minDecimal, $scale, '0', STR_PAD_RIGHT);
            }

            if ($intPart->isLessThan($max->floor())) {
                /**
                 * We cannot take advantage of a more efficient check for the top end of the range, so the
                 * less than check is all we need.
                 */
                $maxDecimal = str_pad('1', $scale + 1, '0', STR_PAD_RIGHT);
            } else {
                /**
                 * The max value is guaranteed to have a decimal portion here since we excluded max being
                 * a natural number and part of the result set for intPart.
                 *
                 * First we use string manipulation to extract the decimal portion as an integer value, the we right
                 * pad with zeroes to make sure that the entire scale is part of the valid result set.
                 */
                $maxDecimal = substr($max->getValue(), strpos($max->getValue(), '.')+1);
                $maxDecimal = str_pad($maxDecimal, $scale, '0', STR_PAD_RIGHT);
            }

            /**
             * Now that we have the correct bounds for the integers we're bounded by, figure out what the decimal
             * portion of the random number is by utilizing randomInt().
             */
            $decPartAsInt = self::randomInt($minDecimal, $maxDecimal, $mode);

            if ($decPartAsInt->isEqual($maxDecimal) && strlen($maxDecimal) > $scale) {
                /**
                 * In the case where maxDecimal was returned by randomInt, we want to specifically translate
                 * that to 1 instead of treating it as a decimal value. But that's only the case if maxDecimal
                 * was larger than our scale.
                 *
                 * This is another case of us using short circuiting on a more efficient call.
                 */
                $decPart = Numbers::makeOne($scale);
            } else {
                /**
                 * In this section we know with certainty that the result of randomInt represents a decimal value
                 * that we can simply append as a string with padding to ensure correct scale.
                 */
                $decPart = new ImmutableDecimal(
                    value: '0.'.str_pad($decPartAsInt->getValue(), $scale, '0', STR_PAD_LEFT),
                    scale: $scale
                );
            }
        }

        /**
         * Combine the integer and decimal portions of the random value.
         */
        /** @noinspection PhpUnhandledExceptionInspection */
        return $intPart->add($decPart);
    }

}