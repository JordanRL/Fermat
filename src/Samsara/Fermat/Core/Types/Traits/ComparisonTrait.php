<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 * @package Samsara\Fermat\Core
 */
trait ComparisonTrait
{

    /**
     * Compares this number to another number and returns whether or not they are equal.
     *
     * @param Number|int|string|float $value The value to compare against
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
                [$thisNumerator, $thatNumerator] = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isEqual($thatNumerator);
        }

    }

    /**
     * Compares this number to another number and returns true if this number is closer to positive infinity.
     *
     * @param Number|int|string|float $value The value to compare against
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isGreaterThan(Number|int|string|float $value): bool
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
                [$thisNumerator, $thatNumerator] = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isGreaterThan($thatNumerator);
        }

    }

    /**
     * Compares this number to another number and returns true if this number is closer to negative infinity.
     *
     * @param Number|int|string|float $value The value to compare against
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isLessThan(Number|int|string|float $value): bool
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
                [$thisNumerator, $thatNumerator] = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isLessThan($thatNumerator);
        }

    }

    /**
     * Compares this number to another number and returns true if this number is closer to positive infinity or equal.
     *
     * @param Number|int|string|float $value The value to compare against
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isGreaterThanOrEqualTo(Number|int|string|float $value): bool
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
                [$thisNumerator, $thatNumerator] = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isGreaterThanOrEqualTo($thatNumerator);
        }

    }

    /**
     * Compares this number to another number and returns true if this number is closer to negative infinity or equal.
     *
     * @param Number|int|string|float $value The value to compare against
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isLessThanOrEqualTo(Number|int|string|float $value): bool
    {
        if ($this instanceof Decimal) {
            /** @var ImmutableDecimal $value */
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
                [$thisNumerator, $thatNumerator] = $this->getNumeratorsWithSameDenominator($number);
            } else {
                $thisNumerator = $this->getNumerator();
                $thatNumerator = $number->getNumerator();
            }

            return $thisNumerator->isLessThanOrEqualTo($thatNumerator);
        }

    }

    /**
     * Returns true if this number is less than zero
     *
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
     * Returns true if this number is larger than zero
     *
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
     * Alias for isInt(). Returns true if this number has no non-zero digits in the decimal part.
     *
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isNatural(): bool
    {
        return $this->isInt();
    }

    /**
     * Alias for isInt(). Returns true if this number has no non-zero digits in the decimal part.
     *
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isWhole(): bool
    {
        return $this->isInt();
    }

    /**
     * Returns true if this number has no non-zero digits in the decimal part.
     *
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