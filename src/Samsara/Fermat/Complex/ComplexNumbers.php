<?php

namespace Samsara\Fermat\Complex;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;

class ComplexNumbers
{

    public const IMMUTABLE_COMPLEX = ImmutableComplexNumber::class;
    public const MUTABLE_COMPLEX = MutableComplexNumber::class;

    /**
     * @param $type
     * @param $value
     *
     * @return ComplexNumber
     * @throws IntegrityConstraint
     */
    public static function make($type, $value)
    {

        if (is_string($value)) {
            if ($type === self::MUTABLE_COMPLEX) {
                return MutableComplexNumber::makeFromString($value);
            }

            return ImmutableComplexNumber::makeFromString($value);
        }

        if ($value instanceof NumberCollectionInterface && $value->count() === 2) {
            if ($type === self::MUTABLE_COMPLEX) {
                return new MutableComplexNumber($value->get(0), $value->get(1));
            }

            return new ImmutableComplexNumber($value->get(0), $value->get(1));
        }

        if (is_array($value) && count($value) === 2) {
            if ($type === self::MUTABLE_COMPLEX) {
                return new MutableComplexNumber($value[0], $value[1]);
            }

            return new ImmutableComplexNumber($value[0], $value[1]);
        }

    }

}