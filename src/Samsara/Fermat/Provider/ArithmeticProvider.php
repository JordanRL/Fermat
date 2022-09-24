<?php

namespace Samsara\Fermat\Provider;

use Decimal\Decimal;
use Samsara\Exceptions\UsageError\IntegrityConstraint;

class ArithmeticProvider
{

    public static function add(string $number1, string $number2, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $number1 = new Decimal($number1, $scale+2);
            $number2 = new Decimal($number2, $scale+2);

            $result = $number1->add($number2);

            $result = $result->toFixed($scale+2, false);
        } else {
            $result = \bcadd($number1, $number2, $scale);
        }
        return $result;
    }

    public static function subtract(string $left, string $right, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $number1 = new Decimal($left, $scale+2);
            $number2 = new Decimal($right, $scale+2);

            $result = $number1->sub($number2);

            $result = $result->toFixed($scale+2, false);
        } else {
            $result = \bcsub($left, $right, $scale);
        }
        return $result;
    }

    public static function multiply(string $number1, string $number2, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $number1 = new Decimal($number1, $scale+2);
            $number2 = new Decimal($number2, $scale+2);

            $result = $number1->mul($number2);

            $result = $result->toFixed($scale+2, false);
        } else {
            $result = \bcmul($number1, $number2, $scale);
        }
        return $result;
    }

    public static function divide(string $numerator, string $denominator, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $number1 = new Decimal($numerator, $scale);
            $number2 = new Decimal($denominator, $scale);

            $result = $number1->div($number2);

            $result = $result->toFixed($scale, false, Decimal::ROUND_TRUNCATE);
        } else {
            $result = \bcdiv($numerator, $denominator, $scale);
        }
        return $result;
    }

    public static function pow(string $base, string $exponent, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $number1 = new Decimal($base, $scale+2);
            $number2 = new Decimal($exponent, $scale+2);

            $result = $number1->pow($number2);

            $result = $result->toFixed($scale+2, false);
        } else {
            $result = \bcpow($base, $exponent, $scale);
        }
        return $result;
    }

    public static function squareRoot(string $number, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $number = new Decimal($number, $scale+2);

            $result = $number->sqrt();

            $result = $result->toFixed($scale+2, false);
        } else {
            $result = \bcsqrt($number, $scale);
        }
        return $result;
    }

    public static function modulo(string $number, $modulo)
    {
        if (extension_loaded('decimal')) {
            $precision = (strlen($modulo) > 12 ? strlen($modulo) : 12);
            $number1 = new Decimal($number, $precision);
            $number2 = new Decimal($modulo, $precision);

            $result = $number1->mod($number2);

            $result = $result->toString();
        } else {
            $result = \bcmod($number, $modulo);
        }
        return $result;
    }

    public static function compare(string $left, string $right, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $number1 = new Decimal($left, $scale+2);
            $number2 = new Decimal($right, $scale+2);

            $result = $number1->compareTo($number2);
        } else {
            $result = \bccomp($left, $right, $scale);
        }
        return $result;
    }

    public static function powmod(string $left, string $right, string $modulus, $scale = 100)
    {
        if (extension_loaded('decimal')) {
            $number1 = new Decimal($left, $scale+2);
            $number2 = new Decimal($right, $scale+2);
            $number3 = new Decimal($modulus, $scale+2);

            $result = $number1->pow($number2)->mod($number3);

            $result = $result->toFixed($scale+2, false);
        } else {
            $result = \bcpowmod($left, $right, $modulus, $scale);
        }
        return $result;
    }

}