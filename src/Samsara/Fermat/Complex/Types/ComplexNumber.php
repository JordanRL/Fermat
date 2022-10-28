<?php

namespace Samsara\Fermat\Complex\Types;

use Exception;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Complex\Types\Base\Interfaces\Numbers\ComplexNumberInterface;
use Samsara\Fermat\Complex\Types\Traits\ArithmeticComplexTrait;
use Samsara\Fermat\Complex\Types\Traits\ComplexScaleTrait;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Coordinates\Values\CartesianCoordinate;
use Samsara\Fermat\Coordinates\Values\PolarCoordinate;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Traits\CalculationModeTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 * @package Samsara\Fermat\Complex
 */
abstract class ComplexNumber extends Number implements ComplexNumberInterface
{

    protected CartesianCoordinate $cachedCartesian;
    protected ?PolarCoordinate $cachedPolar = null;
    protected ImmutableDecimal|ImmutableFraction $imaginaryPart;
    protected ImmutableDecimal|ImmutableFraction $realPart;


    use ArithmeticComplexTrait;
    use CalculationModeTrait;
    use ComplexScaleTrait;

    /**
     * @param ImmutableDecimal|ImmutableFraction $realPart
     * @param ImmutableDecimal|ImmutableFraction $imaginaryPart
     * @param int|null                           $scale
     * @param NumberBase                         $base
     *
     * @throws IntegrityConstraint
     */
    final public function __construct(
        ImmutableDecimal|ImmutableFraction $realPart,
        ImmutableDecimal|ImmutableFraction $imaginaryPart,
        ?int                               $scale = null,
        NumberBase                         $base = NumberBase::Ten
    )
    {
        $partsScale = ($realPart->getScale() > $imaginaryPart->getScale()) ? $realPart->getScale() : $imaginaryPart->getScale();
        $scale = $scale ?? $partsScale;
        $this->scale = $scale;

        $this->realPart = $realPart->roundToScale($scale)->setBase($base);
        $this->imaginaryPart = $imaginaryPart->roundToScale($scale)->setBase($base);

        $cartesian = new CartesianCoordinate(
            $this->realPart,
            new ImmutableDecimal($this->imaginaryPart->getAsBaseTenRealNumber())
        );

        $this->cachedCartesian = $cartesian;

        parent::__construct();
    }

    /**
     * @param array      $number
     * @param int|null   $scale
     * @param NumberBase $base
     *
     * @return static
     * @throws IntegrityConstraint
     * @throws OptionalExit
     */
    public static function makeFromArray(array $number, ?int $scale = null, NumberBase $base = NumberBase::Ten): static
    {

        if (count($number) != 2) {
            throw new IntegrityConstraint(
                'Exactly two numbers must be provided for a ComplexNumber',
                'Provide two numbers in the array.',
                'Attempt made to create ComplexNumber with incorrect amount of input numbers.'
            );
        }

        [$part1, $part2] = $number;

        $part1 = new ImmutableDecimal($part1);
        $part2 = new ImmutableDecimal($part2);

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
     * @param string     $expression
     * @param int|null   $scale
     * @param NumberBase $base
     *
     * @return static
     * @throws IntegrityConstraint
     * @throws OptionalExit
     */
    public static function makeFromString(string $expression, ?int $scale = null, NumberBase $base = NumberBase::Ten): static
    {
        if (str_contains($expression, '+')) {
            $parts = explode('+', $expression);
        } elseif (str_contains($expression, '-')) {
            $parts = explode('-', $expression);
            foreach ($parts as $key => &$value) {
                if ($key === 0) {
                    continue;
                }
                $value = '-' . $value;
            }
        } else {
            throw new IntegrityConstraint(
                'To make a complex number from a string, it must have both a real part and a complex part.',
                'Provide a string in a format that can be read, such as "1+2i", "2-1i", or "6i-5".',
                'Cannot determine real part and imaginary part of complex number from given string.'
            );
        }

        return static::makeFromArray($parts, $scale, $base);
    }

    /**
     * @return string
     */
    public function getAsBaseTenRealNumber(): string
    {
        return $this->getDistanceFromOrigin();
    }

    /**
     * @return ImmutableDecimal
     */
    public function getDistanceFromOrigin(): ImmutableDecimal
    {
        return $this->asPolar()->getDistanceFromOrigin();
    }

    /**
     * @return ImmutableDecimal|ImmutableFraction
     */
    public function getImaginaryPart(): ImmutableDecimal|ImmutableFraction
    {
        return $this->imaginaryPart;
    }

    /**
     * @return ImmutableDecimal
     */
    public function getPolarAngle(): ImmutableDecimal
    {
        return $this->asPolar()->getPolarAngle();
    }

    /**
     * @return ImmutableDecimal|ImmutableFraction
     */
    public function getRealPart(): ImmutableDecimal|ImmutableFraction
    {
        return $this->realPart;
    }

    /**
     * @param NumberBase $base
     *
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

        return $this->getRealPart()->getValue($base) . $joiner . $this->getImaginaryPart()->getValue($base);
    }

    /**
     * Allows you to set a mode on a number to select the calculation methods. If this is null, then the default mode in the
     * CalculationModeProvider at the time a calculation is performed will be used.
     *
     * @param CalcMode|null $mode
     *
     * @return static
     */
    public function setMode(?CalcMode $mode): static
    {
        $this->calcMode = $mode;

        $this->realPart->setMode($mode);
        $this->imaginaryPart->setMode($mode);

        return $this;
    }

    /**
     * @return bool
     */
    public function isComplex(): bool
    {
        return true;
    }

    /**
     * @param string|int|float|Number $value
     *
     * @return bool
     */
    public function isEqual(string|int|float|Number $value): bool
    {
        if (is_int($value) || is_float($value)) {
            return false;
        }

        if (is_string($value) && !str_contains($value, 'i')) {
            return false;
        }

        if ($value instanceof Number && !$value->isComplex()) {
            return false;
        }

        if (!($value instanceof Number)) {
            try {
                $value = static::makeFromString($value);
            } catch (Exception) {
                return false;
            }
        }

        return $this->getValue() === $value->getValue();
    }

    /**
     * @param $value
     *
     * @return bool|null
     * @throws IncompatibleObjectState
     */
    public function isGreaterThan($value): bool|null
    {
        return self::throwForComparison();
    }

    /**
     * @param $value
     *
     * @return bool|null
     * @throws IncompatibleObjectState
     */
    public function isGreaterThanOrEqualTo($value): bool|null
    {
        return self::throwForComparison();
    }

    /**
     * @return bool
     */
    public function isImaginary(): bool
    {
        return false;
    }

    /**
     * @param $value
     *
     * @return bool|null
     * @throws IncompatibleObjectState
     */
    public function isLessThan($value): bool|null
    {
        return self::throwForComparison();
    }

    /**
     * @param $value
     *
     * @return bool|null
     * @throws IncompatibleObjectState
     */
    public function isLessThanOrEqualTo($value): bool|null
    {
        return self::throwForComparison();
    }

    /**
     * @return bool
     */
    public function isReal(): bool
    {
        return false;
    }

    /**
     * @return ImmutableComplexNumber
     */
    public function asComplex(): ImmutableComplexNumber
    {
        return new ImmutableComplexNumber($this->getRealPart(), $this->getImaginaryPart());
    }

    /**
     * @return ImmutableDecimal
     */
    public function asImaginary(): ImmutableDecimal
    {
        return (new ImmutableDecimal(
            $this->getAsBaseTenRealNumber() . 'i',
            $this->getScale()
        ))->setMode($this->getMode());
    }

    /**
     * @return PolarCoordinate
     * @throws OptionalExit
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function asPolar(): PolarCoordinate
    {
        if (is_null($this->cachedPolar)) {
            $this->cachedPolar = $this->cachedCartesian->asPolar(
                $this->scale + $this->realPart->numberOfTotalDigits() + $this->imaginaryPart->numberOfTotalDigits()
            );
        }

        return $this->cachedPolar;
    }

    /**
     * @return ImmutableDecimal
     */
    public function asReal(): ImmutableDecimal
    {
        return (new ImmutableDecimal(
            $this->getAsBaseTenRealNumber(),
            $this->getScale()
        ))->setMode($this->getMode());
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
     * @throws IncompatibleObjectState
     */
    protected static function throwForComparison()
    {
        throw new IncompatibleObjectState(
            'Inequality comparisons are not defined for complex numbers.',
            'Check whether an object is a complex number before calling inequalities.'
        );
    }

}