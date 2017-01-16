<?php

namespace Samsara\Fermat;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Types\Base\CoordinateInterface;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Types\Fraction;
use Samsara\Fermat\Values\CartesianCoordinate;
use Samsara\Fermat\Values\Currency;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableNumber;
use Samsara\Fermat\Values\MutableFraction;
use Samsara\Fermat\Values\MutableNumber;
use Samsara\Fermat\Types\Base\NumberInterface;

class Numbers
{

    const MUTABLE = MutableNumber::class;
    const IMMUTABLE = ImmutableNumber::class;
    const MUTABLE_FRACTION = MutableFraction::class;
    const IMMUTABLE_FRACTION = ImmutableFraction::class;
    const CARTESIAN_COORDINATE = CartesianCoordinate::class;
    const CURRENCY = Currency::class;
    /* 105 digits after decimal, which is going to be overkill in almost all places */
    const PI = '3.1415926535897932384626433832795028841971693993751058209749445923078164062862089986280348253421170679';
    /* Tau (2pi) to 100 digits */
    const TAU = '6.283185307179586476925286766559005768394338798750211641949889184615632812572417997256069650684234136';
    /* Euler's Number to 100 digits */
    const E = '2.718281828459045235360287471352662497757247093699959574966967627724076630353547594571382178525166427';
    /* Golden Ratio to 100 digits */
    const GOLDEN_RATIO = '1.618033988749894848204586834365638117720309179805762862135448622705260462818902449707207204189391137';
    /* Natural log of 10 to 100 digits */
    const LN_10 = '2.302585092994045684017991454684364207601101488628772976033327900967572609677352480235997205089598298';

    /**
     * @param $type
     * @param $value
     * @param int|null $precision
     * @param int $base
     *
     * @throws IntegrityConstraint
     * @return ImmutableNumber|MutableNumber|ImmutableFraction|MutableFraction|Currency|CartesianCoordinate|NumberInterface|FractionInterface|CoordinateInterface
     */
    public static function make($type, $value, $precision = null, $base = 10)
    {

        if (is_object($type)) {
            $type = get_class($type);
        }

        if ($type == static::IMMUTABLE) {
            return new ImmutableNumber(trim($value), $precision, $base);
        } elseif ($type == static::MUTABLE) {
            return new MutableNumber(trim($value), $precision, $base);
        } elseif ($type == static::IMMUTABLE_FRACTION) {
            return self::makeFractionFromString($value, $type)->convertToBase($base);
        } elseif ($type == static::MUTABLE_FRACTION) {
            return self::makeFractionFromString($value, $type)->convertToBase($base);
        } elseif ($type == static::CURRENCY) {
            return new Currency(trim($value), Currency::DOLLAR, $precision, $base);
        } elseif ($type == static::CARTESIAN_COORDINATE && is_array($value)) {
            return new CartesianCoordinate($value);
        } else {
            $reflector = new \ReflectionClass($type);

            if ($reflector->implementsInterface(FractionInterface::class) && $reflector->isSubclassOf(Fraction::class)) {
                return Numbers::makeFractionFromString($value, $reflector->getName(), $base);
            }

            if ($reflector->implementsInterface(CoordinateInterface::class) && is_array($value)) {
                /** @var CoordinateInterface $customCoordinate */
                $customCoordinate = $reflector->newInstance([
                    $value
                ]);
                return $customCoordinate;
            }

            if ($reflector->implementsInterface(CoordinateInterface::class) && !is_array($value)) {
                throw new IntegrityConstraint(
                    'The $value for a CoordinateInterface must be an array',
                    'Provide an array for the $value',
                    'A CoordinateInterface expects the value to be an array of axes and values'
                );
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
        }

        throw new IntegrityConstraint(
            '$type must be an implementation of NumberInterface',
            'Provide a type that implements NumberInterface (the Numbers class contains constants for the built in ones)',
            'The $type argument was not an implementation of NumberInterface'
        );
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
     * @param int|float|string|NumberInterface|DecimalInterface|FractionInterface $value
     * @param int|null $precision
     * @param int $base
     *
     * @throws IntegrityConstraint
     * @return ImmutableNumber|MutableNumber|NumberInterface|ImmutableNumber[]|MutableNumber[]|NumberInterface[]
     */
    public static function makeOrDont($type, $value, $precision = null, $base = 10)
    {

        if (is_object($value)) {
            $reflector = new \ReflectionClass($value);

            if ($value instanceof $type) {
                return $value;
            }

            if ($reflector->implementsInterface(NumberInterface::class)) {
                return static::make($type, $value->getValue(), $precision, $base);
            }
        } elseif (is_numeric($value)) {
            return static::make($type, $value, $precision, $base);
        } elseif (is_array($value)) {
            $newInput = [];
            
            foreach ($value as $key => $item) {
                $newInput[$key] = static::makeOrDont($type, $item, $precision, $base);
            }

            return $newInput;
        }

        throw new IntegrityConstraint(
            '$input must be an int, float, numeric string, or an implementation of NumberInterface',
            'Provide any of the MANY valid inputs',
            'The $input argument was not numeric or an implementation of NumberInterface.'
        );

    }

    /**
     * @param $value
     * @param $type
     *
     * @return ImmutableFraction|MutableFraction|FractionInterface
     * @throws IntegrityConstraint
     */
    public static function makeFractionFromString($value, $type = self::IMMUTABLE_FRACTION, $base = 10)
    {
        $parts = explode('/', $value);

        if (count($parts) > 2) {
            throw new IntegrityConstraint(
                'Only one division symbol (/) can be used',
                'Change the calling code to not provide more than one division symbol',
                'makeFractionFromString needs either one or zero division symbols in the $value argument; '.$value.' given'
            );
        }

        /** @var ImmutableNumber $numerator */
        $numerator = Numbers::make(Numbers::IMMUTABLE, trim(ltrim($parts[0])))->round();
        /** @var ImmutableNumber $denominator */
        $denominator = isset($parts[1]) ? Numbers::make(Numbers::IMMUTABLE, trim(ltrim($parts[1])))->round() : Numbers::makeOne();

        if ($type == self::IMMUTABLE_FRACTION) {
            return new ImmutableFraction($numerator, $denominator, $base);
        } elseif ($type == self::MUTABLE_FRACTION) {
            return new MutableFraction($numerator, $denominator, $base);
        } else {
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
                'Type must be ImmutableFraction or MutableFraction',
                'Alter to calling code to use the correct type',
                'makeFractionFromString can only make objects of type ImmutableFraction or MutableFraction; '.$type.' given'
            );
        }
    }

    /**
     * @param int|null $precision
     *
     * @throws IntegrityConstraint
     * @return NumberInterface
     */
    public static function makePi($precision = null)
    {
        
        if (!is_null($precision) && ($precision > 100 || $precision < 1)) {
            throw new IntegrityConstraint(
                '$precision must be between 1 and 100 inclusive',
                'Provide a precision within range',
                'The PI constant cannot have a precision higher than the constant stored (100)'
            );
        }
        
        if (!is_null($precision)) {
            return self::make(self::IMMUTABLE, self::PI, $precision)->roundToPrecision($precision);
        } else {
            return self::make(self::IMMUTABLE, self::PI, 100);
        }
        
    }

    /**
     * @param int|null $precision
     *
     * @throws IntegrityConstraint
     * @return NumberInterface
     */
    public static function makeTau($precision = null)
    {
        if (!is_null($precision) && ($precision > 100 || $precision < 1)) {
            throw new IntegrityConstraint(
                '$precision must be between 1 and 100 inclusive',
                'Provide a precision within range',
                'The TAU constant cannot have a precision higher than the constant stored (100)'
            );
        }

        if (!is_null($precision)) {
            return self::make(self::IMMUTABLE, self::TAU, $precision)->roundToPrecision($precision);
        } else {
            return self::make(self::IMMUTABLE, self::TAU, 100);
        }
    }

    /**
     * @param int|null $precision
     *
     * @return NumberInterface
     */
    public static function make2Pi($precision = null)
    {
        return self::makeTau($precision);
    }

    /**
     * @param int|null $precision
     *
     * @throws IntegrityConstraint
     * @return NumberInterface
     */
    public static function makeE($precision = null)
    {

        if (!is_null($precision) && ($precision > 100 || $precision < 1)) {
            throw new IntegrityConstraint(
                '$precision must be between 1 and 100 inclusive',
                'Provide a precision within range',
                'The E constant cannot have a precision higher than the constant stored (100)'
            );
        }

        if (!is_null($precision)) {
            return self::make(self::IMMUTABLE, self::E, $precision)->roundToPrecision($precision);
        } else {
            return self::make(self::IMMUTABLE, self::E, 100);
        }

    }

    /**
     * @param int|null $precision
     *
     * @throws IntegrityConstraint
     * @return NumberInterface
     */
    public static function makeGoldenRatio($precision = null)
    {

        if (!is_null($precision) && ($precision > 100 || $precision < 1)) {
            throw new IntegrityConstraint(
                '$precision must be between 1 and 100 inclusive',
                'Provide a precision within range',
                'The Golden Ratio constant cannot have a precision higher than the constant stored (100)'
            );
        }

        if (!is_null($precision)) {
            return self::make(self::IMMUTABLE, self::GOLDEN_RATIO, $precision)->roundToPrecision($precision);
        } else {
            return self::make(self::IMMUTABLE, self::GOLDEN_RATIO, 100);
        }

    }

    /**
     * @param int|null $precision
     *
     * @throws IntegrityConstraint
     * @return NumberInterface
     */
    public static function makeNaturalLog10($precision = null)
    {

        if (!is_null($precision) && ($precision > 100 || $precision < 1)) {
            throw new IntegrityConstraint(
                '$precision must be between 1 and 100 inclusive',
                'Provide a precision within range',
                'The natural log of 10 constant cannot have a precision higher than the constant stored (100)'
            );
        }

        if (!is_null($precision)) {
            return self::make(self::IMMUTABLE, self::LN_10, $precision)->roundToPrecision($precision);
        } else {
            return self::make(self::IMMUTABLE, self::LN_10, 100);
        }

    }

    /**
     * @return ImmutableNumber
     */
    public static function makeOne($precision = null)
    {
        return self::make(self::IMMUTABLE, 1, $precision);
    }

    /**
     * @return ImmutableNumber
     */
    public static function makeZero($precision = null)
    {
        return self::make(self::IMMUTABLE, 0, $precision);
    }

}