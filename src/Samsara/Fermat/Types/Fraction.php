<?php

namespace Samsara\Fermat\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\FractionInterface;
use Samsara\Fermat\Types\Base\Number;
use Samsara\Fermat\Types\Base\Interfaces\NumberInterface;
use Samsara\Fermat\Types\Traits\ArithmeticTrait;
use Samsara\Fermat\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Values\ImmutableDecimal;

abstract class Fraction extends Number implements FractionInterface
{

    protected $base;
    /** @var ImmutableDecimal[] */
    protected $value;

    use ArithmeticTrait;
    use ComparisonTrait;

    /**
     * Fraction constructor.
     * @param $numerator
     * @param $denominator
     * @param int $base
     *
     * @throws IntegrityConstraint
     */
    public function __construct($numerator, $denominator, $base = 10)
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

        if ($numerator->isImaginary() xor $denominator->isImaginary()) {
            $dummyValue = 'i';
        } else {
            $dummyValue = '';
        }

        $this->value = [
            $numerator,
            $denominator
        ];

        $this->base = $base;

        parent::__construct($dummyValue);

    }

    public function getValue()
    {
        return $this->getNumerator()->getValue().'/'.$this->getDenominator()->getValue();
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

    public function absValue()
    {
        if ($this->isPositive()) {
            return $this->getValue();
        } else {
            return substr($this->getValue(), 1);
        }
    }

    public function compare($number)
    {
        if ($this->isGreaterThan($number)) {
            return 1;
        } elseif ($this->isLessThan($number)) {
            return -1;
        } else {
            return 0;
        }
    }

    public function asDecimal($precision = 10)
    {

        /** @var ImmutableDecimal $decimal */
        $decimal = $this->getNumerator()->divide($this->getDenominator(), $precision);

        return $decimal;

    }

    /**
     * @return NumberInterface
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