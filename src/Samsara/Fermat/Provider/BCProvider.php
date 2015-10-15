<?php

namespace Samsara\Fermat\Provider;

class BCProvider
{

    public static function add($number1, $number2)
    {
        return bcadd($number1, $number2);
    }

    public static function subtract($left, $right)
    {
        return bcsub($left, $right);
    }

    public static function multiply($number1, $number2)
    {
        return bcmul($number1, $number2);
    }

    public static function divide($numerator, $denominator, $precision = null)
    {
        return bcdiv($numerator, $denominator, $precision);
    }

    public static function exp($base, $exponent)
    {
        return bcpow($base, $exponent);
    }

    public static function squareRoot($number)
    {
        return bcsqrt($number);
    }

    public static function modulo($number, $modulo)
    {
        return bcmod($number, $modulo);
    }

    public static function compare($left, $right, $scale = null)
    {
        return bccomp($left, $right, $scale);
    }

    public static function powmod($left, $right, $modulus, $scale = null)
    {
        return bcpowmod($left, $right, $modulus, $scale);
    }

}