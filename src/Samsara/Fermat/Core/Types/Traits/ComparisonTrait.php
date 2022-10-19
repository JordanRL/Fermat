<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 *
 */
trait ComparisonTrait
{

    /**
     * @return int
     * @throws IntegrityConstraint
     */
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

    /**
     * @param NumberInterface|int|string|float $value
     * @return bool
     * @throws IntegrityConstraint
     */
    public function isEqual(NumberInterface|int|string|float $value): bool
    {

        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
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

        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
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
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
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
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
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
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
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
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
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
        $check = $this->checkComparisonTraitAndInterface();

        if ($check == 1) {
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
        $check = $this->checkComparisonTraitAndInterface();

        if ($check === 1) {
            $checkVal = $this->getDecimalPart();
            //$checkVal = str_replace('0', '', $checkVal);
            $checkVal = trim($checkVal, '0');

            return !($checkVal !== '');
        }

        return $this->getDenominator()->isEqual(1);
    }

}