<?php


namespace Samsara\Fermat\Types\Traits\Arithmetic;

use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

trait ArithmeticNativeTrait
{

    protected function arithmeticNativeMap()
    {

        return [
            'add' => 'addNative',
            'subtract' => 'subtractNative',
            'divide' => 'divideNative',
            'multiply' => 'multiplyNative',
            'pow' => 'powNative',
            'sqrt' => 'sqrtNative',
        ];

    }

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
        $value = (string)$value;

        return $value;
    }

    protected function subtractNative(DecimalInterface $num)
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left - $right;
        $value = (string)$value;

        return $value;
    }

    protected function multiplyNative(DecimalInterface $num)
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left * $right;
        $value = (string)$value;

        return $value;
    }

    protected function divideNative(DecimalInterface $num)
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left / $right;
        $value = (string)$value;

        return $value;
    }

    protected function powNative(DecimalInterface $num)
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = pow($left, $right);
        $value = (string)$value;

        return $value;
    }

    protected function sqrtNative()
    {
        $value = sqrt($this->asFloat());
        $value = (string)$value;

        return $value;
    }

}