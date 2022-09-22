<?php

namespace Samsara\Fermat\Provider;

use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;
use Samsara\Fermat\Enums\RandomMode;
use Samsara\Fermat\Enums\RoundingMode;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

class RoundingProvider
{

    private static RoundingMode $mode = RoundingMode::HalfEven;
    private static ?DecimalInterface $decimal;
    private static int $alt = 1;
    private static ?string $remainder;

    public static function setRoundingMode(
        RoundingMode $mode
    ): void
    {
        static::$mode = $mode;
    }

    public static function getRoundingMode(): RoundingMode
    {
        return self::$mode;
    }

    public static function round(DecimalInterface $decimal, int $places = 0): string
    {
        static::$decimal = $decimal;

        $rawString = str_replace('-', '', $decimal->getAsBaseTenRealNumber());

        $sign = $decimal->isNegative() ? '-' : '';
        $imaginary = $decimal->isImaginary() ? 'i' : '';

        if ($decimal->isInt() && $places >= 0) {
            return $sign.$rawString.$imaginary;
        }

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
                    RoundingMode::HalfUp => self::roundHalfUp($digit),
                    RoundingMode::HalfDown => self::roundHalfDown($digit),
                    RoundingMode::HalfOdd => self::roundHalfOdd($digit, $nextDigit),
                    RoundingMode::HalfZero => self::roundHalfZero($digit),
                    RoundingMode::HalfInf => self::roundHalfInf($digit),
                    RoundingMode::Ceil => self::roundCeil($digit),
                    RoundingMode::Floor => self::roundFloor(),
                    RoundingMode::HalfRandom => self::roundRandom($digit),
                    RoundingMode::HalfAlternating => self::roundAlternating($digit),
                    RoundingMode::Stochastic => self::roundStochastic($digit),
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
            return RandomProvider::randomInt(0, 1, RandomMode::Speed)->asInt();
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

        $random = RandomProvider::randomInt($rangeMin, $rangeMax, RandomMode::Speed)->asInt();

        if ($random < $target) {
            return 1;
        } else {
            return 0;
        }
    }

}