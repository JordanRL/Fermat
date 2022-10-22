<?php

namespace Samsara\Fermat\Complex\Values;

use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Coordinates\Values\CartesianCoordinate;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 *@package Samsara\Fermat\Complex
 */
class MutableComplexNumber extends ComplexNumber
{

    protected function setValue(
        ImmutableDecimal|ImmutableFraction $realPart,
        ImmutableDecimal|ImmutableFraction $imaginaryPart,
        ?int $scale = null
    ): static|MutableComplexNumber
    {
        $scale = $scale ?? $this->getScale();

        $this->scale = $scale;
        $this->realPart = $realPart;
        $this->imaginaryPart = $imaginaryPart;

        $this->cachedCartesian = new CartesianCoordinate($realPart, $imaginaryPart);

        $polar = $this->cachedCartesian->asPolar();

        $this->cachedPolar = $polar;

        return $this;
    }

}