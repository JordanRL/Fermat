<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Number;
use Samsara\Fermat\Values\Base\NumberInterface;

class MutableNumber extends Number implements NumberInterface
{

    public function modulo($mod)
    {
        $oldBase = $this->convertForModification();

        return (new MutableNumber(bcmod($this->getValue(), $mod), $this->getPrecision()))->convertFromModification($oldBase);
    }

    protected function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

}