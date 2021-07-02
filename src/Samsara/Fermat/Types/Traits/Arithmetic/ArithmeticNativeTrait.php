<?php


namespace Samsara\Fermat\Types\Traits\Arithmetic;

use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

trait ArithmeticNativeTrait
{

    abstract protected static function translateToNative(DecimalInterface $num): float|int;

    protected function addNative(DecimalInterface $num)
    {
        $left = static::translateToNative($this);
        $right = static::translateToNative($num);

        $value = $left + $right;
        return (string)$value;
    }

    protected function subtractNative(DecimalInterface $num)
    {
        $left = static::translateToNative($this);
        $right = static::translateToNative($num);

        $value = $left - $right;
        return (string)$value;
    }

    protected function multiplyNative(DecimalInterface $num)
    {
        $left = static::translateToNative($this);
        $right = static::translateToNative($num);

        $value = $left * $right;
        return (string)$value;
    }

    protected function divideNative(DecimalInterface $num)
    {
        $left = static::translateToNative($this);
        $right = static::translateToNative($num);

        $value = $left / $right;
        return (string)$value;
    }

    protected function powNative(DecimalInterface $num)
    {
        $left = static::translateToNative($this);
        $right = static::translateToNative($num);

        $value = pow($left, $right);
        return (string)$value;
    }

    protected function sqrtNative()
    {
        $value = sqrt($this->asFloat());
        return (string)$value;
    }

}