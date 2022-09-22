<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

trait LogNativeTrait
{

    protected function expNative(): float
    {
        $thisNum = self::translateToNative($this);

        return exp($thisNum);
    }

    protected function lnNative(): float
    {
        $thisNum = self::translateToNative($this);

        return log($thisNum);
    }

    protected function log10Native(): float
    {
        $thisNum = self::translateToNative($this);

        return log10($thisNum);
    }

}