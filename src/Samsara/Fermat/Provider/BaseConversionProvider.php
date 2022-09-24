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
            [$intPart, $decPart] = explode('.', $number);
            $intPart = self::_fromBase($intPart, $fromBase->value);
            $decPart = strrev(self::_fromBase(strrev($decPart), $fromBase->value));

            return $intPart.'.'.$decPart;
        } else {
            return self::_fromBase($number, $fromBase->value);
        }
    }

    private static function _toBase(DecimalInterface $input, int $base): string
    {
        $intPart = '';
        $decPart = '';
        $baseNum = Numbers::make(Numbers::IMMUTABLE, $base, $input->getScale());
        $inputInt = Numbers::make(Numbers::IMMUTABLE, $input->getWholePart());
        $inputDec = Numbers::make(Numbers::IMMUTABLE, strrev($input->getDecimalPart()));
        $posNum = Numbers::makeOne();

        for ($pos = 0;$baseNum->pow($pos)->isLessThan($intPart);$pos++) {
            if ($pos == 0) {
                $mod = $inputInt->modulo($baseNum->pow($pos+1));
            } else {
                $mod = $inputInt->subtract($baseNum->pow($pos))->modulo($baseNum->pow($pos + 1));
            }
            $intPart = self::$chars[$mod->asInt()].$intPart;
        }

        for ($pos = 0;$baseNum->pow($pos)->isLessThan($inputDec);$pos++) {
            if ($pos == 0) {
                $mod = $inputDec->modulo($baseNum->pow($pos+1));
            } else {
                $mod = $inputDec->subtract($baseNum->pow($pos))->modulo($baseNum->pow($pos + 1));
            }
            $decPart = self::$chars[$mod->asInt()].$decPart;
        }

        return $intPart.'.'.strrev($decPart);
    }

    private static function _fromBase(string $number, int $base): string
    {
        $output = Numbers::makeZero();
        $input = str_split($number);
        $pos = 0;
        $base = Numbers::make(Numbers::IMMUTABLE, $base);

        foreach ($input as $char) {
            $output = $output->add($base->pow($pos)->multiply(array_search($char, self::$chars)));
            $pos++;
        }

        return $output->getAsBaseTenRealNumber();
    }

}