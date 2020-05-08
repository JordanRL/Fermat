<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Types\ComplexNumber;

class ImmutableComplexNumber extends ComplexNumber
{

    protected function setValue(SimpleNumberInterface $realPart, SimpleNumberInterface $imaginaryPart): ImmutableComplexNumber
    {
        return new ImmutableComplexNumber($realPart, $imaginaryPart);
    }

}