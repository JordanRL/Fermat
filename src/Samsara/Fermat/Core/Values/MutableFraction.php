<?php

namespace Samsara\Fermat\Core\Values;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Types\Fraction;

/**
 * @package Samsara\Fermat\Core
 */
class MutableFraction extends Fraction
{

    /**
     * @param ImmutableDecimal $numerator
     * @param ImmutableDecimal $denominator
     * @return static
     * @throws IntegrityConstraint
     */
    protected function setValue(ImmutableDecimal $numerator, ImmutableDecimal $denominator): static
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