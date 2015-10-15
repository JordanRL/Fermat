<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Values\Base\BaseValue;
use Samsara\Fermat\Values\Base\NumberInterface;

class FluentNumber extends BaseValue implements NumberInterface
{

    public function modulo($mod)
    {
        $oldBase = $this->convertForModification();

        return (new FluentNumber(bcmod($this->getValue(), $mod), $this->getPrecision()))->convertFromModification($oldBase);
    }

    protected function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

}