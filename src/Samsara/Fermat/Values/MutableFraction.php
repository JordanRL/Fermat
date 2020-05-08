<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Fraction;

class MutableFraction extends Fraction
{

    protected function setValue(ImmutableDecimal $numerator, ImmutableDecimal $denominator)
    {

        $this->numerator = $numerator;
        $this->denominator = $denominator;

        return $this;

    }

}