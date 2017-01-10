<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
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

    public function __construct($numerator, $denominator, $base = 10)
    {

        $this->numerator = Numbers::makeOrDont(Numbers::IMMUTABLE, $numerator)->round();
        $this->denominator = Numbers::makeOrDont(Numbers::IMMUTABLE, $denominator)->round();

    }

    public function simplify()
    {

        $gcd = $this->getGreatestCommonDivisor();

        $numerator = $this->numerator->divide($gcd);
        $denominator = $this->denominator->divide($gcd);

        return $this->setValue($numerator, $denominator);

    }

    public function add($num)
    {

        /** @var ImmutableFraction $num */
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $num);

        if ($this->getDenominator()->isEqual($num->getDenominator())) {
            $finalDenominator = $this->getDenominator();
            $finalNumerator = $this->getNumerator()->add($num->getNumerator());
        } else {
            $finalDenominator = $this->getSmallestCommonDenominator($num);

            list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($num, $finalDenominator);

            $finalNumerator = $thisNumerator->add($thatNumerator);
        }

        return $this->setValue($finalNumerator, $finalDenominator);

    }

    public function subtract($num)
    {

        /** @var ImmutableFraction $num */
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $num);

        if ($this->getDenominator()->isEqual($num->getDenominator())) {
            $finalDenominator = $this->getDenominator();
            $finalNumerator = $this->getNumerator()->subtract($num->getNumerator());
        } else {
            $finalDenominator = $this->getSmallestCommonDenominator($num);

            list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($num, $finalDenominator);

            $finalNumerator = $thisNumerator->subtract($thatNumerator);
        }

        return $this->setValue($finalNumerator, $finalDenominator);

    }

    public function multiply($num)
    {

        /** @var ImmutableFraction $num */
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $num);

        $finalDenominator = $this->getDenominator()->multiply($num->getDenominator());
        $finalNumerator = $this->getNumerator()->multiply($num->getNumerator());

        return $this->setValue($finalNumerator, $finalDenominator);

    }

    public function divide($num)
    {

        /** @var ImmutableFraction $num */
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $num);

        $finalDenominator = $this->getDenominator()->multiply($num->getNumerator());
        $finalNumerator = $this->getNumerator()->multiply($num->getDenominator());

        return $this->setValue($finalNumerator, $finalDenominator);

    }

    public function getNumerator()
    {
        return $this->numerator;
    }

    public function getDenominator()
    {
        return $this->denominator;
    }

    public function getSmallestCommonDenominator(FractionInterface $fraction)
    {
        $thisDenominator = $this->getDenominator();
        $thatDenominator = $fraction->getDenominator();

        /** @var NumberInterface $lcm */
        $lcm = $thisDenominator->getLeastCommonMultiple($thatDenominator);

        return $lcm;
    }

    public function isEqual($number)
    {

        /** @var ImmutableFraction $number */
        $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $number);

        if (!$this->getDenominator()->isEqual($number->getDenominator())) {
            list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
        } else {
            $thisNumerator = $this->getNumerator();
            $thatNumerator = $number->getNumerator();
        }

        return $thisNumerator->isEqual($thatNumerator);

    }

    public function isGreaterThan($number)
    {

        /** @var ImmutableFraction $number */
        $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $number);

        if (!$this->getDenominator()->isEqual($number->getDenominator())) {
            list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
        } else {
            $thisNumerator = $this->getNumerator();
            $thatNumerator = $number->getNumerator();
        }

        return $thisNumerator->isGreaterThan($thatNumerator);

    }

    public function isLessThan($number)
    {

        /** @var ImmutableFraction $number */
        $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $number);

        if (!$this->getDenominator()->isEqual($number->getDenominator())) {
            list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
        } else {
            $thisNumerator = $this->getNumerator();
            $thatNumerator = $number->getNumerator();
        }

        return $thisNumerator->isLessThan($thatNumerator);

    }

    public function isGreaterThanOrEqualTo($number)
    {

        /** @var ImmutableFraction $number */
        $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $number);

        if (!$this->getDenominator()->isEqual($number->getDenominator())) {
            list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
        } else {
            $thisNumerator = $this->getNumerator();
            $thatNumerator = $number->getNumerator();
        }

        return $thisNumerator->isGreaterThanOrEqualTo($thatNumerator);

    }

    public function isLessThanOrEqualTo($number)
    {

        /** @var ImmutableFraction $number */
        $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $number);

        if (!$this->getDenominator()->isEqual($number->getDenominator())) {
            list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
        } else {
            $thisNumerator = $this->getNumerator();
            $thatNumerator = $number->getNumerator();
        }

        return $thisNumerator->isLessThanOrEqualTo($thatNumerator);

    }

    /**
     * @return NumberInterface
     */
    public function getGreatestCommonDivisor()
    {
        return $this->getNumerator()->getGreatestCommonDivisor($this->getDenominator());
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