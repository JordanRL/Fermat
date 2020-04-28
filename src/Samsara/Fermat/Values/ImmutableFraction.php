<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Fraction;

class ImmutableFraction extends Fraction
{

    protected function setValue(ImmutableDecimal $numerator, ImmutableDecimal $denominator)
    {

        return new ImmutableFraction($numerator, $denominator, $this->base);

    }

    /**
     * @return bool
     */
    public function isComplex(): bool
    {
        return false;
    }

}