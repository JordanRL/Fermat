<?php


namespace Samsara\Fermat\Core\Types\Traits\Arithmetic;

use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;

/**
 *
 */
trait ArithmeticNativeTrait
{

    /**
     * @param DecimalInterface $num
     * @return int|float
     */
    protected static function translateToNative(DecimalInterface $num): int|float
    {
        return ($num->isInt() ? $num->asInt() : $num->asFloat());
    }

    /**
     * @param DecimalInterface $num
     * @return int|float
     */
    protected function addNative(DecimalInterface $num): int|float
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left + $right;
        return (string)$value;
    }

    /**
     * @param DecimalInterface $num
     * @return int|float
     */
    protected function subtractNative(DecimalInterface $num): int|float
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left - $right;
        return (string)$value;
    }

    /**
     * @param DecimalInterface $num
     * @return int|float
     */
    protected function multiplyNative(DecimalInterface $num): int|float
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left * $right;
        return (string)$value;
    }

    /**
     * @param DecimalInterface $num
     * @return int|float
     */
    protected function divideNative(DecimalInterface $num): int|float
    {
        $left = self::translateToNative($this);
        $right = self::translateToNative($num);

        $value = $left / $right;
        return (string)$value;
    }

    /**
     * @param DecimalInterface $num
     * @return int|float
     */
    protected function powNative(DecimalInterface $num): int|float
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