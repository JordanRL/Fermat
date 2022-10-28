<?php

namespace Samsara\Fermat\Core\Types;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Core\Types\Traits\SimpleArithmeticTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableFraction;

/**
 * @package Samsara\Fermat\Core
 */
abstract class Fraction extends Number
{

    /** @var ImmutableDecimal[] */
    protected array $value;

    use SimpleArithmeticTrait;
    use ComparisonTrait;

    /**
     * @param Decimal|string|int|float $numerator The numerator of the fraction
     * @param Decimal|string|int|float $denominator The denominator of the fraction
     * @param NumberBase               $base The base you want this number to have any time the value is retrieved.
     *
     * @throws IntegrityConstraint
     */
    final public function __construct(
        Decimal|string|int|float $numerator,
        Decimal|string|int|float $denominator,
        NumberBase               $base = NumberBase::Ten
    )
    {

        /** @var ImmutableDecimal $numerator */
        $numerator = Numbers::makeOrDont(Numbers::IMMUTABLE, $numerator, null, $base);
        /** @var ImmutableDecimal $denominator */
        $denominator = Numbers::makeOrDont(Numbers::IMMUTABLE, $denominator, null, $base);

        if ($denominator->isEqual(0)) {
            throw new IntegrityConstraint(
                'The denominator of a fraction cannot be zero.',
                'Provide a denominator other than zero.',
                'Cannot create new instance of Fraction with denominator of zero.'
            );
        }

        if (!$numerator->isInt() || !$denominator->isInt()) {
            throw new IntegrityConstraint(
                'The numerator and denominator of a fraction must be whole numbers.',
                'Only provide whole numbers to constructors of Fraction.',
                'An attempt was made to create a fraction with non-integer components.'
            );
        }

        $this->scale = $numerator->getScale() >= $denominator->getScale() ? $numerator->getScale() : $denominator->getScale();

        $numerator = $numerator->truncateToScale($this->scale);
        $denominator = $denominator->truncateToScale($this->scale);

        if ($numerator->isImaginary() xor $denominator->isImaginary()) {
            $this->imaginary = true;
        } else {
            $this->imaginary = false;
        }

        $this->value = [
            $numerator,
            $denominator,
        ];

        $this->base = $base;

        if ($numerator->isNegative() xor $denominator->isNegative()) {
            $this->sign = true;
        }

        parent::__construct();

    }

    /**
     * Returns the current value as a string in base 10, converted to a real number. If the number is imaginary, the i is
     * simply not printed. If the number is complex, then the absolute value is returned.
     *
     * @return string
     */
    public function getAsBaseTenRealNumber(): string
    {
        return $this->getNumerator()->getAsBaseTenRealNumber() . '/' . $this->getDenominator()->getAsBaseTenRealNumber();
    }

    /**
     * Returns the ImmutableDecimal instance for the denominator
     *
     * @return ImmutableDecimal
     */
    public function getDenominator(): ImmutableDecimal
    {
        return $this->value[1];
    }

    /**
     * Returns the current value formatted according to the settings in getGrouping() and getFormat()
     *
     * @return string
     */
    public function getFormattedValue(): string
    {
        return $this->getNumerator()->getFormattedValue() . '/' . $this->getDenominator()->getFormattedValue();
    }

    /**
     * Returns the greatest common divisor for the numerator and denominator.
     *
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function getGreatestCommonDivisor(): Decimal
    {
        return $this->getNumerator()->getGreatestCommonDivisor($this->getDenominator());
    }

    /**
     * Returns the ImmutableDecimal instance for the numerator
     *
     * @return ImmutableDecimal
     */
    public function getNumerator(): ImmutableDecimal
    {
        return $this->value[0];
    }

    /**
     * Gets the new numerators for two fractions after they have been converted to have the same denominator. The denominator
     * used is determined by the output of getSmallestCommonDenominator().
     *
     * @param Fraction     $fraction The fraction to compare this fraction against.
     * @param Decimal|null $lcm The common multiple to use. If left null, will use the least common multiple.
     *
     * @return ImmutableDecimal[]
     */
    public function getNumeratorsWithSameDenominator(Fraction $fraction, Decimal $lcm = null): array
    {

        $thisNumerator = $this->getNumerator();
        $thatNumerator = $fraction->getNumerator();

        if (is_null($lcm)) {
            $lcm = $this->getSmallestCommonDenominator($fraction);
        }

        $thisNumerator = $thisNumerator->multiply($lcm->divide($this->getDenominator()));
        $thatNumerator = $thatNumerator->multiply($lcm->divide($fraction->getDenominator()));

        return [$thisNumerator, $thatNumerator];

    }

    /**
     * Gets this number's setting for the number of decimal places it will calculate accurately based on the inputs.
     *
     * Multiple operations, each rounding or truncating digits, will increase the error and reduce the actual accuracy of
     * the result.
     *
     * @return int|null
     */
    public function getScale(): ?int
    {
        return $this->scale;
    }

    /**
     * Returns the smallest common denominator between two fractions.
     *
     * @param Fraction $fraction The fraction to compare this fraction against
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public function getSmallestCommonDenominator(Fraction $fraction): ImmutableDecimal
    {
        $thisDenominator = $this->getDenominator();
        $thatDenominator = $fraction->getDenominator();

        return $thisDenominator->getLeastCommonMultiple($thatDenominator);
    }

    /**
     * Returns the current value as a string.
     *
     * @param NumberBase|null $base If provided, will return the value in the provided base, regardless of the object's base setting.
     *
     * @return string
     * @throws IntegrityConstraint
     */
    public function getValue(?NumberBase $base = null): string
    {
        return $this->getNumerator()->getValue($base) . '/' . $this->getDenominator()->getValue($base);
    }

    /**
     * @param ImmutableDecimal $numerator
     * @param ImmutableDecimal $denominator
     *
     * @return static
     */
    abstract protected function setValue(
        ImmutableDecimal $numerator,
        ImmutableDecimal $denominator
    ): static;

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

        $this->value[0]->setMode($mode);
        $this->value[1]->setMode($mode);

        return $this;
    }

    /**
     * Returns true if the number is complex, false if the number is real or imaginary.
     *
     * @return bool
     */
    public function isComplex(): bool
    {
        return false;
    }

    /**
     * @return ImmutableComplexNumber
     * @throws IntegrityConstraint
     */
    public function asComplex(): ImmutableComplexNumber
    {
        if ($this->isReal()) {
            return new ImmutableComplexNumber($this->asReal(), Numbers::makeZero());
        }

        return new ImmutableComplexNumber(Numbers::makeZero(), $this->asImaginary());
    }

    /**
     * Converts the fraction to an ImmutableDecimal by performing the division that the fraction implies.
     *
     * @param int $scale
     *
     * @return ImmutableDecimal
     */
    public function asDecimal(int $scale = 10): ImmutableDecimal
    {

        /** @var ImmutableDecimal $decimal */
        $decimal = $this->getNumerator()->divide($this->getDenominator(), $scale);

        return $decimal;

    }

    /**
     * Returns a new instance of this object with a base ten imaginary number.
     *
     * @return ImmutableFraction
     */
    public function asImaginary(): ImmutableFraction
    {
        return (new ImmutableFraction(
            $this->getNumerator()->getAsBaseTenRealNumber() . 'i',
            $this->getDenominator()->getAsBaseTenRealNumber()
        ))->setMode($this->getMode());
    }

    /**
     * Returns a new instance of this object with a base ten real number.
     *
     * @return ImmutableFraction
     */
    public function asReal(): ImmutableFraction
    {
        return (new ImmutableFraction(
            $this->getNumerator()->getAsBaseTenRealNumber(),
            $this->getDenominator()->getAsBaseTenRealNumber()
        ))->setMode($this->getMode());
    }

    /**
     * Returns the current object as the absolute value of itself.
     *
     * @return ImmutableFraction|MutableFraction|static
     * @throws IntegrityConstraint
     */
    public function abs(): ImmutableFraction|MutableFraction|static
    {
        if ($this->isPositive()) {
            return $this;
        } else {
            /** @var ImmutableDecimal $numerator */
            $numerator = $this->getNumerator()->abs();
            /** @var ImmutableDecimal $denominator */
            $denominator = $this->getDenominator()->abs();
            return (new static($numerator, $denominator, $this->getBase()))->setMode($this->getMode());
        }
    }

    /**
     * Returns the string of the absolute value of the current object.
     *
     * @return string
     */
    public function absValue(): string
    {
        return $this->getNumerator()->absValue() . '/' . $this->getDenominator()->absValue();
    }

    /**
     * Returns the sort compare integer (signum) (-1, 0, 1) for the two numbers.
     *
     * @param Number|int|float|string $value
     *
     * @return int
     */
    public function compare(Number|int|float|string $value): int
    {
        if ($this->isGreaterThan($value)) {
            return 1;
        } elseif ($this->isLessThan($value)) {
            return -1;
        } else {
            return 0;
        }
    }

    /**
     * Simplifies the current fraction to its reduced form.
     *
     * @return static
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function simplify(): static
    {

        $gcd = $this->getGreatestCommonDivisor();

        $numerator = $this->getNumerator()->divide($gcd);
        $denominator = $this->getDenominator()->divide($gcd);

        return $this->setValue($numerator, $denominator);

    }

}