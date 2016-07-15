<?php

namespace Samsara\Fermat;

use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableNumber;
use Samsara\Fermat\Values\MutableNumber;
use Samsara\Fermat\Values\Base\NumberInterface;

class Numbers
{

    const MUTABLE = MutableNumber::class;
    const IMMUTABLE = ImmutableNumber::class;
    const FRACTION = ImmutableFraction::class;
    /* 105 digits after decimal, which is going to be overkill in almost all places */
    const PI = '3.141592653589793238462643383279502884197169399375105820974944592307816406286208998628034825342117067982148';
    /* Euler's Number to 100 digits */
    const E = '2.718281828459045235360287471352662497757247093699959574966967627724076630353547594571382178525166427';
    /* Golden Ratio to 100 digits */
    const GOLDEN_RATIO = '1.618033988749894848204586834365638117720309179805762862135448622705260462818902449707207204189391137';

    /**
     * @param $type
     * @param $value
     * @param int|null $precision
     * @param int $base
     * @return NumberInterface
     */
    public static function make($type, $value, $precision = null, $base = 10)
    {

        if ($type == self::IMMUTABLE) {
            return new ImmutableNumber(trim($value), $precision, $base);
        } elseif ($type == self::MUTABLE) {
            return New MutableNumber(trim($value), $precision, $base);
        } else {
            $reflector = new \ReflectionClass($type);

            if ($reflector->implementsInterface(NumberInterface::class)) {
                return $reflector->newInstance([
                    trim($value),
                    $precision,
                    $base
                ]);
            }
        }

        throw new \InvalidArgumentException('The $type argument was not an implementation of NumberInterface.');
    }

    /**
     * @param $type
     * @param $value
     * @param int|null $precision
     * @param int $base
     * @return NumberInterface
     */
    public static function makeFromBase10($type, $value, $precision = null, $base = 10)
    {
        /**
         * @var ImmutableNumber|MutableNumber
         */
        $number = self::make($type, $value, $precision, 10);

        return $number->convertToBase($base);
    }

    /**
     * @param $type
     * @param int|float|string|NumberInterface $input
     * @param int|null $precision
     * @param int $base
     * @return NumberInterface
     */
    public static function makeOrDont($type, $input, $precision = null, $base = 10)
    {

        if (is_numeric($input)) {
            return self::make($type, $input, $precision, $base);
        } elseif (is_object($input)) {
            $reflector = new \ReflectionClass($input);

            if ($input instanceof $type) {
                return $input;
            }

            if ($reflector->implementsInterface('Samsara\Fermat\Values\Base\NumberInterface')) {
                return self::make($type, $input->convertToBase(10)->getValue(), $precision, $base);
            }
        } elseif (is_array($input)) {
            $newInput = [];
            
            foreach ($input as $key => $value) {
                $newInput[$key] = self::makeOrDont($type, $value, $precision, $base);
            }

            return $newInput;
        }

        throw new \InvalidArgumentException('The $input argument was not numeric or an implementation of NumberInterface.');

    }

    /**
     * @param int|null $precision
     *
     * @return NumberInterface
     */
    public static function makePi($precision = null)
    {
        
        if (!is_null($precision) && ($precision > 105 || $precision < 1)) {
            throw new \InvalidArgumentException('The PI constant cannot have a precision higher than the constant stored (105).');
        }
        
        $pi = self::make(self::IMMUTABLE, self::PI);
        
        if (!is_null($precision)) {
            return $pi->roundToPrecision($precision);
        } else {
            return $pi;
        }
        
    }

    /**
     * @param int|null $precision
     *
     * @return NumberInterface
     */
    public static function makeE($precision = null)
    {

        if (!is_null($precision) && ($precision > 100 || $precision < 1)) {
            throw new \InvalidArgumentException('The E constant cannot have a precision higher than the constant stored (100).');
        }

        $e = self::make(self::IMMUTABLE, self::E);

        if (!is_null($e)) {
            return $e->roundToPrecision($precision);
        } else {
            return $e;
        }

    }

    /**
     * @param int|null $precision
     *
     * @return NumberInterface
     */
    public static function makeGoldenRatio($precision = null)
    {

        if (!is_null($precision) && ($precision > 100 || $precision < 1)) {
            throw new \InvalidArgumentException('The Golden Ratio constant cannot have a precision higher than the constant stored (100).');
        }

        $goldenRatio = self::make(self::IMMUTABLE, self::GOLDEN_RATIO);

        if (!is_null($precision)) {
            return $goldenRatio->roundToPrecision($precision);
        } else {
            return $goldenRatio;
        }

    }

    public static function makeOne()
    {
        return self::make(self::IMMUTABLE, 1);
    }

    public static function makeZero()
    {
        return self::make(self::IMMUTABLE, 0);
    }

}