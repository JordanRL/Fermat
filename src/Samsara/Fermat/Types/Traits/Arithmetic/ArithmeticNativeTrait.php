<?php


namespace Samsara\Fermat\Types\Traits\Arithmetic;

use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

trait ArithmeticNativeTrait
{

    /**
     * @param DecimalInterface $num
     * @return float|int
     */
    protected static function translateToNative(DecimalInterface $num)
    {
        return ($num->isInt() ? $num->asInt() : $num->asFloat());
    }

    protected function addNative(DecimalInterface $num)
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left + $right;
        return (string)$value;
    }

    protected function subtractNative(DecimalInterface $num)
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left - $right;
        return (string)$value;
    }

    protected function multiplyNative(DecimalInterface $num)
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left * $right;
        return (string)$value;
    }

    protected function divideNative(DecimalInterface $num)
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left / $right;
        return (string)$value;
    }

    protected function powNative(DecimalInterface $num)
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = pow($left, $right);
        return (string)$value;
    }

    protected function sqrtNative()
    {
        $value = sqrt($this->asFloat());
        return (string)$value;
    }

}