<?php

namespace Samsara\Fermat\Complex\Values;

use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 * @package Samsara\Fermat\Complex
 */
class ImmutableComplexNumber extends ComplexNumber
{

    protected function setValue(
        ImmutableDecimal|ImmutableFraction $realPart,
        ImmutableDecimal|ImmutableFraction $imaginaryPart,
        ?int                               $scale = null
    ): static|ImmutableComplexNumber
    {
        $scale = $scale ?? $this->getScale();

        return new ImmutableComplexNumber($realPart, $imaginaryPart, $scale);
    }

}