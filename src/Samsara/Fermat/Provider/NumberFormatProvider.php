<?php

namespace Samsara\Fermat\Provider;

use Samsara\Fermat\Enums\NumberFormat;
use Samsara\Fermat\Enums\NumberGrouping;

/**
 *
 */
class NumberFormatProvider
{

    /**
     * @param string $number
     * @param NumberFormat $format
     * @param NumberGrouping $grouping
     * @return string
     */
    public static function addDelimiter(
        string $number,
        NumberFormat $format = NumberFormat::Standard,
        NumberGrouping $grouping = NumberGrouping::Standard
    ): string
    {
        $numberArr = str_split($number);
        $numberArr = array_reverse($numberArr);
        $formatted = '';

        for ($i = 0;$i < count($numberArr);$i++) {
            $j = $i + 1;

            $formatted = $numberArr[$i].$formatted;

            if ($grouping == NumberGrouping::Standard) {
                if ($j % 3 == 0) {
                    $formatted = self::getDelimiterCharacter($format).$formatted;
                }
            } elseif ($grouping == NumberGrouping::Indian) {
                if ($j == 3) {
                    $formatted = self::getDelimiterCharacter($format).$formatted;
                } elseif (($j - 3) % 2 == 0 && ($j - 3) > 0) {
                    $formatted = self::getDelimiterCharacter($format).$formatted;
                }
            }
        }

        return $formatted;
    }

    /**
     * @param NumberFormat $format
     * @return string
     */
    public static function getRadixCharacter(NumberFormat $format): string
    {
        return match ($format) {
            NumberFormat::European => ',',
            default => '.'
        };
    }

    /**
     * @param NumberFormat $format
     * @return string
     */
    public static function getDelimiterCharacter(NumberFormat $format): string
    {
        return match ($format) {
            NumberFormat::Standard => ',',
            NumberFormat::European => '.',
            NumberFormat::Technical => ' '
        };
    }

}