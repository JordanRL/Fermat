<?php

namespace Samsara\Fermat\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
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
        $lcm = $thisDenominator->multiply($thatDenominator)->abs()->divide($this->getGreatestCommonDivisor($thisDenominator, $thatDenominator));

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
     * @param $a
     * @param $b
     *
     * @return NumberInterface
     */
    protected function getGreatestCommonDivisor($a = null, $b = null)
    {
        if (is_null($a) && is_null($b)) {
            $a = $this->numerator->abs();
            $b = $this->denominator->abs();
        } else {
            $a = Numbers::makeOrDont(Numbers::IMMUTABLE, $a);
            $b = Numbers::makeOrDont(Numbers::IMMUTABLE, $b);
        }

        if (function_exists('gmp_gcd') && function_exists('gmp_strval')) {
            $val = gmp_strval(gmp_gcd($a->getValue(), $b->getValue()));

            return Numbers::make(Numbers::IMMUTABLE, $val);
        } else {

            if ($a->isLessThan($b)) {
                $greater = $b;
                $lesser = $a;
            } else {
                $greater = $a;
                $lesser = $b;
            }

            /** @var NumberInterface $remainder */
            $remainder = $greater->modulo($lesser);

            while ($remainder->isGreaterThan(0)) {
                $greater = $lesser;
                $lesser = $remainder;
                $remainder = $greater->modulo($lesser);
            }

            return $lesser;
        }
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