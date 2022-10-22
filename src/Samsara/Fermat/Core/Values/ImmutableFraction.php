<?php

namespace Samsara\Fermat\Core\Values;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Types\Fraction;

/**
 * @package Samsara\Fermat\Core
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