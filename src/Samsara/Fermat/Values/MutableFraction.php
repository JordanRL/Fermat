<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Fraction;

class MutableFraction extends Fraction
{

    protected function setValue(ImmutableDecimal $numerator, ImmutableDecimal $denominator)
    {

        if ($numerator->isImaginary() xor $denominator->isImaginary()) {
            $this->imaginary = true;
        } else {
            $this->imaginary = false;
        }

        $this->value = [
            $numerator,
            $denominator
        ];

        if ($numerator->isNegative() xor $denominator->isNegative()) {
            $this->sign = true;
        }

        return $this;

    }

}