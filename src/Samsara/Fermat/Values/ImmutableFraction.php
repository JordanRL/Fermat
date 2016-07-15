<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Fraction;

class ImmutableFraction extends Fraction
{

    protected function setValue(ImmutableNumber $numerator, ImmutableNumber $denominator)
    {

        return new ImmutableFraction($numerator->getValue().'/'.$denominator->getValue(), $this->precision, $this->base);

    }

}