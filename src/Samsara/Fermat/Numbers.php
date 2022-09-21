<?php

namespace Samsara\Fermat;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Provider\ConstantProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Values\MutableFraction;
use Samsara\Fermat\Values\MutableDecimal;

/**
 * This class contains useful factory methods to create various numbers, verify the
 * class of a given number, and generally handle all of the formatting necessary to
 * satisfy the various constructors of valid value objects.
 *
 * @package Samsara\Fermat
 */
class Numbers
{

    public const MUTABLE = MutableDecimal::class;
    public const IMMUTABLE = ImmutableDecimal::class;
    public const MUTABLE_FRACTION = MutableFraction::class;
    public const IMMUTABLE_FRACTION = ImmutableFraction::class;
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
    /* Natural log of 2 to 100 digits */
    public const LN_2 = '0.693147180559945309417232121458176568075500134360255254120680009493393621969694715605863326996418687';
    /* The value of i^i */
    public const I_POW_I = '0.2078795763507619085469556198349787700338778416317696080751358830554198772854821397886002778654260353';

    /**
     * This class will make and return an instance of a concrete value.
     *
     * The main reason for this class is that you can pass an unknown value instance as the
     * $type parameter and it will behave as if you passed the FQCN.
     *
     * @param mixed     $type   An instance of FQCN for any Fermat value class.
     * @param mixed     $value  Any value which is valid for the constructor which will be called.
     * @param int|null  $scale  The scale setting the created instance should have.
     * @param int       $base   The base to create the number in. Note, this is not the same as the base of $value, which is always base-10
     *
     * @return ImmutableDecimal|MutableDecimal|ImmutableFraction|MutableFraction|NumberInterface|FractionInterface
     * @throws IntegrityConstraint
     */
    public static function make(mixed $type, mixed $value, ?int $scale = null, int $base = 10)
    {

        if (is_object($type)) {
            $type = get_class($type);
        }

        if ($type === static::IMMUTABLE) {
            return new ImmutableDecimal(trim($value), $scale, $base);
        }

        if ($type === static::MUTABLE) {
            return new MutableDecimal(trim($value), $scale, $base);
        }

        if ($type === static::IMMUTABLE_FRACTION) {
            return self::makeFractionFromString($type, $value, $base);
        }

        if ($type === static::MUTABLE_FRACTION) {
            return self::makeFractionFromString($type, $value, $base);
        }

        throw new IntegrityConstraint(
            '$type must be an implemented concrete class that is supported',
            'Provide a type that implements NumberInterface or CoordinateInterface (the Numbers class contains constants for the built in ones)',
            'The $type argument was not an implementation of NumberInterface or CoordinateInterface'
        );
    }

    /**
     * @param $type
     * @param $value
     * @param int|null $scale
     * @param int $base
     *
     * @return NumberInterface
     * @throws IntegrityConstraint
     */
    public static function makeFromBase10($type, $value, ?int $scale = null, int $base = 10): NumberInterface
    {
        /**
         * @var ImmutableDecimal|MutableDecimal
         */
        $number = self::make($type, $value, $scale);

        return $number->convertToBase($base);
    }

    /**
     * @param string|object $type
     * @param int|float|string|array|NumberInterface|DecimalInterface|FractionInterface $value
     * @param int|null $scale
     * @param int $base
     *
     * @return ImmutableDecimal|MutableDecimal|NumberInterface|ImmutableDecimal[]|MutableDecimal[]|NumberInterface[]
     * @throws IntegrityConstraint
     */
    public static function makeOrDont($type, $value, $scale = null, $base = 10)
    {

        if (is_object($value)) {
            if ($value instanceof $type) {
                return $value;
            }

            if ($value instanceof NumberInterface) {
                return static::make($type, $value->getValue(), $scale, $base);
            }
        } elseif (is_array($value)) {
            $newInput = [];

            foreach ($value as $key => $item) {
                $newInput[$key] = static::makeOrDont($type, $item, $scale, $base);
            }

            return $newInput;
        } elseif (is_string($value) || is_int($value) || is_float($value)) {
            $isImaginary = strpos($value, 'i') !== false;

            if (is_numeric($value) || $isImaginary) {
                return static::make($type, $value, $scale, $base);
            }
        }

        throw new IntegrityConstraint(
            '$input must be an int, float, numeric string, or an implementation of NumberInterface',
            'Provide any of the MANY valid inputs',
            'The $input argument was not numeric or an implementation of NumberInterface. Given value: '.$value
        );

    }

    /**
     * @param string $type
     * @param string $value
     * @param int $base
     *
     * @return FractionInterface|ImmutableFraction|MutableFraction
     * @throws IntegrityConstraint
     */
    public static function makeFractionFromString(string $type, string $value, int $base = 10): FractionInterface
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
        $numerator = self::make(self::IMMUTABLE, trim(ltrim($parts[0])));
        /** @var ImmutableDecimal $denominator */
        $denominator = isset($parts[1]) ? self::make(self::IMMUTABLE, trim(ltrim($parts[1]))) : self::makeOne();

        if ($type === self::IMMUTABLE_FRACTION) {
            return new ImmutableFraction($numerator, $denominator, $base);
        }

        if ($type === self::MUTABLE_FRACTION) {
            return new MutableFraction($numerator, $denominator, $base);
        }

        throw new IntegrityConstraint(
            'Type must be an implementation of FractionInterface',
            'Alter to calling code to use the correct type',
            'makeFractionFromString can only make objects which implement the FractionInterface; '.$type.' given'
        );
    }

    /**
     * @param int|null $scale
     *
     * @return DecimalInterface
     * @throws IntegrityConstraint
     */
    public static function makePi(int $scale = null)
    {

        if (!is_null($scale)) {
            if ($scale < 1) {
                throw new IntegrityConstraint(
                    '$scale must be at least 1',
                    'Provide a scale within range',
                    'The pi constant cannot have a scale less than 1'
                );
            }

            if ($scale > 100) {
                return self::make(self::IMMUTABLE, ConstantProvider::makePi($scale), $scale);
            }

            return self::make(self::IMMUTABLE, self::PI, $scale)->truncateToScale($scale);
        }

        return self::make(self::IMMUTABLE, self::PI, 100);

    }

    /**
     * @param int|null $scale
     *
     * @return DecimalInterface
     * @throws IntegrityConstraint
     */
    public static function makeTau($scale = null)
    {
        if (!is_null($scale)) {
            if ($scale < 1) {
                throw new IntegrityConstraint(
                    '$scale must be at least 1',
                    'Provide a scale within range',
                    'The E constant cannot have a scale less than 1'
                );
            }

            if ($scale > 100) {
                $pi = self::make(self::IMMUTABLE, ConstantProvider::makePi($scale), $scale + 2);
                return $pi->multiply(2)->truncateToScale($scale);
            }

            return self::make(self::IMMUTABLE, self::TAU, $scale)->truncateToScale($scale);
        }

        return self::make(self::IMMUTABLE, self::TAU, 100);
    }

    /**
     * @param int|null $scale
     *
     * @return DecimalInterface
     * @throws IntegrityConstraint
     */
    public static function make2Pi($scale = null)
    {
        return self::makeTau($scale);
    }

    /**
     * @param int|null $scale
     *
     * @return DecimalInterface
     * @throws IntegrityConstraint
     */
    public static function makeE(int $scale = null)
    {

        if (!is_null($scale)) {
            if ($scale < 1) {
                throw new IntegrityConstraint(
                    '$scale must be at least 1',
                    'Provide a scale within range',
                    'The E constant cannot have a scale less than 1'
                );
            }

            if ($scale > 100) {
                return self::make(self::IMMUTABLE, ConstantProvider::makeE($scale), $scale);
            }

            return self::make(self::IMMUTABLE, self::E, $scale)->truncateToScale($scale);
        }

        return self::make(self::IMMUTABLE, self::E, 100);

    }

    /**
     * @param int|null $scale
     *
     * @return NumberInterface
     * @throws IntegrityConstraint
     */
    public static function makeGoldenRatio($scale = null)
    {

        if (!is_null($scale)) {
            if ($scale > 100 || $scale < 1) {
                throw new IntegrityConstraint(
                    '$scale must be between 1 and 100 inclusive',
                    'Provide a scale within range',
                    'The Golden Ratio constant cannot have a scale higher than the constant stored (100)'
                );
            }

            return self::make(self::IMMUTABLE, self::GOLDEN_RATIO, $scale)->truncateToScale($scale);
        }

        return self::make(self::IMMUTABLE, self::GOLDEN_RATIO, 100);

    }

    /**
     * @param int|null $scale
     *
     * @return NumberInterface
     * @throws IntegrityConstraint
     */
    public static function makeNaturalLog10($scale = null)
    {

        if (!is_null($scale)) {
            if ($scale > 100 || $scale < 1) {
                throw new IntegrityConstraint(
                    '$scale must be between 1 and 100 inclusive',
                    'Provide a scale within range',
                    'The natural log of 10 constant cannot have a scale higher than the constant stored (100)'
                );
            }

            return self::make(self::IMMUTABLE, self::LN_10, $scale)->truncateToScale($scale);
        }

        return self::make(self::IMMUTABLE, self::LN_10, 100);

    }

    /**
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function makeOne($scale = null)
    {
        return self::make(self::IMMUTABLE, 1, $scale);
    }

    /**
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function makeZero($scale = null)
    {
        return self::make(self::IMMUTABLE, 0, $scale);
    }

}