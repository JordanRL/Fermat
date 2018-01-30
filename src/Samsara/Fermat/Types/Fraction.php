<?php

namespace Samsara\Fermat\Types;

use Riimu\Kit\BaseConversion\BaseConverter;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Types\Traits\ArithmeticTrait;
use Samsara\Fermat\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableNumber;

abstract class Fraction
{

    protected $base;

    /**
     * @var ImmutableNumber
     */
    protected $numerator;

    /**
     * @var ImmutableNumber
     */
    protected $denominator;

    use ArithmeticTrait;
    use ComparisonTrait;

    public function __construct($numerator, $denominator, $base = 10)
    {

        $this->numerator = Numbers::makeOrDont(Numbers::IMMUTABLE, $numerator, null, $base)->round();
        $this->denominator = Numbers::makeOrDont(Numbers::IMMUTABLE, $denominator, null, $base)->round();

        $this->base = $base;

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
        return $this->numerator;
    }

    public function getDenominator()
    {
        return $this->denominator;
    }

    public function simplify()
    {

        $gcd = $this->getGreatestCommonDivisor();

        $numerator = $this->getNumerator()->divide($gcd);
        $denominator = $this->getDenominator()->divide($gcd);

        return $this->setValue($numerator, $denominator);

    }

    public function sqrt()
    {

        /** @var ImmutableNumber $sqrtNumerator */
        $sqrtNumerator = $this->getNumerator()->sqrt();
        /** @var ImmutableNumber $sqrtDenominator */
        $sqrtDenominator = $this->getDenominator()->sqrt();

        if ($sqrtNumerator->isWhole() && $sqrtDenominator->isWhole()) {
            return $this->setValue($sqrtNumerator, $sqrtDenominator);
        } else {
            return $sqrtNumerator->divide($sqrtDenominator);
        }

    }

    public function abs()
    {
        if ($this->isPositive()) {
            return $this;
        } else {
            return $this->setValue($this->getNumerator()->abs(), $this->getDenominator()->abs());
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

    public function isPositive()
    {
        return $this->getNumerator()->isPositive();
    }

    public function isNegative()
    {
        return $this->getNumerator()->isNegative();
    }

    public function asDecimal($precision = 10)
    {

        /** @var ImmutableNumber $decimal */
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

    public function convertToBase($base)
    {
        $converter = new BaseConverter($this->getBase(), $base);

        $converter->setPrecision(10);

        /** @var ImmutableNumber $numerator */
        $numerator = $this->getNumerator()->convertToBase($base);
        /** @var ImmutableNumber $denominator */
        $denominator = $this->getDenominator()->convertToBase($base);

        $this->base = $base;

        return $this->setValue($numerator, $denominator);
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
     * @param ImmutableNumber $numerator
     * @param ImmutableNumber $denominator
     * @return Fraction
     */
    abstract protected function setValue(ImmutableNumber $numerator, ImmutableNumber $denominator);

}