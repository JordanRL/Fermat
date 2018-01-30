<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Values\ImmutableFraction;

trait ComparisonTrait
{

    public function isEqual($value): bool
    {

        if ($this instanceof DecimalInterface) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

            if ($this->compare($value) === 0) {
                return true;
            } else {
                return false;
            }
        } elseif ($this instanceof FractionInterface) {
            /** @var ImmutableFraction $number */
            $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $number);

            if (!$this->getDenominator()->isEqual($number->getDenominator())) {
                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isEqual($thatNumerator);
        } else {
            throw new IntegrityConstraint(
                'The ComparisonTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ComparisonTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

    public function isGreaterThan($value): bool
    {

        if ($this instanceof DecimalInterface) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

            if ($this->compare($value) === 1) {
                return true;
            } else {
                return false;
            }
        } elseif ($this instanceof FractionInterface) {
            /** @var ImmutableFraction $number */
            $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $number);

            if (!$this->getDenominator()->isEqual($number->getDenominator())) {
                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isGreaterThan($thatNumerator);
        } else {
            throw new IntegrityConstraint(
                'The ComparisonTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ComparisonTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

    public function isLessThan($value): bool
    {

        if ($this instanceof DecimalInterface) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

            if ($this->compare($value) === -1) {
                return true;
            } else {
                return false;
            }
        } elseif ($this instanceof FractionInterface) {
            /** @var ImmutableFraction $number */
            $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $number);

            if (!$this->getDenominator()->isEqual($number->getDenominator())) {
                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isLessThan($thatNumerator);
        } else {
            throw new IntegrityConstraint(
                'The ComparisonTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ComparisonTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

    public function isGreaterThanOrEqualTo($value): bool
    {

        if ($this instanceof DecimalInterface) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

            if ($this->compare($value) > -1) {
                return true;
            } else {
                return false;
            }
        } elseif ($this instanceof FractionInterface) {
            /** @var ImmutableFraction $number */
            $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $number);

            if (!$this->getDenominator()->isEqual($number->getDenominator())) {
                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isGreaterThanOrEqualTo($thatNumerator);
        } else {
            throw new IntegrityConstraint(
                'The ComparisonTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ComparisonTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

    public function isLessThanOrEqualTo($value): bool
    {

        if ($this instanceof DecimalInterface) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

            if ($this->compare($value) < 1) {
                return true;
            } else {
                return false;
            }
        } elseif ($this instanceof FractionInterface) {
            /** @var ImmutableFraction $number */
            $number = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $number);

            if (!$this->getDenominator()->isEqual($number->getDenominator())) {
                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isLessThanOrEqualTo($thatNumerator);
        } else {
            throw new IntegrityConstraint(
                'The ComparisonTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ComparisonTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

}