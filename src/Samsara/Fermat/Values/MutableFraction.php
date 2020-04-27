<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Fraction;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Types\Base\NumberInterface;

class MutableFraction extends Fraction implements NumberInterface, FractionInterface
{

    protected function setValue(ImmutableNumber $numerator, ImmutableNumber $denominator)
    {

        $this->numerator = $numerator;
        $this->denominator = $denominator;

        return $this;

    }

    /**
     * @return bool
     */
    public function isComplex(): bool
    {
        return false;
    }

}