<?php

namespace Samsara\Fermat\Core\Provider;

use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\ModeAdapterFactory;

/**
 * @package Samsara\Fermat\Core
 */
class RoundingProvider
{

    private static RoundingMode $mode = RoundingMode::HalfEven;

    /**
     * @return RoundingMode
     */
    public static function getRoundingMode(): RoundingMode
    {
        return self::$mode;
    }

    /**
     * @param string $decimal
     * @param int    $places
     *
     * @return string
     */
    public static function round(string $decimal, int $places = 0): string
    {
        $carry = 0;

        [
            $rawString,
            $roundedPart,
            $roundedPartString,
            $otherPart,
            $pos,
            $wholePart,
            $decimalPart,
            $currentPart,
            $isNegative,
        ] = self::roundPreFormat($decimal, $places);

        $sign = $isNegative ? '-' : '';
        $imaginary = str_ends_with($decimal, 'i') ? 'i' : '';

        if (empty($decimalPart) && $places >= 0) {
            return $sign . $rawString . $imaginary;
        }

        do {
            if (!array_key_exists($pos, $roundedPart)) {
                break;
            }

            [$digit, $nextDigit, $remainder] = self::roundLoopStart(
                $roundedPart,
                $otherPart,
                $roundedPartString,
                $pos,
                $carry,
                $currentPart
            );

            if ($carry == 0) {
                $roundingMode = ModeAdapterFactory::getAdapter(self::getRoundingMode(), $isNegative, $remainder);
                $carry = $roundingMode->determineCarry($digit, $nextDigit);
            } else {
                if ($digit > 9) {
                    $carry = 1;
                    $roundedPart[$pos] = '0';
                } else {
                    $carry = 0;
                    $roundedPart[$pos] = $digit;
                }
            }

            [$roundedPart, $otherPart, $pos, $carry, $currentPart] = self::roundLoopEnd(
                $roundedPart,
                $otherPart,
                $pos,
                $carry,
                $currentPart
            );
        } while ($carry == 1);

        [$newWholePart, $newDecimalPart, $sign] = self::roundPostFormat($currentPart, $wholePart, $roundedPart, $otherPart, $places, $sign);

        return $sign . $newWholePart . '.' . $newDecimalPart . $imaginary;
    }

    /**
     * @param RoundingMode $mode
     *
     * @return void
     */
    public static function setRoundingMode(
        RoundingMode $mode
    ): void
    {
        self::$mode = $mode;
    }

    /**
     * @param array $roundedPart
     * @param array $otherPart
     * @param int   $pos
     * @param int   $carry
     * @param bool  $currentPart
     *
     * @return array
     */
    private static function roundLoopEnd(
        array $roundedPart,
        array $otherPart,
        int   $pos,
        int   $carry,
        bool  $currentPart
    ): array
    {
        if ($pos == 0 && $carry == 1) {
            if ($currentPart) {
                $currentPart = false;

                // Do the variable swap dance
                $temp = $otherPart;
                $otherPart = $roundedPart;
                $roundedPart = $temp;

                $pos = count($roundedPart) - 1;
            } else {
                array_unshift($roundedPart, $carry);
                $carry = 0;
            }
        } else {
            $pos -= 1;
        }

        return [$roundedPart, $otherPart, $pos, $carry, $currentPart];
    }

    /**
     * @param array  $roundedPart
     * @param array  $otherPart
     * @param string $roundedPartString
     * @param int    $pos
     * @param int    $carry
     * @param bool   $currentPart
     *
     * @return array
     */
    private static function roundLoopStart(
        array  $roundedPart,
        array  $otherPart,
        string $roundedPartString,
        int    $pos,
        int    $carry,
        bool   $currentPart
    ): array
    {
        $digit = (int)$roundedPart[$pos] + $carry;

        if ($carry == 0 && $digit == 5 && strlen($roundedPartString) > $pos + 1) {
            $remainder = substr($roundedPartString, $pos + 1);
        } else {
            $remainder = null;
        }

        if ($pos == 0) {
            if ($currentPart) {
                $nextDigit = (int)$otherPart[count($otherPart) - 1];
            } else {
                $nextDigit = 0;
            }
        } else {
            $nextDigit = (int)$roundedPart[$pos - 1];
        }

        return [$digit, $nextDigit, $remainder];
    }

    /**
     * @param string $currentPart
     * @param string $wholePart
     * @param array  $roundedPart
     * @param array  $otherPart
     * @param int    $places
     * @param string $sign
     *
     * @return array
     */
    private static function roundPostFormat(
        string $currentPart,
        string $wholePart,
        array  $roundedPart,
        array  $otherPart,
        int    $places,
        string $sign
    ): array
    {
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
            $newWholePart = substr($newWholePart, 0, strlen($wholePart) + $places) . str_repeat('0', $places * -1);
            $newDecimalPart = '0';
        }

        if (!strlen(str_replace('0', '', $newDecimalPart))) {
            $newDecimalPart = '0';
        }

        if (empty(trim($newWholePart)) && empty(trim($newDecimalPart))) {
            $sign = '';
        }

        return [$newWholePart, $newDecimalPart, $sign];
    }

    /**
     * @param string $decimal
     * @param int    $places
     *
     * @return array
     */
    private static function roundPreFormat(string $decimal, int $places): array
    {
        $decimal = trim(rtrim($decimal));

        $isNegative = str_starts_with($decimal, '-');

        $rawString = str_replace('-', '', $decimal);
        $rawString = str_replace('i', '', $rawString);

        if (str_contains($rawString, '.')) {
            [$wholePart, $decimalPart] = explode('.', $rawString);
        } else {
            $wholePart = $rawString;
            $decimalPart = '';
        }

        $absPlaces = abs($places);

        $currentPart = $places >= 0;

        if ($currentPart) {
            $roundedPart = str_split($decimalPart);
            $roundedPartString = $decimalPart;
            $otherPart = str_split($wholePart);
            $baseLength = strlen($decimalPart) - 1;
            $pos = ($absPlaces > $baseLength && $places < 0) ? $baseLength : $places;
        } else {
            $roundedPart = str_split($wholePart);
            $roundedPartString = $wholePart;
            $otherPart = str_split($decimalPart);
            $baseLength = strlen($wholePart);
            $pos = ($absPlaces >= $baseLength) ? 0 : $baseLength + $places;
        }

        return [
            $rawString,
            $roundedPart,
            $roundedPartString,
            $otherPart,
            $pos,
            $wholePart,
            $decimalPart,
            $currentPart,
            $isNegative,
        ];
    }

}