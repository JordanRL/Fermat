<?php

namespace Samsara\Fermat\Complex\Types;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\ComplexNumbers;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Coordinates\Values\PolarCoordinate;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Complex\Types\Base\Interfaces\Numbers\ComplexNumberInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\ScaleInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Complex\Types\Traits\ArithmeticComplexTrait;
use Samsara\Fermat\Coordinates\Values\CartesianCoordinate;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Traits\CalculationModeTrait;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 *
 */
abstract class ComplexNumber extends Number implements ComplexNumberInterface, ScaleInterface
{

    /** @var ImmutableDecimal|ImmutableFraction */
    protected ImmutableDecimal|ImmutableFraction $realPart;
    /** @var ImmutableDecimal|ImmutableFraction */
    protected ImmutableDecimal|ImmutableFraction $imaginaryPart;
    /** @var int */
    protected int $scale;
    protected CartesianCoordinate $cachedCartesian;
    protected PolarCoordinate $cachedPolar;


    use ArithmeticComplexTrait;
    use CalculationModeTrait;

    /**
     * @param $realPart
     * @param $imaginaryPart
     * @param $scale
     * @param NumberBase $base
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function __construct($realPart, $imaginaryPart, $scale = null, NumberBase $base = NumberBase::Ten)
    {

        if ($realPart instanceof Fraction) {
            $this->realPart = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $realPart, $scale, $base);
        } elseif (!is_object($realPart) || !($realPart instanceof ImmutableDecimal)) {
            $this->realPart = Numbers::makeOrDont(Numbers::IMMUTABLE, $realPart, $scale, $base);
        } else {
            $this->realPart = $realPart;
        }
        if ($imaginaryPart instanceof Fraction) {
            $this->imaginaryPart = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $imaginaryPart, $scale, $base);
        } elseif (!is_object($imaginaryPart) || !($imaginaryPart instanceof ImmutableDecimal)) {
            $this->imaginaryPart = Numbers::makeOrDont(Numbers::IMMUTABLE, $imaginaryPart, $scale, $base);
        } else {
            $this->imaginaryPart = $imaginaryPart;
        }

        $this->scale = ($this->realPart->getScale() > $this->imaginaryPart->getScale()) ? $this->realPart->getScale() : $this->imaginaryPart->getScale();

        $cartesian = new CartesianCoordinate($this->realPart, Numbers::make(Numbers::IMMUTABLE, $this->imaginaryPart->getAsBaseTenRealNumber()));
        $this->cachedCartesian = $cartesian;

        $this->cachedPolar = $cartesian->asPolar($this->scale+$this->realPart->numberOfTotalDigits()+$this->imaginaryPart->numberOfTotalDigits());
    }

    /**
     * @param int $decimals
     * @param RoundingMode|null $mode
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function round(
        int $decimals = 0,
        ?RoundingMode $mode = null
    ): ImmutableComplexNumber|MutableComplexNumber|static
    {
        $roundedReal = $this->realPart->round($decimals, $mode);
        $roundedImaginary = $this->imaginaryPart->round($decimals, $mode);

        return $this->setValue($roundedReal, $roundedImaginary);
    }

    /**
     * @param int $scale
     * @param RoundingMode|null $mode
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function roundToScale(
        int $scale,
        ?RoundingMode $mode = null
    ): ImmutableComplexNumber|MutableComplexNumber|static
    {
        $roundedReal = $this->realPart->round($scale, $mode);
        $roundedImaginary = $this->imaginaryPart->round($scale, $mode);

        return $this->setValue($roundedReal, $roundedImaginary, $scale);
    }

    /**
     * @param int $decimals
     *
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function truncate(
        int $decimals = 0
    ): ImmutableComplexNumber|MutableComplexNumber|static
    {
        $roundedReal = $this->realPart->truncate($decimals);
        $roundedImaginary = $this->imaginaryPart->truncate($decimals);

        return $this->setValue($roundedReal, $roundedImaginary);
    }

    /**
     * @param int $scale
     *
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function truncateToScale(
        int $scale
    ): ImmutableComplexNumber|MutableComplexNumber|static
    {
        $roundedReal = $this->realPart->truncate($scale);
        $roundedImaginary = $this->imaginaryPart->truncate($scale);

        return $this->setValue($roundedReal, $roundedImaginary, $scale);
    }

    /**
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function ceil(): ImmutableComplexNumber|MutableComplexNumber|static
    {
        return $this->round(0, RoundingMode::Ceil);
    }

    /**
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function floor(): ImmutableComplexNumber|MutableComplexNumber|static
    {
        return $this->round(0, RoundingMode::Floor);
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

    public function asPolar(): PolarCoordinate
    {
        return $this->cachedPolar;
    }

    public function getAsBaseTenRealNumber(): string
    {
        return $this->getDistanceFromOrigin();
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

    public function asReal(): ImmutableDecimal|ImmutableFraction
    {
        return (new ImmutableDecimal($this->getAsBaseTenRealNumber(), $this->getScale()))->setMode($this->getMode());
    }

    public function isEqual($value): bool
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

    abstract protected function setValue(
        ImmutableDecimal|ImmutableFraction $realPart,
        ImmutableDecimal|ImmutableFraction $imaginaryPart,
        ?int $scale = null
    ): static|ImmutableComplexNumber|MutableComplexNumber;

    public static function makeFromArray(array $number, $scale = null, NumberBase $base = NumberBase::Ten): ComplexNumber
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

    public static function makeFromString(string $expression, $scale = null, NumberBase $base = NumberBase::Ten): ComplexNumber
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

    public function abs(): ImmutableDecimal
    {
        return $this->getDistanceFromOrigin()->roundToScale($this->getScale());
    }

    public function absValue(): string
    {
        return $this->abs()->getValue();
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

    public function asComplex(): ImmutableComplexNumber
    {
        return new ImmutableComplexNumber($this->getRealPart(), $this->getImaginaryPart());
    }

}