<?php

namespace Samsara\Fermat\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Base\Number;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticSelectionTrait;
use Samsara\Fermat\Types\Traits\ArithmeticSimpleTrait;
use Samsara\Fermat\Types\Traits\ArithmeticTrait;
use Samsara\Fermat\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Values\ImmutableDecimal;

abstract class Fraction extends Number implements FractionInterface
{

    protected int $base;
    /** @var ImmutableDecimal[] */
    protected array $value;
    /** @var bool */
    protected bool $sign;
    /** @var int */
    protected ?int $scale;

    use ArithmeticSimpleTrait;
    use ComparisonTrait;

    /**
     * Fraction constructor.
     * @param $numerator
     * @param $denominator
     * @param int $base
     *
     * @throws IntegrityConstraint
     */
    public function __construct($numerator, $denominator, int $base = 10)
    {

        $numerator = Numbers::makeOrDont(Numbers::IMMUTABLE, $numerator, null, $base)->round();
        $denominator = Numbers::makeOrDont(Numbers::IMMUTABLE, $denominator, null, $base)->round();

        if ($denominator->isEqual(0)) {
            throw new IntegrityConstraint(
                'The denominator of a fraction cannot be zero.',
                'Provide a denominator other than zero.',
                'Cannot create new instance of Fraction with denominator of zero.'
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

    public function getValue(): string
    {
        return $this->getNumerator()->getValue().'/'.$this->getDenominator()->getValue();
    }

    public function getScale(): ?int
    {
        return $this->scale;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getNumerator()
    {
        return $this->value[0];
    }

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

    public function simplify()
    {

        $gcd = $this->getGreatestCommonDivisor();

        $numerator = $this->getNumerator()->divide($gcd);
        $denominator = $this->getDenominator()->divide($gcd);

        return $this->setValue($numerator, $denominator);

    }

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

    public function absValue(): string
    {
        return $this->getNumerator()->absValue().'/'.$this->getDenominator()->absValue();
    }

    public function compare($number): int
    {
        if ($this->isGreaterThan($number)) {
            return 1;
        } elseif ($this->isLessThan($number)) {
            return -1;
        } else {
            return 0;
        }
    }

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

    public function getSmallestCommonDenominator(FractionInterface $fraction)
    {
        $thisDenominator = $this->getDenominator();
        $thatDenominator = $fraction->getDenominator();

        /** @var NumberInterface $lcm */
        $lcm = $thisDenominator->getLeastCommonMultiple($thatDenominator);

        return $lcm;
    }

    public function getAsBaseTenRealNumber(): string
    {
        return $this->getNumerator()->getAsBaseTenRealNumber().'/'.$this->getDenominator()->getAsBaseTenRealNumber();
    }

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