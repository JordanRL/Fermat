<?php

namespace Samsara\Fermat\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Base\Number;
use Samsara\Fermat\Types\Traits\ArithmeticSimpleTrait;
use Samsara\Fermat\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 *
 */
abstract class Fraction extends Number implements FractionInterface
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
    public function __construct($numerator, $denominator, NumberBase $base = NumberBase::Ten)
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
     * @return mixed|DecimalInterface|ImmutableDecimal
     */
    public function getNumerator()
    {
        return $this->value[0];
    }

    /**
     * @return mixed|DecimalInterface|ImmutableDecimal
     */
    public function getDenominator()
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
     * @return FractionInterface|Fraction
     * @throws IntegrityConstraint
     */
    public function simplify()
    {

        $gcd = $this->getGreatestCommonDivisor();

        $numerator = $this->getNumerator()->divide($gcd);
        $denominator = $this->getDenominator()->divide($gcd);

        return $this->setValue($numerator, $denominator);

    }

    /**
     * @return $this|Base\Interfaces\Numbers\DecimalInterface|FractionInterface|NumberInterface|Fraction
     */
    public function abs()
    {
        if ($this->isPositive()) {
            return $this;
        } else {
            /** @var ImmutableDecimal $numerator */
            $numerator = $this->getNumerator()->abs();
            /** @var ImmutableDecimal $denominator */
            $denominator = $this->getDenominator()->abs();
            return $this->setValue(
                $numerator,
                $denominator
            );
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
     * @return NumberInterface
     * @throws IntegrityConstraint
     */
    public function getGreatestCommonDivisor()
    {
        return $this->getNumerator()->getGreatestCommonDivisor($this->getDenominator());
    }

    /**
     * @param FractionInterface $fraction
     * @return NumberInterface
     * @throws IntegrityConstraint
     */
    public function getSmallestCommonDenominator(FractionInterface $fraction)
    {
        $thisDenominator = $this->getDenominator();
        $thatDenominator = $fraction->getDenominator();

        /** @var NumberInterface $lcm */
        $lcm = $thisDenominator->getLeastCommonMultiple($thatDenominator);

        return $lcm;
    }

    /**
     * @return string
     */
    public function getAsBaseTenRealNumber(): string
    {
        return $this->getNumerator()->getAsBaseTenRealNumber().'/'.$this->getDenominator()->getAsBaseTenRealNumber();
    }

    /**
     * @param FractionInterface $fraction
     * @param NumberInterface|null $lcm
     * @return array
     */
    protected function getNumeratorsWithSameDenominator(FractionInterface $fraction, NumberInterface $lcm = null)
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
     * @return Fraction
     */
    abstract protected function setValue(ImmutableDecimal $numerator, ImmutableDecimal $denominator);

}