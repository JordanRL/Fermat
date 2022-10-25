<?php

namespace Samsara\Fermat\Core\Provider;

use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
class BaseConversionProvider
{

    private static array $chars = [
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        'A',
        'B',
        'C',
        'D',
        'E',
        'F'
    ];

    /**
     * @param Decimal $number
     * @param NumberBase|null $toBase
     * @return string
     */
    public static function convertFromBaseTen(Decimal $number, ?NumberBase $toBase = null): string
    {
        $base = $toBase ?? $number->getBase();

        return self::_toBase($number, $base->value);
    }

    /**
     * @param string $number
     * @param NumberBase $fromBase
     * @return string
     */
    public static function convertStringToBaseTen(string $number, NumberBase $fromBase): string
    {
        if (str_contains($number, '.')) {
            $sign = str_starts_with($number, '-') ? '-' : '';
            [$intPart, $decPart] = explode('.', $number);
            $intPart = self::_fromBase($intPart, $fromBase->value);
            $decPart = strrev(self::_fromBase(strrev($decPart), $fromBase->value));

            return $sign.$intPart.'.'.$decPart;
        } else {
            $sign = str_starts_with($number, '-') ? '-' : '';
            return $sign.self::_fromBase($number, $fromBase->value);
        }
    }

    private static function _toBase(Decimal $input, int $base): string
    {
        $baseNum = Numbers::make(Numbers::IMMUTABLE, $base, $input->getScale());
        $inputInt = Numbers::make(Numbers::IMMUTABLE, $input->getWholePart());
        $inputDec = Numbers::make(Numbers::IMMUTABLE, strrev($input->getDecimalPart()));

        $intPart = self::_toBasePart($baseNum, $inputInt);
        $decPart = self::_toBasePart($baseNum, $inputDec);

        $sign = $input->isNegative() ? '-' : '';

        return $sign.$intPart.'.'.strrev($decPart);
    }

    private static function _fromBase(string $number, int $base): string
    {
        if (str_starts_with($number, '-')) {
            $number = trim($number, '-');
        }

        $output = Numbers::makeZero();
        $input = str_split($number);
        $input = array_reverse($input);
        $pos = 0;
        $base = Numbers::make(Numbers::IMMUTABLE, $base);

        foreach ($input as $char) {
            $output = $output->add($base->pow($pos)->multiply(array_search($char, self::$chars)));
            $pos++;
        }

        return $output->getAsBaseTenRealNumber();
    }

    public static function _toBasePart(ImmutableDecimal $baseNum, ImmutableDecimal $startVal): string
    {
        if ($startVal->isGreaterThan(0)) {
            $stringVal = '';
            $runningTotal = Numbers::make(Numbers::IMMUTABLE, $startVal->getAsBaseTenRealNumber());
            while ($runningTotal->isGreaterThan(0)) {
                $current = gmp_div_qr($runningTotal->getAsBaseTenRealNumber(), $baseNum->getAsBaseTenRealNumber());
                $mod = (int)$current[1];
                $stringVal = self::$chars[$mod] . $stringVal;
                $runningTotal = Numbers::make(Numbers::IMMUTABLE, $current[0]);
            }
        } else {
            $stringVal = '0';
        }

        return $stringVal;
    }

}