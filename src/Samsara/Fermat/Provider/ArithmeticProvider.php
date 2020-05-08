<?php

namespace Samsara\Fermat\Provider;

use Samsara\Exceptions\UsageError\IntegrityConstraint;

class ArithmeticProvider
{

    public static function add(string $number1, string $number2, $precision = 100)
    {
        return \bcadd($number1, $number2, $precision);
    }

    public static function subtract(string $left, string $right, $precision = 100)
    {
        return \bcsub($left, $right, $precision);
    }

    public static function multiply(string $number1, string $number2, $precision = 100)
    {
        return \bcmul($number1, $number2, $precision);
    }

    public static function divide(string $numerator, string $denominator, $precision = 100)
    {
        return \bcdiv($numerator, $denominator, $precision);
    }

    public static function pow(string $base, string $exponent, $precision = 100)
    {
        return \bcpow($base, $exponent, $precision);
    }

    public static function squareRoot(string $number, $precision = 100)
    {
        return \bcsqrt($number, $precision);
    }

    public static function modulo(string $number, $modulo)
    {
        return \bcmod($number, $modulo);
    }

    public static function compare(string $left, string $right, $precision = 100)
    {
        return \bccomp($left, $right, $precision);
    }

    public static function powmod(string $left, string $right, string $modulus, $precision = 100)
    {
        return \bcpowmod($left, $right, $modulus, $precision);
    }
    
    public static function factorial(string $number, int $precision = 100)
    {
        for ($x = 1; $number > 0; $number--) {
            $x = \bcmul($x, $number, $precision);
        }
        
        return $x;
    }

}