<?php

namespace Samsara\Fermat\Provider;

use Samsara\Exceptions\UsageError\IntegrityConstraint;

class ArithmeticProvider
{

    public static function add(string $number1, string $number2, $scale = 100)
    {
        return \bcadd($number1, $number2, $scale);
    }

    public static function subtract(string $left, string $right, $scale = 100)
    {
        return \bcsub($left, $right, $scale);
    }

    public static function multiply(string $number1, string $number2, $scale = 100)
    {
        return \bcmul($number1, $number2, $scale);
    }

    public static function divide(string $numerator, string $denominator, $scale = 100)
    {
        return \bcdiv($numerator, $denominator, $scale);
    }

    public static function pow(string $base, string $exponent, $scale = 100)
    {
        return \bcpow($base, $exponent, $scale);
    }

    public static function squareRoot(string $number, $scale = 100)
    {
        return \bcsqrt($number, $scale);
    }

    public static function modulo(string $number, $modulo)
    {
        return \bcmod($number, $modulo);
    }

    public static function compare(string $left, string $right, $scale = 100)
    {
        return \bccomp($left, $right, $scale);
    }

    public static function powmod(string $left, string $right, string $modulus, $scale = 100)
    {
        return \bcpowmod($left, $right, $modulus, $scale);
    }
    
    public static function factorial(string $number, int $scale = 100)
    {
        for ($x = 1; $number > 0; $number--) {
            $x = \bcmul($x, $number, $scale);
        }
        
        return $x;
    }

}