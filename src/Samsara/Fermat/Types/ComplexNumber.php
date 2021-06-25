<?php

namespace Samsara\Fermat\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\ComplexNumberInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Types\Traits\ArithmeticComplexTrait;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\PolarCoordinate;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableDecimal;

abstract class ComplexNumber extends PolarCoordinate implements ComplexNumberInterface
{

    /** @var ImmutableDecimal|ImmutableFraction */
    protected $realPart;
    /** @var ImmutableDecimal|ImmutableFraction */
    protected $imaginaryPart;
    /** @var int */
    protected $scale;

    use ArithmeticComplexTrait;

    public function __construct($realPart, $imaginaryPart, $scale = null, $base = 10)
    {

        if (is_object($realPart) && $realPart instanceof Fraction) {
            $this->realPart = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $realPart, $scale, $base);
        } elseif (!is_object($realPart) || !($realPart instanceof ImmutableDecimal)) {
            $this->realPart = Numbers::makeOrDont(Numbers::IMMUTABLE, $realPart, $scale, $base);
        } else {
            $this->realPart = $realPart;
        }
        if (is_object($imaginaryPart) && $imaginaryPart instanceof Fraction) {
            $this->imaginaryPart = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $imaginaryPart, $scale, $base);
        } elseif (!is_object($imaginaryPart) || !($imaginaryPart instanceof ImmutableDecimal)) {
            $this->imaginaryPart = Numbers::makeOrDont(Numbers::IMMUTABLE, $imaginaryPart, $scale, $base);
        } else {
            $this->imaginaryPart = $imaginaryPart;
        }

        $this->scale = ($this->realPart->getScale() > $this->imaginaryPart->getScale()) ? $this->realPart->getScale() : $this->imaginaryPart->getScale();

        $cartesian = new CartesianCoordinate($this->realPart, Numbers::make(Numbers::IMMUTABLE, $this->imaginaryPart->getAsBaseTenRealNumber()));
        $this->cachedCartesian = $cartesian;

        $polar = $cartesian->asPolar();

        parent::__construct($polar->getDistanceFromOrigin(), $polar->getPolarAngle());

    }

    public function getRealPart(): SimpleNumberInterface
    {
        return $this->realPart;
    }

    public function getImaginaryPart(): SimpleNumberInterface
    {
        return $this->imaginaryPart;
    }

    public function getScale(): int
    {
        return $this->scale;
    }

    public function isComplex(): bool
    {
        return true;
    }

    public function isImaginary(): bool
    {
        return false;
    }

    public function isReal(): bool
    {
        return false;
    }

    public function asReal(): string
    {
        return $this->getDistanceFromOrigin();
    }

    public function isEqual($value): bool
    {
        if (!($value instanceof NumberInterface)) {
            if (is_string($value)) {
                try {
                    $value = static::makeFromString($value);
                } catch (IntegrityConstraint $constraint) {
                    return false;
                }
            } elseif (is_array($value)) {
                try {
                    $value = static::makeFromArray($value);
                } catch (IntegrityConstraint $constraint) {
                    return false;
                }
            } else {
                return false;
            }
        }

        if ($value->isComplex() == false) {
            return false;
        }

        $valueParts = $value->getValue()->all();

        $equal = true;

        foreach ($valueParts as $key => $part) {
            $equal = $equal && $this->values->get($key)->isEqual($part);
        }

        return $equal;
    }

    abstract protected function setValue(SimpleNumberInterface $realPart, SimpleNumberInterface $imaginaryPart);

    public static function makeFromArray(array $number, $scale = null, $base = 10): ComplexNumber
    {

        if (count($number) != 2) {
            throw new IntegrityConstraint(
                'Exactly two numbers must be provided for a ComplexNumber',
                'Provide two numbers in the array.',
                'Attempt made to create ComplexNumber with incorrect amount of input numbers.'
            );
        }

        list($part1, $part2) = $number;

        $part1 = Numbers::make(Numbers::IMMUTABLE, $part1);
        $part2 = Numbers::make(Numbers::IMMUTABLE, $part2);

        if (($part1->isReal() && $part2->isReal()) || ($part1->isImaginary() && $part2->isImaginary())) {
            throw new IntegrityConstraint(
                'A complex number must have both an imaginary component and real component.',
                'Provide a string that contains both an imaginary number and a real number.',
                'Attempted to make a complex number from two numbers of the same type.'
            );
        }

        $realPart = $part1->isReal() ? $part1 : $part2;
        $imaginaryPart = $part2->isImaginary() ? $part2 : $part1;

        return new static($realPart, $imaginaryPart, $scale, $base);

    }

    public static function makeFromString(string $expression, $scale = null, $base = 10): ComplexNumber
    {
        if (strpos($expression, '+') !== false) {
            list($part1, $part2) = explode('+', $expression);
        } elseif (strpos($expression, '-') !== false) {
            list($part1, $part2) = explode('-', $expression);
        } else {
            throw new IntegrityConstraint(
                'To make a complex number from a string, it must have both a real part and a complex part.',
                'Provide a string in a format that can be read, such as "1 + 2i", "2 - 1i", or "6i - 5".',
                'Cannot determine real part and imaginary part of complex number from given string.'
            );
        }

        return static::makeFromArray([$part1, $part2], $scale, $base);
    }

    public function abs(): ImmutableDecimal
    {
        return $this->getDistanceFromOrigin();
    }

    public function absValue(): string
    {
        return $this->getDistanceFromOrigin()->getValue();
    }

    public function getValue(): string
    {
        if (!$this->getImaginaryPart()->isNegative()) {
            $joiner = '+';
        } else {
            $joiner = '';
        }

        return $this->getRealPart()->getValue().$joiner.$this->getImaginaryPart()->getValue();
    }

    public function asComplex(): ComplexNumber
    {
        return $this;
    }

}