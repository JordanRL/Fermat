<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 * @package Samsara\Fermat\Core
 */
trait ComparisonTrait
{

    /**
     * @param Number|int|string|float $value
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isEqual(Number|int|string|float $value): bool
    {
        if ($this instanceof Decimal) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getScale());

            if (($this->isImaginary() xor $value->isImaginary()) && $this->getAsBaseTenRealNumber() != '0') {
                return false;
            }

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

    /**
     * @param $value
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isGreaterThan($value): bool
    {
        if ($this instanceof Decimal) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getScale());

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

    /**
     * @param $value
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isLessThan($value): bool
    {
        if ($this instanceof Decimal) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getScale());

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

    /**
     * @param $value
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isGreaterThanOrEqualTo($value): bool
    {
        if ($this instanceof Decimal) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getScale());

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

    /**
     * @param $value
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isLessThanOrEqualTo($value): bool
    {
        if ($this instanceof Decimal) {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getScale());

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

    /**
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isNegative(): bool
    {
        if ($this instanceof Decimal) {
            if ($this->isEqual(0)) {
                return false;
            }

            return $this->sign;
        } else {
            return $this->getNumerator()->isNegative();
        }
    }

    /**
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isPositive(): bool
    {
        if ($this instanceof Decimal) {
            if ($this->isEqual(0)) {
                return false;
            }

            return !$this->sign;
        } else {
            return $this->getNumerator()->isPositive();
        }
    }

    /**
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isNatural(): bool
    {
        return $this->isInt();
    }

    /**
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isWhole(): bool
    {
        return $this->isInt();
    }

    /**
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isInt(): bool
    {
        if ($this instanceof Decimal) {
            $checkVal = $this->getDecimalPart();
            $checkVal = trim($checkVal, '0');

            return !($checkVal !== '');
        }

        return $this->getDenominator()->isEqual(1);
    }

}