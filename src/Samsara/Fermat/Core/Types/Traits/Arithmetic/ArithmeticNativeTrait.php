<?php


namespace Samsara\Fermat\Core\Types\Traits\Arithmetic;

use Samsara\Fermat\Core\Types\Decimal;

/**
 * @package Samsara\Fermat\Core
 */
trait ArithmeticNativeTrait
{

    /**
     * @param Decimal $num
     * @return int|float
     */
    protected static function translateToNative(Decimal $num): int|float
    {
        return ($num->isInt() ? $num->asInt() : $num->asFloat());
    }

    /**
     * @param Decimal $num
     * @return string
     */
    protected function addNative(Decimal $num): string
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left + $right;
        return (string)$value;
    }

    /**
     * @param Decimal $num
     * @return string
     */
    protected function subtractNative(Decimal $num): string
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left - $right;
        return (string)$value;
    }

    /**
     * @param Decimal $num
     * @return string
     */
    protected function multiplyNative(Decimal $num): string
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left * $right;
        return (string)$value;
    }

    /**
     * @param Decimal $num
     * @return string
     */
    protected function divideNative(Decimal $num): string
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left / $right;
        return (string)$value;
    }

    /**
     * @param Decimal $num
     * @return string
     */
    protected function powNative(Decimal $num): string
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = pow($left, $right);
        return (string)$value;
    }

    /**
     * @return string
     */
    protected function sqrtNative(): string
    {
        $left = self::translateToNative($this);

        $value = sqrt(abs($left));
        return (string)$value;
    }

}