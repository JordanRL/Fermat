<?php

namespace Samsara\Fermat\Complex\Values;

use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Coordinates\Values\CartesianCoordinate;

class MutableComplexNumber extends ComplexNumber
{

    protected function setValue(SimpleNumberInterface $realPart, SimpleNumberInterface $imaginaryPart)
    {
        $this->realPart = $realPart;
        $this->imaginaryPart = $imaginaryPart;

        $this->cachedCartesian = new CartesianCoordinate($realPart, $imaginaryPart);

        $polar = $this->cachedCartesian->asPolar();

        $this->values->set(0, $polar->getDistanceFromOrigin());
        $this->values->set(1, $polar->getPolarAngle());

        return $this;
    }

}