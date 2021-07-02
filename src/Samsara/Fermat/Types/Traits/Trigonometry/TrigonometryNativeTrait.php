<?php

namespace Samsara\Fermat\Types\Traits\Trigonometry;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

trait TrigonometryNativeTrait
{

    abstract protected static function translateToNative(DecimalInterface $num): float|int;

    protected function sinNative(): float
    {
        $thisNum = static::translateToNative($this);

        return sin($thisNum);
    }

    protected function cosNative(): float
    {
        $thisNum = static::translateToNative($this);

        return cos($thisNum);
    }

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

        $sin = $this->sinNative();

        if ($sin) {
            throw new IntegrityConstraint(
                'Value of out range, division by zero.',
                'Do not do this.',
                'Cannot calculate the secant of a value that has a sin() of 0.'
            );
        }

        return 1/$sin;
    }

    /**
     * @throws IntegrityConstraint
     */
    protected function cscNative(): float
    {
        $thisNum = static::translateToNative($this);

        $cos = $this->cosNative();

        if ($cos) {
            throw new IntegrityConstraint(
                'Value of out range, division by zero.',
                'Do not do this.',
                'Cannot calculate the cosecant of a value that has a cos() of 0.'
            );
        }

        return 1/$cos;
    }

    /**
     * @throws IntegrityConstraint
     */
    protected function cotNative(): float
    {
        $thisNum = static::translateToNative($this);

        $tan = $this->tanNative();

        if ($tan) {
            throw new IntegrityConstraint(
                'Value of out range, division by zero.',
                'Do not do this.',
                'Cannot calculate the cotangent of a value that has a tan() of 0.'
            );
        }

        return 1/$tan;
    }

}