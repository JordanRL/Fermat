<?php

namespace Samsara\Fermat\Core\Provider;

use Decimal\Decimal;
use Samsara\Fermat\Core\Enums\CalcOperation;

/**
 *
 */
class ArithmeticProvider
{

    /**
     * @param string $number1
     * @param string $number2
     * @param int $scale
     * @return string
     */
    public static function add(string $number1, string $number2, int $scale = 100): string
    {
        return self::performBaseArithmetic($number1, $number2, $scale, CalcOperation::Addition);
    }

    /**
     * @param string $left
     * @param string $right
     * @param int $scale
     * @return string
     */
    public static function subtract(string $left, string $right, int $scale = 100): string
    {
        return self::performBaseArithmetic($left, $right, $scale, CalcOperation::Subtraction);
    }

    /**
     * @param string $number1
     * @param string $number2
     * @param int $scale
     * @return string
     */
    public static function multiply(string $number1, string $number2, int $scale = 100): string
    {
        return self::performBaseArithmetic($number1, $number2, $scale, CalcOperation::Multiplication);
    }

    /**
     * @param string $numerator
     * @param string $denominator
     * @param int $scale
     * @return string
     */
    public static function divide(string $numerator, string $denominator, int $scale = 100): string
    {
        return self::performBaseArithmetic($numerator, $denominator, $scale, CalcOperation::Division);
    }

    /**
     * @param string $base
     * @param string $exponent
     * @param int $scale
     * @return string
     */
    public static function pow(string $base, string $exponent, int $scale = 100): string
    {
        return self::performBaseArithmetic($base, $exponent, $scale, CalcOperation::Power);
    }

    /**
     * @param string $number
     * @param int $scale
     * @return string
     */
    public static function squareRoot(string $number, int $scale = 100): string
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
     * @param string $left
     * @param string $right
     * @param int $scale
     * @return int
     */
    public static function compare(string $left, string $right, int $scale = 100): int
    {
        return self::performBaseArithmetic($left, $right, $scale, CalcOperation::Compare);
    }

    private static function performBaseArithmetic(
        string $left,
        string $right,
        int $scale,
        CalcOperation $operation
    ): string|int
    {
        if (extension_loaded('decimal')) {
            $intDigits1 = self::integerDigits($left);
            $intDigits2 = self::integerDigits($right);
            $decimalScale = max($intDigits1, $intDigits2);
            $decimalScale = $decimalScale+$scale+1;
            $decimalScale = max($decimalScale, strlen($left)+1, strlen($right)+1);
            $number1 = new Decimal($left, $decimalScale);
            $number2 = new Decimal($right, $decimalScale);

            $result = match ($operation) {
                CalcOperation::Addition => $number1->add($number2)->toFixed($scale, false, Decimal::ROUND_TRUNCATE),
                CalcOperation::Subtraction => $number1->sub($number2)->toFixed($scale, false, Decimal::ROUND_TRUNCATE),
                CalcOperation::Multiplication => $number1->mul($number2)->toFixed($scale, false, Decimal::ROUND_TRUNCATE),
                CalcOperation::Division => $number1->div($number2)->toFixed($scale, false, Decimal::ROUND_TRUNCATE),
                CalcOperation::Power => $number1->pow($number2)->toFixed($scale, false, Decimal::ROUND_TRUNCATE),
                CalcOperation::Compare => $number1->compareTo($number2)
            };
        } else {
            $result = match ($operation) {
                CalcOperation::Addition => \bcadd($left, $right, $scale),
                CalcOperation::Subtraction => \bcsub($left, $right, $scale),
                CalcOperation::Multiplication => \bcmul($left, $right, $scale),
                CalcOperation::Division => \bcdiv($left, $right, $scale),
                CalcOperation::Power => \bcpow($left, $right, $scale),
                CalcOperation::Compare => \bccomp($left, $right, $scale),
            };
        }
        return $result;
    }

    private static function integerDigits(string $number): int
    {
        return strlen($number);
    }

}