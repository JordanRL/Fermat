<?php

namespace Samsara\Fermat\Core;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Provider\ConstantProvider;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 * This class contains useful factory methods to create various numbers, verify the
 * class of a given number, and generally handle all of the formatting necessary to
 * satisfy the various constructors of valid value objects.
 *
 * @package Samsara\Fermat\Core
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
    public const TAU = '6.2831853071795864769252867665590057683943387987502116419498891846156328125724179972560696506842341359';
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
     * @param mixed         $type   An instance of FQCN for any Fermat value class.
     * @param mixed         $value  Any value which is valid for the constructor which will be called.
     * @param int|null      $scale  The scale setting the created instance should have.
     * @param NumberBase    $base   The base to create the number in. Note, this is not the same as the base of $value, which is always base-10
     *
     * @return ImmutableDecimal|MutableDecimal|ImmutableFraction|MutableFraction|Fraction|Decimal
     * @throws IntegrityConstraint
     */
    public static function make(mixed $type, mixed $value, ?int $scale = null, NumberBase $base = NumberBase::Ten)
    {

        if (is_object($type)) {
            $type = get_class($type);
        }

        if ($type === static::IMMUTABLE) {
            return new ImmutableDecimal(trim($value), $scale, $base, true);
        }

        if ($type === static::MUTABLE) {
            return new MutableDecimal(trim($value), $scale, $base, true);
        }

        if ($type === static::IMMUTABLE_FRACTION) {
            return self::makeFractionFromString($type, $value, $base);
        }

        if ($type === static::MUTABLE_FRACTION) {
            return self::makeFractionFromString($type, $value, $base);
        }

        throw new IntegrityConstraint(
            '$type must be an implemented concrete class that is supported',
            'Provide a type that Decimal',
            'The $type argument was not an instance of Decimal'
        );
    }

    /**
     * @param $type
     * @param $value
     * @param int|null $scale
     * @param NumberBase $base
     *
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public static function makeFromBase10($type, $value, ?int $scale = null, NumberBase $base = NumberBase::Ten): Decimal
    {
        /**
         * @var ImmutableDecimal|MutableDecimal $number
         */
        $number = self::make($type, $value, $scale);

        return $number->setBase($base);
    }

    /**
     * @param string|object $type
     * @param int|float|string|array|Decimal|Fraction $value
     * @param int|null $scale
     * @param NumberBase $base
     *
     * @return ImmutableDecimal|MutableDecimal|Decimal|ImmutableDecimal[]|MutableDecimal[]|Decimal[]
     * @throws IntegrityConstraint
     */
    public static function makeOrDont(string|object $type, mixed $value, ?int $scale = null, NumberBase $base = NumberBase::Ten)
    {

        if (is_object($value)) {
            if ($value instanceof $type) {
                return $value;
            }

            if ($value instanceof Number) {
                return static::make($type, $value->getValue(NumberBase::Ten), $scale, $base);
            }
        } elseif (is_array($value)) {
            $newInput = [];

            foreach ($value as $key => $item) {
                $newInput[$key] = static::makeOrDont($type, $item, $scale, $base);
            }

            return $newInput;
        } elseif (is_string($value) || is_int($value) || is_float($value)) {
            $isImaginary = str_contains($value, 'i');

            if (is_numeric($value) || $isImaginary) {
                return static::make($type, $value, $scale, $base);
            }
        }

        throw new IntegrityConstraint(
            '$input must be an int, float, numeric string, or an implementation of Decimal',
            'Provide any of the MANY valid inputs',
            'The $input argument was not numeric or an implementation of Decimal. Given value: '.$value
        );

    }

    /**
     * @param string $type
     * @param string $value
     * @param NumberBase $base
     *
     * @return Fraction
     * @throws IntegrityConstraint
     */
    public static function makeFractionFromString(string $type, string $value, NumberBase $base = NumberBase::Ten): Fraction
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
        $numerator = self::make(self::IMMUTABLE, trim($parts[0]));
        /** @var ImmutableDecimal $denominator */
        $denominator = isset($parts[1]) ? self::make(self::IMMUTABLE, trim($parts[1])) : self::makeOne();

        if ($type === self::IMMUTABLE_FRACTION) {
            return new ImmutableFraction($numerator, $denominator, $base);
        }

        if ($type === self::MUTABLE_FRACTION) {
            return new MutableFraction($numerator, $denominator, $base);
        }

        throw new IntegrityConstraint(
            'Type must be an implementation of Fraction',
            'Alter to calling code to use the correct type',
            'makeFractionFromString can only make objects which implement the Fraction; '.$type.' given'
        );
    }

    /**
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function makePi(int $scale = null): ImmutableDecimal
    {
        return self::makeConstant(self::PI, $scale);
    }

    /**
     * @param null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function makeTau($scale = null): ImmutableDecimal
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
                $pi = self::make(self::IMMUTABLE, ConstantProvider::makePi($scale+2), $scale + 2);
                /** @var ImmutableDecimal */
                return $pi->multiply(2)->truncateToScale($scale);
            }

            return self::make(self::IMMUTABLE, self::TAU, $scale+1)->truncateToScale($scale);
        }

        return self::make(self::IMMUTABLE, self::TAU, 100);
    }

    /**
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function make2Pi(int $scale = null): ImmutableDecimal
    {
        return self::makeTau($scale);
    }

    /**
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function makeE(int $scale = null): ImmutableDecimal
    {
        return self::makeConstant(self::E, $scale);
    }

    /**
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function makeGoldenRatio(?int $scale = null): ImmutableDecimal
    {

        return self::makeConstant(self::GOLDEN_RATIO, $scale);

    }

    /**
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function makeNaturalLog10(?int $scale = null): ImmutableDecimal
    {
        return self::makeConstant(self::LN_10, $scale);
    }

    /**
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function makeNaturalLog2(?int $scale = null): ImmutableDecimal
    {
        return self::makeConstant(self::LN_2, $scale);
    }

    /**
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function makeOne(?int $scale = null): ImmutableDecimal
    {
        return self::make(self::IMMUTABLE, 1, $scale);
    }

    /**
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public static function makeZero(?int $scale = null): ImmutableDecimal
    {
        return self::make(self::IMMUTABLE, 0, $scale);
    }

    private static function makeConstant(string $constant, ?int $scale): ImmutableDecimal
    {

        if (!is_null($scale)) {
            if ($scale < 1) {
                throw new IntegrityConstraint(
                    'Scale must be at least 1',
                    'Provide a scale within range',
                    'Cannot create a constant with a scale less than 1'
                );
            }

            if ($scale > 100) {
                return self::make(
                    self::IMMUTABLE,
                    match ($constant) {
                        self::LN_2 => ConstantProvider::makeLn2($scale),
                        self::LN_10 => ConstantProvider::makeLn10($scale),
                        self::GOLDEN_RATIO => ConstantProvider::makeGoldenRatio($scale),
                        self::E => ConstantProvider::makeE($scale),
                        self::PI => ConstantProvider::makePi($scale),
                    },
                    $scale
                );
            }

            return self::make(
                self::IMMUTABLE,
                $constant,
                $scale+1
            )->truncateToScale($scale);
        }

        return self::make(
            self::IMMUTABLE,
            $constant,
            100
        );

    }

}