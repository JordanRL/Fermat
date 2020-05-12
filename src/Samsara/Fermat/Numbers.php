<?php

namespace Samsara\Fermat;

use ReflectionException;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Fraction;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Values\MutableFraction;
use Samsara\Fermat\Values\MutableDecimal;

class Numbers
{

    public const MUTABLE = MutableDecimal::class;
    public const IMMUTABLE = ImmutableDecimal::class;
    public const MUTABLE_FRACTION = MutableFraction::class;
    public const IMMUTABLE_FRACTION = ImmutableFraction::class;
    public const CARTESIAN_COORDINATE = CartesianCoordinate::class;
    /* 105 digits after decimal, which is going to be overkill in almost all places */
    public const PI = '3.1415926535897932384626433832795028841971693993751058209749445923078164062862089986280348253421170679';
    /* Tau (2pi) to 100 digits */
    public const TAU = '6.283185307179586476925286766559005768394338798750211641949889184615632812572417997256069650684234136';
    /* Euler's Number to 100 digits */
    public const E = '2.718281828459045235360287471352662497757247093699959574966967627724076630353547594571382178525166427';
    /* Golden Ratio to 100 digits */
    public const GOLDEN_RATIO = '1.618033988749894848204586834365638117720309179805762862135448622705260462818902449707207204189391137';
    /* Natural log of 10 to 100 digits */
    public const LN_10 = '2.302585092994045684017991454684364207601101488628772976033327900967572609677352480235997205089598298';
    /* The value of i^i */
    public const I_POW_I = '0.2078795763507619085469556198349787700338778416317696080751358830554198772854821397886002778654260353';

    /**
     * @param $type
     * @param $value
     * @param int|null $precision
     * @param int $base
     *
     * @return ImmutableDecimal|MutableDecimal|ImmutableFraction|MutableFraction|CartesianCoordinate|NumberInterface|FractionInterface|CoordinateInterface
     * @throws IntegrityConstraint
     * @throws ReflectionException
     */
    public static function make($type, $value, $precision = null, $base = 10)
    {

        if (is_object($type)) {
            $type = get_class($type);
        }

        if ($type === static::IMMUTABLE) {
            return new ImmutableDecimal(trim($value), $precision, $base);
        }

        if ($type === static::MUTABLE) {
            return new MutableDecimal(trim($value), $precision, $base);
        }

        if ($type === static::IMMUTABLE_FRACTION) {
            return self::makeFractionFromString($type, $value, $base);
        }

        if ($type === static::MUTABLE_FRACTION) {
            return self::makeFractionFromString($type, $value, $base);
        }

        if ($type === static::CARTESIAN_COORDINATE && is_array($value)) {
            $x = $value[0];
            $y = $value[1] ?? null;
            $z = $value[2] ?? null;

            return new CartesianCoordinate($x, $y, $z);
        }

        $reflector = new \ReflectionClass($type);

        if ($reflector->implementsInterface(FractionInterface::class) && $reflector->isSubclassOf(Fraction::class)) {
            return self::makeFractionFromString($reflector->getName(), $value, $base);
        }

        if ($reflector->implementsInterface(CoordinateInterface::class) && is_array($value)) {
            /** @var CoordinateInterface $customCoordinate */
            $customCoordinate = $reflector->newInstance([
                $value
            ]);
            return $customCoordinate;
        }

        if ($reflector->implementsInterface(NumberInterface::class)) {
            /** @var NumberInterface $customNumber */
            $customNumber = $reflector->newInstance([
                trim($value),
                $precision,
                $base
            ]);
            return $customNumber;
        }

        if ($reflector->implementsInterface(CoordinateInterface::class) && !is_array($value)) {
            throw new IntegrityConstraint(
                'The $value for a CoordinateInterface must be an array',
                'Provide an array for the $value',
                'A CoordinateInterface expects the value to be an array of axes and values'
            );
        }

        throw new IntegrityConstraint(
            '$type must be an implementation of NumberInterface or CoordinateInterface',
            'Provide a type that implements NumberInterface or CoordinateInterface (the Numbers class contains constants for the built in ones)',
            'The $type argument was not an implementation of NumberInterface or CoordinateInterface'
        );
    }

    /**
     * @param $type
     * @param $value
     * @param int|null $precision
     * @param int $base
     *
     * @return NumberInterface
     * @throws IntegrityConstraint
     * @throws ReflectionException
     */
    public static function makeFromBase10($type, $value, $precision = null, $base = 10): NumberInterface
    {
        /**
         * @var ImmutableDecimal|MutableDecimal
         */
        $number = self::make($type, $value, $precision, 10);

        return $number->convertToBase($base);
    }

    /**
     * @param string|object $type
     * @param int|float|string|array|NumberInterface|DecimalInterface|FractionInterface $value
     * @param int|null $precision
     * @param int $base
     *
     * @return ImmutableDecimal|MutableDecimal|NumberInterface|ImmutableDecimal[]|MutableDecimal[]|NumberInterface[]
     * @throws IntegrityConstraint|ReflectionException
     */
    public static function makeOrDont($type, $value, $precision = null, $base = 10)
    {

        if (is_object($value)) {
            if ($value instanceof $type) {
                return $value;
            }

            if ($value instanceof NumberInterface) {
                return static::make($type, $value->getValue(), $precision, $base);
            }
        } elseif (is_array($value)) {
            $newInput = [];

            foreach ($value as $key => $item) {
                $newInput[$key] = static::makeOrDont($type, $item, $precision, $base);
            }

            return $newInput;
        } elseif (is_string($value)) {
            $isImaginary = strpos($value, 'i') !== false;

            if (is_numeric($value) || $isImaginary) {
                return static::make($type, $value, $precision, $base);
            }
        }

        throw new IntegrityConstraint(
            '$input must be an int, float, numeric string, or an implementation of NumberInterface',
            'Provide any of the MANY valid inputs',
            'The $input argument was not numeric or an implementation of NumberInterface. Given value: '.$value
        );

    }

    /**
     * @param     $type
     * @param     $value
     * @param int $base
     *
     * @return FractionInterface|ImmutableFraction|MutableFraction
     * @throws IntegrityConstraint|ReflectionException
     */
    public static function makeFractionFromString($type, $value, int $base = 10): FractionInterface
    {
        $parts = explode('/', $value);

        if (count($parts) > 2) {
            throw new IntegrityConstraint(
                'Only one division symbol (/) can be used',
                'Change the calling code to not provide more than one division symbol',
                'makeFractionFromString needs either one or zero division symbols in the $value argument; '.$value.' given'
            );
        }

        /** @var ImmutableDecimal $numerator */
        $numerator = self::make(self::IMMUTABLE, trim(ltrim($parts[0])))->round();
        /** @var ImmutableDecimal $denominator */
        $denominator = isset($parts[1]) ? self::make(self::IMMUTABLE, trim(ltrim($parts[1])))->round() : self::makeOne();

        if ($type === self::IMMUTABLE_FRACTION) {
            return new ImmutableFraction($numerator, $denominator, $base);
        }

        if ($type === self::MUTABLE_FRACTION) {
            return new MutableFraction($numerator, $denominator, $base);
        }

        $reflector = new \ReflectionClass($type);

        if ($reflector->implementsInterface(FractionInterface::class) && $reflector->isSubclassOf(Fraction::class)) {
            /** @var FractionInterface|Fraction $customFraction */
            $customFraction = $reflector->newInstance([
                $numerator,
                $denominator,
                $base
            ]);
            return $customFraction;
        }

        throw new IntegrityConstraint(
            'Type must be an implementation of FractionInterface',
            'Alter to calling code to use the correct type',
            'makeFractionFromString can only make objects which implement the FractionInterface; '.$type.' given'
        );
    }

    /**
     * @param int|null $precision
     *
     * @return NumberInterface
     * @throws ReflectionException
     * @throws IntegrityConstraint
     */
    public static function makePi(int $precision = null)
    {

        if (!is_null($precision)) {
            if ($precision > 100 || $precision < 1) {
                throw new IntegrityConstraint(
                    '$precision must be between 1 and 100 inclusive',
                    'Provide a precision within range',
                    'The PI constant cannot have a precision higher than the constant stored (100)'
                );
            }

            return self::make(self::IMMUTABLE, self::PI, $precision)->truncateToPrecision($precision);
        }

        return self::make(self::IMMUTABLE, self::PI, 100);

    }

    /**
     * @param int|null $precision
     *
     * @return NumberInterface
     * @throws ReflectionException
     * @throws IntegrityConstraint
     */
    public static function makeTau($precision = null)
    {
        if (!is_null($precision)) {
            if ($precision > 100 || $precision < 1) {
                throw new IntegrityConstraint(
                    '$precision must be between 1 and 100 inclusive',
                    'Provide a precision within range',
                    'The TAU constant cannot have a precision higher than the constant stored (100)'
                );
            }

            return self::make(self::IMMUTABLE, self::TAU, $precision)->truncateToPrecision($precision);
        }

        return self::make(self::IMMUTABLE, self::TAU, 100);
    }

    /**
     * @param int|null $precision
     *
     * @return NumberInterface
     * @throws IntegrityConstraint
     * @throws ReflectionException
     */
    public static function make2Pi($precision = null)
    {
        return self::makeTau($precision);
    }

    /**
     * @param int|null $precision
     *
     * @return NumberInterface
     * @throws ReflectionException
     * @throws IntegrityConstraint
     */
    public static function makeE($precision = null)
    {

        if (!is_null($precision)) {
            if ($precision > 100 || $precision < 1) {
                throw new IntegrityConstraint(
                    '$precision must be between 1 and 100 inclusive',
                    'Provide a precision within range',
                    'The E constant cannot have a precision higher than the constant stored (100)'
                );
            }

            return self::make(self::IMMUTABLE, self::E, $precision)->truncateToPrecision($precision);
        }

        return self::make(self::IMMUTABLE, self::E, 100);

    }

    /**
     * @param int|null $precision
     *
     * @return NumberInterface
     * @throws ReflectionException
     * @throws IntegrityConstraint
     */
    public static function makeGoldenRatio($precision = null)
    {

        if (!is_null($precision)) {
            if ($precision > 100 || $precision < 1) {
                throw new IntegrityConstraint(
                    '$precision must be between 1 and 100 inclusive',
                    'Provide a precision within range',
                    'The Golden Ratio constant cannot have a precision higher than the constant stored (100)'
                );
            }

            return self::make(self::IMMUTABLE, self::GOLDEN_RATIO, $precision)->truncateToPrecision($precision);
        }

        return self::make(self::IMMUTABLE, self::GOLDEN_RATIO, 100);

    }

    /**
     * @param int|null $precision
     *
     * @return NumberInterface
     * @throws ReflectionException
     * @throws IntegrityConstraint
     */
    public static function makeNaturalLog10($precision = null)
    {

        if (!is_null($precision)) {
            if ($precision > 100 || $precision < 1) {
                throw new IntegrityConstraint(
                    '$precision must be between 1 and 100 inclusive',
                    'Provide a precision within range',
                    'The natural log of 10 constant cannot have a precision higher than the constant stored (100)'
                );
            }

            return self::make(self::IMMUTABLE, self::LN_10, $precision)->truncateToPrecision($precision);
        }

        return self::make(self::IMMUTABLE, self::LN_10, 100);

    }

    /**
     * @param int|null $precision
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws ReflectionException
     */
    public static function makeOne($precision = null)
    {
        return self::make(self::IMMUTABLE, 1, $precision);
    }

    /**
     * @param int|null $precision
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws ReflectionException
     */
    public static function makeZero($precision = null)
    {
        return self::make(self::IMMUTABLE, 0, $precision);
    }

}