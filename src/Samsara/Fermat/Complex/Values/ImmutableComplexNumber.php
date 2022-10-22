<?php

namespace Samsara\Fermat\Complex\Values;

use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;

class ImmutableComplexNumber extends ComplexNumber
{

    protected function setValue(
        ImmutableDecimal|ImmutableFraction $realPart,
        ImmutableDecimal|ImmutableFraction $imaginaryPart,
        ?int $scale = null
    ): static|ImmutableComplexNumber
    {
        $scale = $scale ?? $this->getScale();

        return new ImmutableComplexNumber($realPart, $imaginaryPart, $scale);
    }

}