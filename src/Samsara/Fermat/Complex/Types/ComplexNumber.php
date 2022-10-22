<?php

namespace Samsara\Fermat\Complex\Types;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Complex\ComplexNumbers;
use Samsara\Fermat\Complex\Types\Traits\ComplexScaleTrait;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Coordinates\Values\PolarCoordinate;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Complex\Types\Base\Interfaces\Numbers\ComplexNumberInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\ScaleInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Complex\Types\Traits\ArithmeticComplexTrait;
use Samsara\Fermat\Coordinates\Values\CartesianCoordinate;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Traits\CalculationModeTrait;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Types\Fraction;

/**
 *
 */
abstract class ComplexNumber extends Number implements ComplexNumberInterface, ScaleInterface
{

    protected ImmutableDecimal|ImmutableFraction $realPart;
    protected ImmutableDecimal|ImmutableFraction $imaginaryPart;
    protected CartesianCoordinate $cachedCartesian;
    protected PolarCoordinate $cachedPolar;


    use ArithmeticComplexTrait;
    use CalculationModeTrait;
    use ComplexScaleTrait;

    /**
     * @param ImmutableDecimal|ImmutableFraction $realPart
     * @param ImmutableDecimal|ImmutableFraction $imaginaryPart
     * @param int|null $scale
     * @param NumberBase $base
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws OptionalExit
     */
    public function __construct(
        ImmutableDecimal|ImmutableFraction $realPart,
        ImmutableDecimal|ImmutableFraction $imaginaryPart,
        ?int $scale = null,
        NumberBase $base = NumberBase::Ten
    )
    {
        $partsScale = ($realPart->getScale() > $imaginaryPart->getScale()) ? $realPart->getScale() : $imaginaryPart->getScale();
        $scale = $scale ?? $partsScale;
        $this->scale = $scale;

        $this->realPart = $realPart->roundToScale($scale)->setBase($base);
        $this->imaginaryPart = $imaginaryPart->roundToScale($scale)->setBase($base);

        $cartesian = new CartesianCoordinate(
            $this->realPart,
            Numbers::make(
                Numbers::IMMUTABLE,
                $this->imaginaryPart->getAsBaseTenRealNumber()
            )
        );

        $this->cachedCartesian = $cartesian;
        $this->cachedPolar = $cartesian->asPolar($this->scale + $this->realPart->numberOfTotalDigits() + $this->imaginaryPart->numberOfTotalDigits());

        parent::__construct();
    }

    /**
     * Allows you to set a mode on a number to select the calculation methods.
     *
     * @param ?CalcMode $mode
     * @return $this
     */
    public function setMode(?CalcMode $mode): self
    {
        $this->calcMode = $mode;

        $this->realPart->setMode($mode);
        $this->imaginaryPart->setMode($mode);

        return $this;
    }

    /**
     * @return PolarCoordinate
     */
    public function asPolar(): PolarCoordinate
    {
        return $this->cachedPolar;
    }

    /**
     * @return string
     */
    public function getAsBaseTenRealNumber(): string
    {
        return $this->getDistanceFromOrigin();
    }

    /**
     * @return ImmutableDecimal|ImmutableFraction
     */
    public function getRealPart(): ImmutableDecimal|ImmutableFraction
    {
        return $this->realPart;
    }

    /**
     * @return ImmutableDecimal|ImmutableFraction
     */
    public function getImaginaryPart(): ImmutableDecimal|ImmutableFraction
    {
        return $this->imaginaryPart;
    }

    /**
     * @return bool
     */
    public function isComplex(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isImaginary(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isReal(): bool
    {
        return false;
    }

    /**
     * @return ImmutableDecimal|ImmutableFraction
     */
    public function asReal(): ImmutableDecimal|ImmutableFraction
    {
        return (new ImmutableDecimal($this->getAsBaseTenRealNumber(), $this->getScale()))->setMode($this->getMode());
    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $value
     *
     * @return bool
     */
    public function isEqual(string|int|float|Decimal|Fraction|ComplexNumber $value): bool
    {
        if (is_int($value) || is_float($value)) {
            return false;
        }

        if (is_string($value) && !str_contains($value, 'i')) {
            return false;
        } else {
            $value = ComplexNumbers::make(ComplexNumbers::IMMUTABLE_COMPLEX, $value);
        }

        if (!$value->isComplex()) {
            return false;
        }

        if (!($value instanceof NumberInterface)) {
            if (is_string($value)) {
                try {
                    $value = static::makeFromString($value);
                } catch (IntegrityConstraint) {
                    return false;
                }
            } elseif (is_array($value)) {
                try {
                    $value = static::makeFromArray($value);
                } catch (IntegrityConstraint) {
                    return false;
                }
            } else {
                return false;
            }
        }

        return $this->getValue() === $value->getValue();
    }

    /**
     * @param array $number
     * @param $scale
     * @param NumberBase $base
     * @return ComplexNumber
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws OptionalExit
     */
    public static function makeFromArray(array $number, $scale = null, NumberBase $base = NumberBase::Ten): ComplexNumber
    {

        if (count($number) != 2) {
            throw new IntegrityConstraint(
                'Exactly two numbers must be provided for a ComplexNumber',
                'Provide two numbers in the array.',
                'Attempt made to create ComplexNumber with incorrect amount of input numbers.'
            );
        }

        [$part1, $part2] = $number;

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

    /**
     * @param string $expression
     * @param $scale
     * @param NumberBase $base
     * @return ComplexNumber
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws OptionalExit
     */
    public static function makeFromString(string $expression, $scale = null, NumberBase $base = NumberBase::Ten): ComplexNumber
    {
        if (str_contains($expression, '+')) {
            [$part1, $part2] = explode('+', $expression);
        } elseif (str_contains($expression, '-')) {
            [$part1, $part2] = explode('-', $expression);
        } else {
            throw new IntegrityConstraint(
                'To make a complex number from a string, it must have both a real part and a complex part.',
                'Provide a string in a format that can be read, such as "1 + 2i", "2 - 1i", or "6i - 5".',
                'Cannot determine real part and imaginary part of complex number from given string.'
            );
        }

        return static::makeFromArray([$part1, $part2], $scale, $base);
    }

    /**
     * @return ImmutableDecimal
     */
    public function getDistanceFromOrigin(): ImmutableDecimal
    {
        return $this->cachedPolar->getDistanceFromOrigin();
    }

    /**
     * @return ImmutableDecimal
     */
    public function getPolarAngle(): ImmutableDecimal
    {
        return $this->cachedPolar->getPolarAngle();
    }

    /**
     * @return ImmutableDecimal
     */
    public function abs(): ImmutableDecimal
    {
        return $this->getDistanceFromOrigin()->roundToScale($this->getScale());
    }

    /**
     * @return string
     */
    public function absValue(): string
    {
        return $this->abs()->getValue();
    }

    /**
     * @param NumberBase $base
     * @return string
     * @throws IntegrityConstraint
     */
    public function getValue(NumberBase $base = NumberBase::Ten): string
    {
        if (!$this->getImaginaryPart()->isNegative()) {
            $joiner = '+';
        } else {
            $joiner = '';
        }

        return $this->getRealPart()->getValue($base).$joiner.$this->getImaginaryPart()->getValue($base);
    }

    /**
     * @return ImmutableComplexNumber
     */
    public function asComplex(): ImmutableComplexNumber
    {
        return new ImmutableComplexNumber($this->getRealPart(), $this->getImaginaryPart());
    }

}