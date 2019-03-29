<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableNumber;

trait ArithmeticTrait
{

    protected function checkArithmeticTraitAndInterface()
    {

        if ($this instanceof DecimalInterface) {
            return 1;
        } elseif ($this instanceof FractionInterface) {
            return 2;
        } else {
            throw new IntegrityConstraint(
                'The ArithmeticTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ArithmeticTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

    protected function transformNum($num, $instance)
    {

        if ($instance == 1) {
            if (is_object($num) && method_exists($num, 'asDecimal')) {
                $num = $num->asDecimal($this->getPrecision());
            } else {
                $num = Numbers::makeOrDont($this, $num, $this->getPrecision());
            }
        } elseif ($instance == 2) {
            $num = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $num);
        }

        return $num;

    }

    public function add($num)
    {

        $check = $this->checkArithmeticTraitAndInterface();

        $num = $this->transformNum($num, $check);

        if ($check == 1) {
            /** @var DecimalInterface|NumberInterface $num */
            $oldBase = $this->convertForModification();
            $numOldBase = $num->convertForModification();

            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

            $value = ArithmeticProvider::add($this->getValue(), $num->getValue(), $internalPrecision);

            $this->convertFromModification($oldBase);
            $num->convertFromModification($numOldBase);

            return $this->setValue($value)->truncateToPrecision($internalPrecision);
        } else {
            /**
             * @var ImmutableFraction $num
             * @var FractionInterface $this
             */

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

    }

    public function subtract($num)
    {
        $check = $this->checkArithmeticTraitAndInterface();

        $num = $this->transformNum($num, $check);

        if ($check == 1) {
            $oldBase = $this->convertForModification();
            $numOldBase = $num->convertForModification();

            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

            $value = ArithmeticProvider::subtract($this->getValue(), $num->getValue(), $internalPrecision);

            $this->convertFromModification($oldBase);
            $num->convertFromModification($numOldBase);

            return $this->setValue($value)->truncateToPrecision($internalPrecision);
        } else {
            /** @var ImmutableFraction $num */

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

    }

    public function multiply($num)
    {
        $check = $this->checkArithmeticTraitAndInterface();

        $num = $this->transformNum($num, $check);

        if ($check == 1) {
            $oldBase = $this->convertForModification();
            $numOldBase = $num->convertForModification();

            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

            $value = ArithmeticProvider::multiply($this->getValue(), $num->getValue(), $internalPrecision);

            $this->convertFromModification($oldBase);
            $num->convertFromModification($numOldBase);

            return $this->setValue($value)->truncateToPrecision($internalPrecision);
        } else {
            /** @var ImmutableFraction $num */

            $finalDenominator = $this->getDenominator()->multiply($num->getDenominator());
            $finalNumerator = $this->getNumerator()->multiply($num->getNumerator());

            return $this->setValue($finalNumerator, $finalDenominator);
        }

    }

    public function divide($num, $precision = null)
    {
        $check = $this->checkArithmeticTraitAndInterface();

        $num = $this->transformNum($num, $check);

        if ($check == 1) {
            $oldBase = $this->convertForModification();
            $numOldBase = $num->convertForModification();

            if (!is_int($precision)) {
                $precision = ($this->getPrecision() > $num->getPrecision()) ? $num->getPrecision() : $this->getPrecision();
            }

            $value = ArithmeticProvider::divide($this->getValue(), $num->getValue(), $precision);

            $this->convertFromModification($oldBase);
            $num->convertFromModification($numOldBase);

            return $this->setValue($value);
        } else {
            /** @var ImmutableFraction $num */

            $finalDenominator = $this->getDenominator()->multiply($num->getNumerator());
            $finalNumerator = $this->getNumerator()->multiply($num->getDenominator());

            return $this->setValue($finalNumerator, $finalDenominator);
        }
    }

    public function pow($num)
    {
        $check = $this->checkArithmeticTraitAndInterface();

        $num = $this->transformNum($num, $check);

        if ($check == 1) {
            $oldBase = $this->convertForModification();
            $numOldBase = $num->convertForModification();

            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

            if ($num->isWhole()) {
                $value = ArithmeticProvider::pow($this->getValue(), $num->getValue(), $internalPrecision);
            } else {
                $exponent = $num->multiply($this->ln($internalPrecision));
                $value = $exponent->exp();
            }

            $this->convertFromModification($oldBase);
            $num->convertFromModification($numOldBase);

            return $this->setValue($value)->truncateToPrecision($internalPrecision);
        } else {
            /** @var ImmutableNumber $powNumerator */
            $powNumerator = $this->getNumerator()->pow($num);
            /** @var ImmutableNumber $powDenominator */
            $powDenominator = $this->getDenominator()->pow($num);

            if ($powNumerator->isWhole() && $powDenominator->isWhole()) {
                return $this->setValue($powNumerator, $powDenominator);
            } else {
                return $powNumerator->divide($powDenominator);
            }
        }

    }

    public function sqrt($precision = null)
    {
        $check = $this->checkArithmeticTraitAndInterface();

        if ($check == 1) {
            $oldBase = $this->convertForModification();

            if (!is_null($precision) && is_int($precision)) {
                $value = ArithmeticProvider::squareRoot($this->getValue(), $precision);
            } else {
                $value = ArithmeticProvider::squareRoot($this->getValue(), $this->getPrecision());
            }

            $this->convertFromModification($oldBase);

            return $this->setValue($value);
        } else {
            /** @var ImmutableNumber $sqrtNumerator */
            $sqrtNumerator = $this->getNumerator()->sqrt($precision);
            /** @var ImmutableNumber $sqrtDenominator */
            $sqrtDenominator = $this->getDenominator()->sqrt($precision);

            if ($sqrtNumerator->isWhole() && $sqrtDenominator->isWhole()) {
                return $this->setValue($sqrtNumerator, $sqrtDenominator);
            } else {
                return $sqrtNumerator->divide($sqrtDenominator);
            }
        }

    }

}