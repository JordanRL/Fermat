<?php

namespace Samsara\Fermat;

use Samsara\Fermat\Values\ImmutableNumber;
use Samsara\Fermat\Values\FluentNumber;
use Samsara\Fermat\Values\Base\NumberInterface;

class Numbers
{

    const MUTABLE = FluentNumber::class;
    const IMMUTABLE = ImmutableNumber::class;

    /**
     * @param $type
     * @param $value
     * @param null $precision
     * @param int $base
     * @return NumberInterface
     */
    public static function make($type, $value, $precision = null, $base = 10)
    {

        $reflector = new \ReflectionClass($type);

        if ($reflector->implementsInterface(NumberInterface::class)) {
            return $reflector->newInstance([
                trim($value),
                $precision,
                $base
            ]);
        }

        throw new \InvalidArgumentException('The $type argument was not an implementation of NumberInterface.');
    }

    /**
     * @param $type
     * @param $value
     * @param null $precision
     * @param int $base
     * @return NumberInterface
     */
    public static function makeFromBase10($type, $value, $precision = null, $base = 10)
    {
        /**
         * @var ImmutableNumber|FluentNumber
         */
        $number = self::make($type, $value, $precision, 10);

        return $number->convertToBase($base);
    }

    /**
     * @param $type
     * @param $input
     * @param null $precision
     * @param int $base
     * @return NumberInterface
     */
    public static function makeOrDont($type, $input, $precision = null, $base = 10)
    {

        if (is_numeric($input)) {
            return self::make($type, $input, $precision, $base);
        } elseif (is_object($input)) {
            $reflector = new \ReflectionClass($input);

            if ($reflector->implementsInterface('Samsara\Fermat\Values\Base\NumberInterface')) {
                return $input;
            }
        }

        throw new \InvalidArgumentException('The $input argument was not numeric or an implementation of NumberInterface.');

    }

}