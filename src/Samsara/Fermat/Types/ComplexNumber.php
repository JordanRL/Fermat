<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\ComplexInterface;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableNumber;

abstract class ComplexNumber implements ComplexInterface, NumberInterface, DecimalInterface
{

    /** @var ImmutableNumber|ImmutableFraction */
    protected $realPart;

    /** @var ImmutableNumber|ImmutableFraction */
    protected $complexPart;

    public function __construct($realPart, $complexPart, $precision = null, $base = 10)
    {

        if (is_object($realPart) && $realPart instanceof Fraction) {
            $this->realPart = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $realPart, $precision, $base);
        } else {
            $this->realPart = Numbers::makeOrDont(Numbers::IMMUTABLE, $realPart, $precision, $base);
        }
        if (is_object($complexPart) && $complexPart instanceof Fraction) {
            $this->complexPart = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $complexPart, $precision, $base);
        } else {
            $this->complexPart = Numbers::makeOrDont(Numbers::IMMUTABLE, $complexPart, $precision, $base);
        }

    }

    abstract public static function makeFromArray(array $number, $precision = null, $base = 10): ComplexNumber;

    abstract public static function makeFromString(string $expression, $precision = null, $base = 10): ComplexNumber;

    public function asPolar()
    {
        // TODO: Implement asPolar() method.
    }

    public function getAngle()
    {
        // TODO: Implement getAngle() method.
    }

    public function getMagnitude()
    {
        // TODO: Implement getMagnitude() method.
    }

    public function getRealPart()
    {
        return $this->realPart;
    }

    public function getComplexPart()
    {
        return $this->complexPart;
    }

}