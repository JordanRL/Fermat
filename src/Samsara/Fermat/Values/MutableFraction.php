<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Fraction;
use Samsara\Fermat\Values\Base\FractionInterface;
use Samsara\Fermat\Values\Base\NumberInterface;

class MutableFraction extends Fraction implements NumberInterface, FractionInterface
{

    protected function setValue(ImmutableNumber $numerator, ImmutableNumber $denominator)
    {

        $this->numerator = $numerator;
        $this->denominator = $denominator;

        return $this;

    }

}