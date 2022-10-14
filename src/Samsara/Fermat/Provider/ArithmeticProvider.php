<?php

namespace Samsara\Fermat\Provider;

use Decimal\Decimal;
use Samsara\Exceptions\UsageError\IntegrityConstraint;

/**
 *
 */
class ArithmeticProvider
{

    /**
     * @param string $number1
     * @param string $number2
     * @param $scale
     * @return string
     */
    public static function add(string $number1, string $number2, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $intDigits1 = self::integerDigits($number1);
            $intDigits2 = self::integerDigits($number2);
            $decimalScale = max($intDigits1, $intDigits2);
            $decimalScale = $decimalScale+$scale+1;
            $decimalScale = max($decimalScale, strlen($number1)+1, strlen($number2)+1);
            $number1 = new Decimal($number1, $decimalScale);
            $number2 = new Decimal($number2, $decimalScale);

            $result = $number1->add($number2);

            $result = $result->toFixed($scale, false, Decimal::ROUND_TRUNCATE);
        } else {
            $result = \bcadd($number1, $number2, $scale);
        }
        return $result;
    }

    /**
     * @param string $left
     * @param string $right
     * @param $scale
     * @return string
     */
    public static function subtract(string $left, string $right, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $intDigits1 = self::integerDigits($left);
            $intDigits2 = self::integerDigits($right);
            $decimalScale = max($intDigits1, $intDigits2);
            $decimalScale = $decimalScale+$scale+1;
            $decimalScale = max($decimalScale, strlen($left)+1, strlen($right)+1);
            $number1 = new Decimal($left, $decimalScale);
            $number2 = new Decimal($right, $decimalScale);

            $result = $number1->sub($number2);

            $result = $result->toFixed($scale, false, Decimal::ROUND_TRUNCATE);
        } else {
            $result = \bcsub($left, $right, $scale);
        }
        return $result;
    }

    /**
     * @param string $number1
     * @param string $number2
     * @param $scale
     * @return string
     */
    public static function multiply(string $number1, string $number2, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $intDigits1 = self::integerDigits($number1);
            $intDigits2 = self::integerDigits($number2);
            $decimalScale = max($intDigits1, $intDigits2);
            $decimalScale = $decimalScale+$scale+1;
            $decimalScale = max($decimalScale, strlen($number1)+1, strlen($number2)+1);
            $number1 = new Decimal($number1, $decimalScale);
            $number2 = new Decimal($number2, $decimalScale);

            $result = $number1->mul($number2);

            $result = $result->toFixed($scale, false, Decimal::ROUND_TRUNCATE);
        } else {
            $result = \bcmul($number1, $number2, $scale);
        }
        return $result;
    }

    /**
     * @param string $numerator
     * @param string $denominator
     * @param $scale
     * @return string
     */
    public static function divide(string $numerator, string $denominator, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $intDigits1 = self::integerDigits($numerator);
            $intDigits2 = self::integerDigits($denominator);
            $decimalScale = max($intDigits1, $intDigits2);
            $decimalScale = $decimalScale+$scale+1;
            $decimalScale = max($decimalScale, strlen($numerator)+1, strlen($denominator)+1);
            $number1 = new Decimal($numerator, $decimalScale);
            $number2 = new Decimal($denominator, $decimalScale);

            $result = $number1->div($number2);

            $result = $result->toFixed($scale, false, Decimal::ROUND_TRUNCATE);
        } else {
            $result = \bcdiv($numerator, $denominator, $scale);
        }
        return $result;
    }

    /**
     * @param string $base
     * @param string $exponent
     * @param $scale
     * @return string
     */
    public static function pow(string $base, string $exponent, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $intDigits1 = self::integerDigits($base);
            $intDigits2 = self::integerDigits($exponent);
            $decimalScale = max($intDigits1, $intDigits2);
            $decimalScale = $decimalScale+$scale+1;
            $decimalScale = max($decimalScale, strlen($base)+1, strlen($exponent)+1);
            $number1 = new Decimal($base, $decimalScale);
            $number2 = new Decimal($exponent, $decimalScale);

            $result = $number1->pow($number2);

            $result = $result->toFixed($scale, false, Decimal::ROUND_TRUNCATE);
        } else {
            $result = \bcpow($base, $exponent, $scale);
        }
        return $result;
    }

    /**
     * @param string $number
     * @param $scale
     * @return string
     */
    public static function squareRoot(string $number, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $intDigits1 = self::integerDigits($number);
            $decimalScale = $intDigits1+$scale+1;
            $decimalScale = max($decimalScale, strlen($number)+1);
            $number = new Decimal($number, $decimalScale);

            $result = $number->sqrt();

            $result = $result->toFixed($scale, false, Decimal::ROUND_TRUNCATE);
        } else {
            $result = \bcsqrt($number, $scale);
        }
        return $result;
    }

    /**
     * @param string $number
     * @param $modulo
     * @return string
     */
    public static function modulo(string $number, $modulo)
    {
        if (extension_loaded('decimal')) {
            $decimalScale = (max(strlen($modulo)*2, strlen($number)*2));
            $number1 = new Decimal($number, $decimalScale);
            $number2 = new Decimal($modulo, $decimalScale);

            $result = $number1->mod($number2);

            $result = $result->toString();
        } else {
            $result = \bcmod($number, $modulo);
        }
        return $result;
    }

    /**
     * @param string $left
     * @param string $right
     * @param $scale
     * @return int
     */
    public static function compare(string $left, string $right, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $intDigits1 = self::integerDigits($left);
            $intDigits2 = self::integerDigits($right);
            $decimalScale = max($intDigits1, $intDigits2);
            $decimalScale = $decimalScale+$scale+1;
            $decimalScale = max($decimalScale, strlen($left)+1, strlen($right)+1);
            $number1 = new Decimal($left, $decimalScale);
            $number2 = new Decimal($right, $decimalScale);

            $result = $number1->compareTo($number2);
        } else {
            $result = \bccomp($left, $right, $scale);
        }
        return $result;
    }

    /**
     * @param string $left
     * @param string $right
     * @param string $modulus
     * @param $scale
     * @return string
     */
    public static function powmod(string $left, string $right, string $modulus, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $number1 = new Decimal($left, $scale+2);
            $number2 = new Decimal($right, $scale+2);
            $number3 = new Decimal($modulus, $scale+2);

            $result = $number1->pow($number2)->mod($number3);

            $result = $result->toFixed($scale+2);
        } else {
            $result = \bcpowmod($left, $right, $modulus, $scale);
        }
        return $result;
    }

    private static function integerDigits(string $number): int
    {
        return strlen($number);
    }

}