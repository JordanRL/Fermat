<?php

namespace Samsara\Fermat\Core\Types\Traits\Decimal;

use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;

/**
 *
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