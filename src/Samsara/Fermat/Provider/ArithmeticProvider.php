<?php

namespace Samsara\Fermat\Provider;

class ArithmeticProvider
{

    public static function add($number1, $number2)
    {
        return \bcadd($number1, $number2, 100);
    }

    public static function subtract($left, $right)
    {
        return \bcsub($left, $right, 100);
    }

    public static function multiply($number1, $number2)
    {
        return \bcmul($number1, $number2, 100);
    }

    public static function divide($numerator, $denominator, $precision = 100)
    {
        return \bcdiv($numerator, $denominator, $precision);
    }

    public static function pow($base, $exponent)
    {
        return \bcpow($base, $exponent, 100);
    }

    public static function squareRoot($number, $precision = 100)
    {
        return \bcsqrt($number, $precision);
    }

    public static function modulo($number, $modulo)
    {
        return \bcmod($number, $modulo);
    }

    public static function compare($left, $right, $scale = 100)
    {
        return \bccomp($left, $right, $scale);
    }

    public static function powmod($left, $right, $modulus, $scale = 100)
    {
        return \bcpowmod($left, $right, $modulus, $scale);
    }
    
    public static function factorial($number)
    {
        for ($x = 1; $number > 0; $number--) {
            $x = \bcmul($x, $number, 100);
        }
        
        return $x;
    }

}