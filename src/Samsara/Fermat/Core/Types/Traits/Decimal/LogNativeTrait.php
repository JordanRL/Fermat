<?php

namespace Samsara\Fermat\Core\Types\Traits\Decimal;

/**
 * @package Samsara\Fermat\Core
 */
trait LogNativeTrait
{

    /**
     * @return float
     */
    protected function expNative(): float
    {
        $thisNum = self::translateToNative($this);

        return exp($thisNum);
    }

    /**
     * @return float
     */
    protected function lnNative(): float
    {
        $thisNum = self::translateToNative($this);

        return log($thisNum);
    }

    /**
     * @return float
     */
    protected function log10Native(): float
    {
        $thisNum = self::translateToNative($this);

        return log10($thisNum);
    }

}