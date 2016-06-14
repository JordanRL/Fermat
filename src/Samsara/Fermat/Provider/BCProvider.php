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

    public static function pow($base, $exponent)
    {
        return bcpow($base, $exponent);
    }

    public static function squareRoot($number, $precision = null)
    {
        return bcsqrt($number, $precision);
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
    
    public static function factorial($number)
    {
        for ($x = 1; $number > 0; $number--) {
            $x = bcmul($x, $number);
        }
        
        return $x;
    }

}