<?php

namespace Samsara\Fermat\Core\Types;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Traits\ArithmeticSimpleTrait;
use Samsara\Fermat\Core\Types\Traits\ComparisonTrait;
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

    use ArithmeticSimpleTrait;
    use ComparisonTrait;

    /**
     * Fraction constructor.
     * @param $numerator
     * @param $denominator
     * @param NumberBase $base
     *
     * @throws IntegrityConstraint
     */
    final public function __construct($numerator, $denominator, NumberBase $base = NumberBase::Ten)
    {

        $numerator = Numbers::makeOrDont(Numbers::IMMUTABLE, $numerator, null, $base);
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
            $denominator
        ];

        $this->base = $base;

        if ($numerator->isNegative() xor $denominator->isNegative()) {
            $this->sign = true;
        }

        parent::__construct();

    }

    /**
     * Allows you to set a mode on a number to select the calculation methods.
     *
     * @param CalcMode|null $mode
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
     * Returns the current value formatted according to the settings in getGrouping() and getFormat()
     *
     * @return string
     */
    public function getFormattedValue(): string
    {
        return $this->getNumerator()->getFormattedValue().'/'.$this->getDenominator()->getFormattedValue();
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->getNumerator()->getValue().'/'.$this->getDenominator()->getValue();
    }

    /**
     * @return int|null
     */
    public function getScale(): ?int
    {
        return $this->scale;
    }

    /**
     * @return ImmutableDecimal
     */
    public function getNumerator(): ImmutableDecimal
    {
        return $this->value[0];
    }

    /**
     * @return ImmutableDecimal
     */
    public function getDenominator(): ImmutableDecimal
    {
        return $this->value[1];
    }

    /**
     * @return bool
     */
    public function isComplex(): bool
    {
        return false;
    }

    /**
     * @return Fraction|ImmutableFraction|MutableFraction
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function simplify(): static|ImmutableFraction|MutableFraction
    {

        $gcd = $this->getGreatestCommonDivisor();

        $numerator = $this->getNumerator()->divide($gcd);
        $denominator = $this->getDenominator()->divide($gcd);

        return $this->setValue($numerator, $denominator);

    }

    /**
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
     * @return string
     */
    public function absValue(): string
    {
        return $this->getNumerator()->absValue().'/'.$this->getDenominator()->absValue();
    }

    /**
     * @param $value
     * @return int
     */
    public function compare($value): int
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
     * @param $scale
     * @return ImmutableDecimal
     */
    public function asDecimal($scale = 10): ImmutableDecimal
    {

        /** @var ImmutableDecimal $decimal */
        $decimal = $this->getNumerator()->divide($this->getDenominator(), $scale);

        return $decimal;

    }

    /**
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function getGreatestCommonDivisor(): Decimal
    {
        return $this->getNumerator()->getGreatestCommonDivisor($this->getDenominator());
    }

    /**
     * @param Fraction $fraction
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function getSmallestCommonDenominator(Fraction $fraction): Decimal
    {
        $thisDenominator = $this->getDenominator();
        $thatDenominator = $fraction->getDenominator();

        return $thisDenominator->getLeastCommonMultiple($thatDenominator);
    }

    /**
     * @return string
     */
    public function getAsBaseTenRealNumber(): string
    {
        return $this->getNumerator()->getAsBaseTenRealNumber().'/'.$this->getDenominator()->getAsBaseTenRealNumber();
    }

    /**
     * @return ImmutableDecimal|ImmutableFraction
     */
    public function asReal(): ImmutableDecimal|ImmutableFraction
    {
        return (new ImmutableFraction($this->getNumerator()->getAsBaseTenRealNumber(), $this->getDenominator()->getAsBaseTenRealNumber()))->setMode($this->getMode());
    }

    /**
     * @param Fraction $fraction
     * @param Decimal|null $lcm
     * @return ImmutableDecimal[]
     */
    protected function getNumeratorsWithSameDenominator(Fraction $fraction, Decimal $lcm = null): array
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
     * @param ImmutableDecimal $numerator
     * @param ImmutableDecimal $denominator
     *
     * @return static
     */
    abstract protected function setValue(
        ImmutableDecimal $numerator,
        ImmutableDecimal $denominator
    ): static;

}