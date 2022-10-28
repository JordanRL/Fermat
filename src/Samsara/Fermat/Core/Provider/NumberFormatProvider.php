<?php

namespace Samsara\Fermat\Core\Provider;

use Samsara\Fermat\Core\Enums\Currency;
use Samsara\Fermat\Core\Enums\NumberFormat;
use Samsara\Fermat\Core\Enums\NumberGrouping;

/**
 * @package Samsara\Fermat\Core
 */
class NumberFormatProvider
{

    /**
     * @param string         $number
     * @param NumberFormat   $format
     * @param NumberGrouping $grouping
     *
     * @return string
     */
    public static function addDelimiter(
        string         $number,
        NumberFormat   $format = NumberFormat::English,
        NumberGrouping $grouping = NumberGrouping::Standard
    ): string
    {
        $numberArr = str_split($number);
        $numberArr = array_reverse($numberArr);
        $formatted = '';

        for ($i = 0; $i < count($numberArr); $i++) {
            $j = $i + 1;

            $formatted = $numberArr[$i] . $formatted;

            if ($grouping == NumberGrouping::Standard) {
                if ($j % 3 == 0 && array_key_exists($i + 1, $numberArr)) {
                    $formatted = self::getDelimiterCharacter($format) . $formatted;
                }
            } elseif ($grouping == NumberGrouping::Indian) {
                if ($j == 3 && array_key_exists($i + 1, $numberArr)) {
                    $formatted = self::getDelimiterCharacter($format) . $formatted;
                } elseif (($j - 3) % 2 == 0 && ($j - 3) > 0 && array_key_exists($i + 1, $numberArr)) {
                    $formatted = self::getDelimiterCharacter($format) . $formatted;
                }
            }
        }

        return $formatted;
    }

    /**
     * @param string              $number
     * @param Currency            $currency
     * @param NumberFormat|null   $format
     * @param NumberGrouping|null $grouping
     *
     * @return string
     */
    public static function formatCurrency(
        string          $number,
        Currency        $currency,
        ?NumberFormat   $format = null,
        ?NumberGrouping $grouping = null
    ): string
    {
        $number = self::formatNumber($number, $format, $grouping);

        if (str_starts_with($number, '(') || str_starts_with($number, '-') || str_starts_with($number, '+')) {
            $number = str_replace('(', '(' . $currency->value, $number);
            $number = str_replace('-', '-' . $currency->value, $number);
            $number = str_replace('+', '+' . $currency->value, $number);
        } else {
            $number = $currency->value . $number;
        }

        return $number;
    }

    /**
     * @param string              $number
     * @param NumberFormat|null   $format
     * @param NumberGrouping|null $grouping
     *
     * @return string
     */
    public static function formatNumber(
        string          $number,
        ?NumberFormat   $format = null,
        ?NumberGrouping $grouping = null
    ): string
    {
        $posNegChar = '';

        if (!is_null($format)) {
            $negative = str_starts_with($number, '-');
            $number = str_replace('-', '', $number);
            if (str_contains($number, '.')) {
                [$wholePart, $decimalPart] = explode('.', $number);
            } else {
                $wholePart = $number;
                $decimalPart = '';
            }
            if (!is_null($grouping)) {
                $wholePart = self::addDelimiter($wholePart, $format, $grouping);
            }

            $posNegChar = $negative ? self::getNegativeCharacter($format) : self::getPositiveCharacter($format);

            $number = $wholePart;

            if ($decimalPart) {
                $number .= self::getRadixCharacter($format) . $decimalPart;
            }
        }

        if (str_contains($posNegChar, '\N')) {
            $number = str_replace('\N', $number, $posNegChar);
        } else {
            $number = $posNegChar . $number;
        }

        return $number;
    }

    /**
     * @param string $number
     *
     * @return string
     */
    public static function formatScientific(string $number): string
    {
        $negative = str_starts_with($number, '-') ? '-' : '';
        $imaginary = str_ends_with($number, 'i') ? 'i' : '';
        $number = str_replace('-', '', $number);
        $number = str_replace('i', '', $number);
        if (str_contains($number, '.')) {
            [$wholePart, $decimalPart] = explode('.', $number);
        } else {
            $wholePart = $number;
            $decimalPart = '';
        }

        $wholeSizeNonZero = strlen(ltrim($wholePart, '0'));
        $decimalSizeNonZero = strlen(ltrim($decimalPart, '0'));

        if ($wholeSizeNonZero) {
            $exponent = $wholeSizeNonZero;
            $exponent -= 1;
            $mantissa = substr($wholePart, 0, 1) . '.' . substr($wholePart, 1) . $decimalPart;
        } else {
            $exponent = strlen($decimalPart) - $decimalSizeNonZero;
            $exponent += 1;
            $mantissa = substr($decimalPart, $exponent - 1, 1) . '.' . substr($decimalPart, $exponent);
            $exponent *= -1;
        }

        $mantissa = rtrim($mantissa, '0');

        return $negative . $mantissa . 'E' . $exponent . $imaginary;
    }

    /**
     * @param NumberFormat $format
     *
     * @return string
     */
    public static function getDelimiterCharacter(NumberFormat $format): string
    {
        return match ($format) {
            NumberFormat::EnglishFinance,
            NumberFormat::English => ',',
            NumberFormat::EuropeanFinance,
            NumberFormat::European => '.',
            NumberFormat::TechnicalFinance,
            NumberFormat::Technical => ' ',
        };
    }

    /**
     * @param NumberFormat $format
     *
     * @return string
     */
    public static function getNegativeCharacter(NumberFormat $format): string
    {
        return match ($format) {
            NumberFormat::EnglishFinance,
            NumberFormat::EuropeanFinance,
            NumberFormat::TechnicalFinance => '(\N)',
            default => '-',
        };
    }

    /**
     * @param NumberFormat $format
     *
     * @return string
     */
    public static function getPositiveCharacter(NumberFormat $format): string
    {
        return match ($format) {
            NumberFormat::EnglishFinance,
            NumberFormat::EuropeanFinance,
            NumberFormat::TechnicalFinance => '+',
            default => ''
        };
    }

    /**
     * @param NumberFormat $format
     *
     * @return string
     */
    public static function getRadixCharacter(NumberFormat $format): string
    {
        return match ($format) {
            NumberFormat::EuropeanFinance,
            NumberFormat::European => ',',
            default => '.'
        };
    }

}