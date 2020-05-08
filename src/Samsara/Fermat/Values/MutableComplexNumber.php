<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Types\ComplexNumber;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate;

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