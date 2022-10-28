<?php

namespace Samsara\Fermat\Core\Types\Base\Traits;

/**
 * @package Samsara\Fermat\Core
 */
trait InverseTrigonometryNativeTrait
{

    /**
     * @return float
     */
    protected function arccosNative(): float
    {
        $thisNum = self::translateToNative($this);

        return acos($thisNum);
    }

    /**
     * @return float
     */
    protected function arccotNative(): float
    {
        $thisNum = self::translateToNative($this);
        $piDiv2 = M_PI_2;

        if ($thisNum < 0) {
            return -1 * ($piDiv2 - atan(abs($thisNum)));
        } else {
            return $piDiv2 - atan($thisNum);
        }
    }

    /**
     * @return float
     */
    protected function arccscNative(): float
    {
        $thisNum = self::translateToNative($this);
        $thisNum = 1 / $thisNum;

        return asin($thisNum);
    }

    /**
     * @return float
     */
    protected function arcsecNative(): float
    {
        $thisNum = self::translateToNative($this);
        $thisNum = 1 / $thisNum;

        return acos($thisNum);
    }

    /**
     * @return float
     */
    protected function arcsinNative(): float
    {
        $thisNum = self::translateToNative($this);

        return asin($thisNum);
    }

    /**
     * @return float
     */
    protected function arctanNative(): float
    {
        $thisNum = self::translateToNative($this);

        return atan($thisNum);
    }

}