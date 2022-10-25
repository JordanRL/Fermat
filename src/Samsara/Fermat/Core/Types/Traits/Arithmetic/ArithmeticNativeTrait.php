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
     * @return int|float
     */
    protected function addNative(Decimal $num): int|float
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left + $right;
        return (string)$value;
    }

    /**
     * @param Decimal $num
     * @return int|float
     */
    protected function subtractNative(Decimal $num): int|float
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left - $right;
        return (string)$value;
    }

    /**
     * @param Decimal $num
     * @return int|float
     */
    protected function multiplyNative(Decimal $num): int|float
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left * $right;
        return (string)$value;
    }

    /**
     * @param Decimal $num
     * @return int|float
     */
    protected function divideNative(Decimal $num): int|float
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left / $right;
        return (string)$value;
    }

    /**
     * @param Decimal $num
     * @return int|float
     */
    protected function powNative(Decimal $num): int|float
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = pow($left, $right);
        return (string)$value;
    }

    /**
     * @return int|float
     */
    protected function sqrtNative(): int|float
    {
        $value = sqrt($this->abs()->asFloat());
        return (string)$value;
    }

}