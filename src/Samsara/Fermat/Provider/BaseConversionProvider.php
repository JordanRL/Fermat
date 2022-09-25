<?php

namespace Samsara\Fermat\Provider;

use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

/**
 *
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
     * @param DecimalInterface $number
     * @param NumberBase|null $toBase
     * @return string
     */
    public static function convertFromBaseTen(DecimalInterface $number, ?NumberBase $toBase = null): string
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

    private static function _toBase(DecimalInterface $input, int $base): string
    {
        $intPart = '0';
        $decPart = '0';
        $baseNum = Numbers::make(Numbers::IMMUTABLE, $base, $input->getScale());
        $inputInt = Numbers::make(Numbers::IMMUTABLE, $input->getWholePart());
        $inputDec = Numbers::make(Numbers::IMMUTABLE, strrev($input->getDecimalPart()));
        $runningTotal = Numbers::makeZero();

        if ($inputInt->isGreaterThan(0)) {
            for ($pos = 0; $runningTotal->isLessThan($inputInt); $pos++) {
                $basePow = $pos ?
                    $baseNum->pow($pos) :
                    $baseNum->pow($pos+1);
                $intPart = $pos ? $intPart : '';
                $mod = $pos ?
                    (int)gmp_strval(gmp_div_q($inputInt->getAsBaseTenRealNumber(), $basePow->getAsBaseTenRealNumber())) :
                    (int)gmp_strval(gmp_div_r($inputInt->getAsBaseTenRealNumber(), $basePow->getAsBaseTenRealNumber()));
                $intPart = self::$chars[$mod] . $intPart;
                $runningTotal = $pos ?
                    $runningTotal->add($basePow->multiply($mod)) :
                    $runningTotal->add($mod);
            }
        }

        if ($inputDec->isGreaterThan(0)) {
            $runningTotal = Numbers::makeZero();
            for ($pos = 0; $runningTotal->isLessThan($decPart); $pos++) {
                $basePow = $baseNum->pow($pos);
                $decPart = $pos ? $decPart : '';
                $mod = $pos ?
                    (int)gmp_strval(gmp_div_q($inputDec->getAsBaseTenRealNumber(), $baseNum->pow($pos)->getAsBaseTenRealNumber())) :
                    $inputDec->modulo($baseNum->pow($pos))->asInt();
                $decPart = self::$chars[$mod] . $decPart;
                $runningTotal = $runningTotal->add($basePow->multiply($mod));
            }
        }

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

}