<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Values\ImmutableFraction;

trait ArithmeticTrait
{

    public function add($num)
    {

        if ($this instanceof DecimalInterface) {
            if (is_object($num) && method_exists($num, 'asDecimal')) {
                $num = $num->asDecimal($this->getPrecision());
            } else {
                $num = Numbers::makeOrDont($this, $num, $this->getPrecision());
            }

            $oldBase = $this->convertForModification();
            $numOldBase = $num->convertForModification();

            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

            $value = ArithmeticProvider::add($this->getValue(), $num->getValue(), $internalPrecision);

            $this->convertFromModification($oldBase);
            $num->convertFromModification($numOldBase);

            return $this->setValue($value)->truncateToPrecision($internalPrecision);
        } elseif ($this instanceof FractionInterface) {
            /**
             * @var ImmutableFraction $num
             * @var FractionInterface $this
             */
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
        } else {
            throw new IntegrityConstraint(
                'The ArithmeticTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ArithmeticTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

    public function subtract($num)
    {

        if ($this instanceof DecimalInterface) {
            if (is_object($num) && method_exists($num, 'asDecimal')) {
                $num = $num->asDecimal($this->getPrecision());
            } else {
                $num = Numbers::makeOrDont($this, $num, $this->getPrecision());
            }

            $oldBase = $this->convertForModification();
            $numOldBase = $num->convertForModification();

            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

            $value = ArithmeticProvider::subtract($this->getValue(), $num->getValue(), $internalPrecision);

            $this->convertFromModification($oldBase);
            $num->convertFromModification($numOldBase);

            return $this->setValue($value)->truncateToPrecision($internalPrecision);
        } elseif ($this instanceof FractionInterface) {
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
        } else {
            throw new IntegrityConstraint(
                'The ArithmeticTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ArithmeticTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

    public function multiply($num)
    {

        if ($this instanceof DecimalInterface) {
            if (is_object($num) && method_exists($num, 'asDecimal')) {
                $num = $num->asDecimal($this->getPrecision());
            } else {
                $num = Numbers::makeOrDont($this, $num, $this->getPrecision());
            }

            $oldBase = $this->convertForModification();
            $numOldBase = $num->convertForModification();

            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

            $value = ArithmeticProvider::multiply($this->getValue(), $num->getValue(), $internalPrecision);

            $this->convertFromModification($oldBase);
            $num->convertFromModification($numOldBase);

            return $this->setValue($value)->truncateToPrecision($internalPrecision);
        } elseif ($this instanceof FractionInterface) {
            /** @var ImmutableFraction $num */
            $num = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $num);

            $finalDenominator = $this->getDenominator()->multiply($num->getDenominator());
            $finalNumerator = $this->getNumerator()->multiply($num->getNumerator());

            return $this->setValue($finalNumerator, $finalDenominator);
        } else {
            throw new IntegrityConstraint(
                'The ArithmeticTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ArithmeticTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

    public function divide($num, $precision = null)
    {

        if ($this instanceof DecimalInterface) {
            if (is_object($num) && method_exists($num, 'asDecimal')) {
                $num = $num->asDecimal($this->getPrecision());
            } else {
                $num = Numbers::makeOrDont($this, $num, $this->getPrecision());
            }

            $oldBase = $this->convertForModification();
            $numOldBase = $num->convertForModification();

            if (!is_int($precision)) {
                $precision = ($this->getPrecision() > $num->getPrecision()) ? $num->getPrecision() : $this->getPrecision();
            }

            $value = ArithmeticProvider::divide($this->getValue(), $num->getValue(), $precision);

            $this->convertFromModification($oldBase);
            $num->convertFromModification($numOldBase);

            return $this->setValue($value);
        } elseif ($this instanceof FractionInterface) {
            /** @var ImmutableFraction $num */
            $num = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $num);

            $finalDenominator = $this->getDenominator()->multiply($num->getNumerator());
            $finalNumerator = $this->getNumerator()->multiply($num->getDenominator());

            return $this->setValue($finalNumerator, $finalDenominator);
        } else {
            throw new IntegrityConstraint(
                'The ArithmeticTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ArithmeticTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }
    }

}