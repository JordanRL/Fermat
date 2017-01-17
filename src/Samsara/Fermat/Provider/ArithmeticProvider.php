<?php

namespace Samsara\Fermat\Provider;

class ArithmeticProvider
{

    public static function add($number1, $number2, $precision = 100)
    {
        return \bcadd($number1, $number2, $precision);
    }

    public static function subtract($left, $right, $precision = 100)
    {
        return \bcsub($left, $right, $precision);
    }

    public static function multiply($number1, $number2, $precision = 100)
    {
        return \bcmul($number1, $number2, $precision);
    }

    public static function divide($numerator, $denominator, $precision = 100)
    {
        return \bcdiv($numerator, $denominator, $precision);
    }

    public static function pow($base, $exponent, $precision = 100)
    {
        return \bcpow($base, $exponent, $precision);
    }

    public static function squareRoot($number, $precision = 100)
    {
        return \bcsqrt($number, $precision);
    }

    public static function modulo($number, $modulo)
    {
        return \bcmod($number, $modulo);
    }

    public static function compare($left, $right, $precision = 100)
    {
        return \bccomp($left, $right, $precision);
    }

    public static function powmod($left, $right, $modulus, $precision = 100)
    {
        return \bcpowmod($left, $right, $modulus, $precision);
    }
    
    public static function factorial($number, $precision = 100)
    {
        for ($x = 1; $number > 0; $number--) {
            $x = \bcmul($x, $number, $precision);
        }
        
        return $x;
    }

}