<?php

namespace Samsara\Fermat\Types\Traits\Trigonometry;

trait InverseTrigonometryNativeTrait
{

    protected function arcsinNative(): float
    {
        $thisNum = self::translateToNative($this);

        return asin($thisNum);
    }

    protected function arccosNative(): float
    {
        $thisNum = self::translateToNative($this);

        return acos($thisNum);
    }

    protected function arctanNative(): float
    {
        $thisNum = self::translateToNative($this);

        return atan($thisNum);
    }

    protected function arcsecNative(): float
    {
        $thisNum = self::translateToNative($this);
        $thisNum = 1/$thisNum;

        return acos($thisNum);
    }

    protected function arccscNative(): float
    {
        $thisNum = self::translateToNative($this);
        $thisNum = 1/$thisNum;

        return asin($thisNum);
    }

    protected function arccotNative(): float
    {
        $thisNum = self::translateToNative($this);
        $piDiv2 = M_PI_2;

        return $piDiv2 - atan($thisNum);
    }

}