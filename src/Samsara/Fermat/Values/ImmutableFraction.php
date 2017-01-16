<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Fraction;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Types\Base\NumberInterface;

class ImmutableFraction extends Fraction implements NumberInterface, FractionInterface
{

    protected function setValue(ImmutableNumber $numerator, ImmutableNumber $denominator)
    {

        return new ImmutableFraction($numerator, $denominator, $this->base);

    }

}