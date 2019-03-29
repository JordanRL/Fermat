<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Values\ImmutableFraction;

trait ComparisonTrait
{

    protected function checkComparisonTraitAndInterface()
    {

        if ($this instanceof DecimalInterface) {
            return 1;
        } elseif ($this instanceof FractionInterface) {
            return 2;
        } else {
            throw new IntegrityConstraint(
                'The ComparisonTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ComparisonTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

    public function isEqual($value): bool
    {

        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

            if ($this->compare($value) === 0) {
                return true;
            } else {
                return false;
            }
        } else {
            /** @var ImmutableFraction $number */
            $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $value);

            if (!$this->getDenominator()->isEqual($number->getDenominator())) {
                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isEqual($thatNumerator);
        }

    }

    public function isGreaterThan($value): bool
    {

        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

            if ($this->compare($value) === 1) {
                return true;
            } else {
                return false;
            }
        } else {
            /** @var ImmutableFraction $number */
            $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $value);

            if (!$this->getDenominator()->isEqual($number->getDenominator())) {
                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isGreaterThan($thatNumerator);
        }

    }

    public function isLessThan($value): bool
    {
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

            if ($this->compare($value) === -1) {
                return true;
            } else {
                return false;
            }
        } else {
            /** @var ImmutableFraction $number */
            $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $value);

            if (!$this->getDenominator()->isEqual($number->getDenominator())) {
                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isLessThan($thatNumerator);
        }

    }

    public function isGreaterThanOrEqualTo($value): bool
    {
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

            if ($this->compare($value) > -1) {
                return true;
            } else {
                return false;
            }
        } else {
            /** @var ImmutableFraction $number */
            $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $value);

            if (!$this->getDenominator()->isEqual($number->getDenominator())) {
                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isGreaterThanOrEqualTo($thatNumerator);
        }

    }

    public function isLessThanOrEqualTo($value): bool
    {
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

            if ($this->compare($value) < 1) {
                return true;
            } else {
                return false;
            }
        } else {
            /** @var ImmutableFraction $number */
            $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $value);

            if (!$this->getDenominator()->isEqual($number->getDenominator())) {
                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isLessThanOrEqualTo($thatNumerator);
        }

    }

    public function isNegative(): bool
    {
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
            if ($this->isEqual(0)) {
                return false;
            }

            if (strpos($this->getValue(), '-') === 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return $this->getNumerator()->isNegative();
        }
    }

    public function isPositive(): bool
    {
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
            if ($this->isEqual(0)) {
                return false;
            }

            return !$this->isNegative();
        } else {
            return $this->getNumerator()->isPositive();
        }
    }

    public function isNatural(): bool
    {
        return $this->isInt();
    }

    public function isWhole(): bool
    {
        return $this->isInt();
    }

    public function isInt(): bool
    {
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
            if ($this->getDecimalPart() == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return $this->getDenominator()->isEqual(1);
        }
    }

}