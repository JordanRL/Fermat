<?php

namespace Samsara\Fermat\Types\Traits\Trigonometry;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

/**
 *
 */
trait TrigonometryNativeTrait
{

    /**
     * @param DecimalInterface $num
     * @return float|int
     */
    abstract protected static function translateToNative(DecimalInterface $num): float|int;

    /**
     * @return float
     */
    protected function sinNative(): float
    {
        $thisNum = static::translateToNative($this);

        return sin($thisNum);
    }

    /**
     * @return float
     */
    protected function cosNative(): float
    {
        $thisNum = static::translateToNative($this);

        return cos($thisNum);
    }

    /**
     * @return float
     */
    protected function tanNative(): float
    {
        $thisNum = static::translateToNative($this);

        return tan($thisNum);
    }

    /**
     * @throws IntegrityConstraint
     */
    protected function secNative(): float
    {
        $thisNum = static::translateToNative($this);
        $cos = cos($thisNum);

        if ($cos == 0) {
            throw new IntegrityConstraint(
                'Value of out range, division by zero.',
                'Do not do this.',
                'Cannot calculate the secant of a value that has a sin() of 0.'
            );
        }

        return 1/$cos;
    }

    /**
     * @throws IntegrityConstraint
     */
    protected function cscNative(): float
    {
        $thisNum = static::translateToNative($this);
        $sin = sin($thisNum);

        if ($sin == 0) {
            throw new IntegrityConstraint(
                'Value of out range, division by zero.',
                'Do not do this.',
                'Cannot calculate the cosecant of a value that has a cos() of 0.'
            );
        }

        return 1/$sin;
    }

    /**
     * @throws IntegrityConstraint
     */
    protected function cotNative(): float
    {
        $thisNum = static::translateToNative($this);
        $tan = tan($thisNum);

        if ($tan == 0) {
            throw new IntegrityConstraint(
                'Value of out range, division by zero.',
                'Do not do this.',
                'Cannot calculate the cotangent of a value that has a tan() of 0.'
            );
        }

        return 1/$tan;
    }

    /**
     * @return float
     */
    protected function sinhNative(): float
    {
        $thisNum = self::translateToNative($this);

        return sinh($thisNum);
    }

    /**
     * @return float
     */
    protected function coshNative(): float
    {
        $thisNum = self::translateToNative($this);

        return cosh($thisNum);
    }

    /**
     * @return float
     */
    protected function tanhNative(): float
    {
        $thisNum = self::translateToNative($this);

        return tanh($thisNum);
    }

    /**
     * @return float
     * @throws IntegrityConstraint
     */
    protected function sechNative(): float
    {
        $thisNum = self::translateToNative($this);

        if ($thisNum == 0) {
            throw new IntegrityConstraint(
                'Value of out range, division by zero.',
                'Do not do this.',
                'Cannot calculate the hyperbolic secant of a value that has a sinh() of 0.'
            );
        }

        return 1/cosh($thisNum);
    }

    /**
     * @return float
     */
    protected function cschNative(): float
    {
        $thisNum = self::translateToNative($this);

        return 1/sinh($thisNum);
    }

    /**
     * @return float
     * @throws IntegrityConstraint
     */
    protected function cothNative(): float
    {
        $thisNum = self::translateToNative($this);

        if ($thisNum == 0) {
            throw new IntegrityConstraint(
                'Value of out range, division by zero.',
                'Do not do this.',
                'Cannot calculate the hyperbolic cotangent of a value that has a tanh() of 0.'
            );
        }

        return 1/tanh($thisNum);
    }

}