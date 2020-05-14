<?php

namespace Samsara\Fermat;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Values\ImmutableComplexNumber;
use Samsara\Fermat\Values\MutableComplexNumber;

class ComplexNumbers
{

    public const IMMUTABLE = ImmutableComplexNumber::class;
    public const MUTABLE = MutableComplexNumber::class;

    /**
     * @param $type
     * @param $value
     *
     * @return Types\ComplexNumber
     * @throws IntegrityConstraint
     */
    public static function make($type, $value)
    {

        if (is_string($value)) {
            if ($type === self::MUTABLE) {
                return MutableComplexNumber::makeFromString($value);
            }

            return ImmutableComplexNumber::makeFromString($value);
        }

    }

}