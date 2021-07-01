<?php

namespace Samsara\Fermat\Provider;

use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

class RoundingProvider
{

    const MODE_HALF_UP = 1;
    const MODE_HALF_DOWN = 2;
    const MODE_HALF_EVEN = 3;
    const MODE_HALF_ODD = 4;
    const MODE_HALF_ZERO = 5;
    const MODE_HALF_INF = 6;
    const MODE_CEIL = 7;
    const MODE_FLOOR = 8;
    const MODE_RANDOM = 9;
    const MODE_ALTERNATING = 10;
    const MODE_STOCHASTIC = 11;

    private static int $mode = self::MODE_HALF_EVEN;
    private static ?DecimalInterface $decimal;
    private static int $alt = 1;
    private static ?string $remainder;

    public static function setRoundingMode(
        #[ExpectedValues([
            self::MODE_HALF_UP,
            self::MODE_HALF_DOWN,
            self::MODE_HALF_EVEN,
            self::MODE_HALF_ODD,
            self::MODE_HALF_ZERO,
            self::MODE_HALF_INF,
            self::MODE_CEIL,
            self::MODE_FLOOR,
            self::MODE_RANDOM,
            self::MODE_ALTERNATING,
            self::MODE_STOCHASTIC
        ])]
        int $mode
    ): void
    {
        static::$mode = $mode;
    }

    #[Pure]
    public static function getRoundingMode(): int
    {
        return static::$mode;
    }

    public static function round(DecimalInterface $decimal, int $places = 0): string
    {
        static::$decimal = $decimal;

        $rawString = str_replace('-', '', $decimal->getAsBaseTenRealNumber());

        if ($decimal->isInt() && $places >= 0) {
            return $rawString;
        }

        $sign = $decimal->isNegative() ? '-' : '';
        $imaginary = $decimal->isImaginary() ? 'i' : '';

        if (str_contains($rawString, '.')) {
            [$wholePart, $decimalPart] = explode('.', $rawString);
        } else {
            $wholePart = $rawString;
            $decimalPart = '';
        }

        $absPlaces = abs($places);

        $currentPart = $places >= 0;
        $roundedPart = $currentPart ? str_split($decimalPart) : str_split($wholePart);
        $roundedPartString = $currentPart ? $decimalPart : $wholePart;
        $otherPart = $currentPart ? str_split($wholePart) : str_split($decimalPart);
        $baseLength = $currentPart ? strlen($decimalPart)-1 : strlen($wholePart);
        $pos = $currentPart ? $places : $baseLength + $places;
        $carry = 0;

        if ($currentPart) {
            $pos = ($absPlaces > $baseLength) ? $baseLength : $pos;
        } else {
            $pos = ($absPlaces >= $baseLength) ? 0 : $pos;
        }

        do {
            if (!array_key_exists($pos, $roundedPart)) {
                break;
            }

            $digit = (int)$roundedPart[$pos] + $carry;

            if ($carry == 0 && $digit == 5) {
                static::$remainder = substr($roundedPartString, $pos+1);
            } else {
                static::$remainder = null;
            }

            if ($pos == 0) {
                if ($currentPart) {
                    $nextDigit = (int)$otherPart[count($otherPart)-1];
                } else {
                    $nextDigit = 0;
                }
            } else {
                $nextDigit = (int)$roundedPart[$pos-1];
            }

            if ($carry == 0) {
                $carry = match (self::getRoundingMode()) {
                    self::MODE_HALF_UP => self::roundHalfUp($digit),
                    self::MODE_HALF_DOWN => self::roundHalfDown($digit),
                    self::MODE_HALF_ODD => self::roundHalfOdd($digit, $nextDigit),
                    self::MODE_HALF_ZERO => self::roundHalfZero($digit),
                    self::MODE_HALF_INF => self::roundHalfInf($digit),
                    self::MODE_CEIL => self::roundCeil($digit),
                    self::MODE_FLOOR => self::roundFloor(),
                    self::MODE_RANDOM => self::roundRandom($digit),
                    self::MODE_ALTERNATING => self::roundAlternating($digit),
                    self::MODE_STOCHASTIC => self::roundStochastic($digit),
                    default => self::roundHalfEven($digit, $nextDigit)
                };
            } else {
                if ($digit > 9) {
                    $carry = 1;
                    $roundedPart[$pos] = '0';
                } else {
                    $carry = 0;
                    $roundedPart[$pos] = $digit;
                }
            }

            if ($pos == 0 && $carry == 1) {
                if ($currentPart) {
                    $currentPart = false;

                    // Do the variable swap dance
                    $temp = $otherPart;
                    $otherPart = $roundedPart;
                    $roundedPart = $temp;

                    $pos = count($roundedPart)-1;
                } else {
                    array_unshift($roundedPart, $carry);
                    $carry = 0;
                }
            } else {
                $pos -= 1;
            }
        } while ($carry == 1);

        if ($currentPart) {
            $newDecimalPart = implode('', $roundedPart);
            $newWholePart = implode('', $otherPart);
        } else {
            $newDecimalPart = implode('', $otherPart);
            $newWholePart = implode('', $roundedPart);
        }

        if ($places > 0) {
            $newDecimalPart = substr($newDecimalPart, 0, $places);
        } elseif ($places == 0) {
            $newDecimalPart = '0';
        } else {
            $newWholePart = substr($newWholePart, 0, strlen($wholePart)+$places).str_repeat('0', $places*-1);
            $newDecimalPart = '0';
        }

        if (!strlen(str_replace('0', '', $newDecimalPart))) {
            $newDecimalPart = '0';
        }

        static::$remainder = null;
        static::$decimal = null;

        return $sign.$newWholePart.'.'.$newDecimalPart.$imaginary;
    }

    #[Pure]
    private static function nonHalfEarlyReturn(int $digit): int
    {
        return $digit <=> 5;
    }

    private static function negativeReverser(): int
    {
        if (static::$decimal->isNegative()) {
            return 1;
        } else {
            return 0;
        }
    }

    private static function remainderCheck(): bool
    {
        $remainder = static::$remainder;

        if (is_null($remainder)) {
            return false;
        }

        $remainder = str_replace('0', '', $remainder);

        return !empty($remainder);
    }

    private static function roundHalfUp(int $digit): int
    {
        $negative = self::negativeReverser();
        $remainder = self::remainderCheck();

        if ($negative) {
            return $digit > 5 || ($digit == 5 && $remainder) ? 1 : 0;
        } else {
            return $digit > 4 ? 1 : 0;
        }
    }

    private static function roundHalfDown(int $digit): int
    {
        $negative = self::negativeReverser();
        $remainder = self::remainderCheck();

        if ($negative) {
            return $digit > 4 ? 1 : 0;
        } else {
            return $digit > 5 || ($digit == 5 && $remainder) ? 1 : 0;
        }
    }

    private static function roundHalfEven(int $digit, int $nextDigit): int
    {
        $early = static::nonHalfEarlyReturn($digit);
        $remainder = self::remainderCheck();

        if ($early == 0) {
            return ($nextDigit % 2 == 0 && !$remainder) ? 0 : 1;
        } else {
            return $early == 1 ? 1 : 0;
        }
    }

    private static function roundHalfOdd(int $digit, int $nextDigit): int
    {
        $early = static::nonHalfEarlyReturn($digit);
        $remainder = self::remainderCheck();

        if ($early == 0) {
            return ($nextDigit % 2 == 1 && !$remainder) ? 0 : 1;
        } else {
            return $early == 1 ? 1 : 0;
        }
    }

    private static function roundHalfZero(int $digit): int
    {
        $remainder = self::remainderCheck();

        return $digit > 5 || ($digit == 5 && $remainder) ? 1 : 0;
    }

    #[Pure]
    private static function roundHalfInf(int $digit): int
    {
        return $digit > 4 ? 1 : 0;
    }

    #[Pure]
    private static function roundCeil(int $digit): int
    {
        return $digit == 0 ? 0 : 1;
    }

    #[Pure]
    private static function roundFloor(): int
    {
        return 0;
    }

    private static function roundRandom(int $digit): int
    {
        $early = static::nonHalfEarlyReturn($digit);
        $remainder = self::remainderCheck();

        if ($early == 0 && !$remainder) {
            return RandomProvider::randomInt(0, 1, RandomProvider::MODE_SPEED)->asInt();
        } else {
            return (($early == 1 || $remainder) ? 1 : 0);
        }
    }

    private static function roundAlternating(int $digit): int
    {
        $early = static::nonHalfEarlyReturn($digit);
        $remainder = self::remainderCheck();

        if ($early == 0 && !$remainder) {
            $val = self::$alt;
            self::$alt = (int)!$val;

            return $val;
        } else {
            return (($early == 1 || $remainder) ? 1 : 0);
        }
    }

    private static function roundStochastic(int $digit): int
    {
        $remainder = static::$remainder;

        if (is_null($remainder)) {
            $target = $digit;
            $rangeMin = 0;
            $rangeMax = 9;
        } else {
            $remainder = substr($remainder, 0, 3);
            $target = (int)($digit.$remainder);
            $rangeMin = 0;
            $rangeMax = (int)str_repeat('9', strlen($remainder) + 1);
        }

        $random = RandomProvider::randomInt($rangeMin, $rangeMax, RandomProvider::MODE_SPEED)->asInt();

        if ($random < $target) {
            return 1;
        } else {
            return 0;
        }
    }

}