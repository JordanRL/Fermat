<?php

namespace Samsara\Fermat\Complex\Values;

use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Complex\Types\ComplexNumber;

class ImmutableComplexNumber extends ComplexNumber
{

    protected function setValue(SimpleNumberInterface $realPart, SimpleNumberInterface $imaginaryPart): ImmutableComplexNumber
    {
        return new ImmutableComplexNumber($realPart, $imaginaryPart);
    }

}