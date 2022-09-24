<?php

namespace Samsara\Fermat\Values;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Types\Fraction;

/**
 *
 */
class ImmutableFraction extends Fraction
{

    /**
     * @throws IntegrityConstraint
     */
    protected function setValue(ImmutableDecimal $numerator, ImmutableDecimal $denominator): ImmutableFraction
    {

        return new ImmutableFraction($numerator, $denominator, $this->base);

    }

}