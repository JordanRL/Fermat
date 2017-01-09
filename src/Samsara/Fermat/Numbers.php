<?php

namespace Samsara\Fermat;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
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
    const CURRENCY = Currency::class;
    /* 105 digits after decimal, which is going to be overkill in almost all places */
    const PI = '3.1415926535897932384626433832795028841971693993751058209749445923078164062862089986280348253421170679';
    /* Tau (2pi) to 100 digits */
    const TAU = '6.283185307179586476925286766559005768394338798750211641949889184615632812572417997256069650684234136';
    /* Euler's Number to 100 digits */
    const E = '2.718281828459045235360287471352662497757247093699959574966967627724076630353547594571382178525166427';
    /* Golden Ratio to 100 digits */
    const GOLDEN_RATIO = '1.618033988749894848204586834365638117720309179805762862135448622705260462818902449707207204189391137';

    /**
     * @param $type
     * @param $value
     * @param int|null $precision
     * @param int $base
     *
     * @throws IntegrityConstraint
     * @return ImmutableNumber|MutableNumber|ImmutableFraction|MutableFraction|NumberInterface
     */
    public static function make($type, $value, $precision = null, $base = 10)
    {

        if ($type == self::IMMUTABLE) {
            return new ImmutableNumber(trim($value), $precision, $base);
        } elseif ($type == self::MUTABLE) {
            return new MutableNumber(trim($value), $precision, $base);
        } elseif ($type == self::IMMUTABLE_FRACTION) {
            return self::makeFractionFromString($value, $type)->convertToBase($base);
        } elseif ($type == self::MUTABLE_FRACTION) {
            return self::makeFractionFromString($value, $type)->convertToBase($base);
        } else {
            $reflector = new \ReflectionClass($type);

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
     * @param int|float|string|NumberInterface $value
     * @param int|null $precision
     * @param int $base
     *
     * @throws IntegrityConstraint
     * @return ImmutableNumber|MutableNumber|NumberInterface|ImmutableNumber[]|MutableNumber[]|NumberInterface[]
     */
    public static function makeOrDont($type, $value, $precision = null, $base = 10)
    {

        if (is_numeric($value)) {
            return self::make($type, $value, $precision, $base);
        } elseif (is_object($value)) {
            $reflector = new \ReflectionClass($value);

            if ($value instanceof $type) {
                return $value;
            }

            if ($reflector->implementsInterface(NumberInterface::class)) {
                return self::make($type, $value->getValue(), $precision, $base);
            }
        } elseif (is_array($value)) {
            $newInput = [];
            
            foreach ($value as $key => $item) {
                $newInput[$key] = self::makeOrDont($type, $item, $precision, $base);
            }

            return $newInput;
        }

        throw new IntegrityConstraint(
            '$input must be an int, float, numeric string, or an implementation of NumberInterface',
            'Provide any of the MANY valid inputs',
            'The $input argument was not numeric or an implementation of NumberInterface.'
        );

    }

    public static function makeFractionFromString($value, $type = self::IMMUTABLE_FRACTION)
    {
        $parts = explode('/', $value);

        if (count($parts) > 2) {
            throw new IntegrityConstraint(
                'Only one division symbol (/) can be used',
                'Change the calling code to not provide more than one division symbol',
                'makeFractionFromString needs either one or zero division symbols in the $value argument; '.$value.' given'
            );
        }

        $numerator = Numbers::make(Numbers::IMMUTABLE, trim(ltrim($parts[0])))->round();
        $denominator = isset($parts[1]) ? Numbers::make(Numbers::IMMUTABLE, trim(ltrim($parts[1])))->round() : Numbers::makeOne();

        if ($type == self::IMMUTABLE_FRACTION) {
            return new ImmutableFraction($numerator, $denominator);
        } elseif ($type == self::MUTABLE_FRACTION) {
            return new MutableFraction($numerator, $denominator);
        } else {
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

        $tau = self::make(self::IMMUTABLE, self::TAU);

        if (!is_null($tau)) {
            return $tau->roundToPrecision($precision);
        } else {
            return $tau;
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

        $goldenRatio = self::make(self::IMMUTABLE, self::GOLDEN_RATIO);

        if (!is_null($precision)) {
            return $goldenRatio->roundToPrecision($precision);
        } else {
            return $goldenRatio;
        }

    }

    /**
     * @return ImmutableNumber
     */
    public static function makeOne()
    {
        return self::make(self::IMMUTABLE, 1);
    }

    /**
     * @return ImmutableNumber
     */
    public static function makeZero()
    {
        return self::make(self::IMMUTABLE, 0);
    }

}