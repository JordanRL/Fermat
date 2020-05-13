<?php

namespace Samsara\Fermat;

use Samsara\Fermat\Values\ImmutableComplexNumber;
use Samsara\Fermat\Values\MutableComplexNumber;

class ComplexNumbers
{

    const IMMUTABLE = ImmutableComplexNumber::class;
    const MUTABLE = MutableComplexNumber::class;

    public static function make($type, $value)
    {

        if (is_string($value)) {
            if ($type == self::IMMUTABLE) {
                return ImmutableComplexNumber::makeFromString($value);
            } elseif ($type == self::MUTABLE) {
                return MutableComplexNumber::makeFromString($value);
            }
        }

    }

}