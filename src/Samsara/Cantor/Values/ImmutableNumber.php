<?php

namespace Samsara\Cantor\Values;

use Samsara\Cantor\Values\Base\BaseValue;
use Samsara\Cantor\Values\Base\NumberInterface;

class ImmutableNumber extends BaseValue implements NumberInterface
{

    public function modulo($mod)
    {
        $oldBase = $this->convertForModification();

        return (new ImmutableNumber(bcmod($this->getValue(), $mod), $this->getPrecision()))->convertFromModification($oldBase);
    }

    protected function setValue($value)
    {
        return new ImmutableNumber($value, $this->getPrecision(), $this->getBase());
    }

}