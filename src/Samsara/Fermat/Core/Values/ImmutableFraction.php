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
     * @param ImmutableDecimal $numerator
     * @param ImmutableDecimal $denominator
     *
     * @return static
     * @throws IntegrityConstraint
     */
    protected function setValue(ImmutableDecimal $numerator, ImmutableDecimal $denominator): static
    {

        return new static($numerator, $denominator, $this->base);

    }

}