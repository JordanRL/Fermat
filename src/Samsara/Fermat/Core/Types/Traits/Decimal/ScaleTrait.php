<?php

namespace Samsara\Fermat\Core\Types\Traits\Decimal;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Provider\ArithmeticProvider;
use Samsara\Fermat\Core\Provider\RoundingProvider;
use Samsara\Fermat\Core\Types\Base\Number;

/**
 * @package Samsara\Fermat\Core
 */
trait ScaleTrait
{

    protected int $scale;

    /**
     * Gets this number's setting for the number of decimal places it will calculate accurately based on the inputs.
     *
     * Multiple operations, each rounding or truncating digits, will increase the error and reduce the actual accuracy of
     * the result.
     *
     * @return int
     */
    public function getScale(): int
    {
        return $this->scale;
    }

    /**
     * @param int $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @return static
     */
    protected function setScale(int $scale): static
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * Round this number's value to the given number of decimal places, but keep the current scale setting of this number.
     *
     * NOTE: Rounding to a negative number of digits will round the integer part of the number.
     *
     * @param int $decimals The number of decimal places to round to. Negative values round that many integer digits.
     * @param RoundingMode|null $mode The rounding mode to use for this operation. If null, will use the current default mode.
     * @return static
     * @throws IntegrityConstraint
     */
    public function round(
        int $decimals = 0,
        ?RoundingMode $mode = null
    ): static
    {
        if ($this->getValue(NumberBase::Ten) == Number::INFINITY || $this->getValue(NumberBase::Ten) == Number::NEG_INFINITY) {
            return $this;
        }

        if ($mode) {
            $defaultMode = RoundingProvider::getRoundingMode();
            RoundingProvider::setRoundingMode($mode);
            $return = $this->setValue(RoundingProvider::round($this->getValue(NumberBase::Ten), $decimals));
            RoundingProvider::setRoundingMode($defaultMode);

            return $return;
        }

        return $this->setValue(RoundingProvider::round($this->getValue(NumberBase::Ten), $decimals));
    }

    /**
     * Truncate this number's value to the given number of decimal places, but keep the current scale setting of this number.
     *
     * @param int $decimals The number of decimal places to truncate to.
     * @return static
     * @throws IntegrityConstraint
     */
    public function truncate(
        int $decimals = 0
    ): static
    {
        if ($this->getValue(NumberBase::Ten) == Number::INFINITY || $this->getValue(NumberBase::Ten) == Number::NEG_INFINITY) {
            return $this;
        }

        $fractional = $this->getDecimalPart();
        $whole = $this->getWholePart();

        if ($this->sign) {
            $whole = '-'.$whole;
        }

        if ($decimals == 0) {
            $result = $whole;
        } else {
            $truncated = $whole.'.';

            if ($decimals > strlen($fractional)) {
                $fractional = str_pad($fractional, $decimals, '0');
            } else {
                $fractional = substr($fractional, 0, $decimals);
            }

            $truncated .= $fractional;

            $result = $truncated;
        }

        if ($this->isImaginary()) {
            $result .= 'i';
        }

        return $this->setValue($result, $decimals);
    }

    /**
     * Round this number's value to the given number of decimal places, and set this number's scale to that many digits.
     *
     * @param int $scale The number of decimal places to round to.
     * @param RoundingMode|null $mode The rounding mode to use for this operation. If null, will use the current default mode.
     * @return static
     */
    public function roundToScale(
        int $scale,
        ?RoundingMode $mode = null
    ): static
    {
        return $this->round($scale, $mode)->setScale($scale);
    }

    /**
     * Truncate this number's value to the given number of decimal places, and set this number's scale to that many digits.
     *
     * @param int $scale The number of decimal places to truncate to.
     * @return static
     * @throws IntegrityConstraint
     */
    public function truncateToScale(
        int $scale
    ): static
    {
        return $this->truncate($scale)->setScale($scale);
    }

    /**
     * Round to the next integer closest to positive infinity.
     *
     * @return static
     * @throws IntegrityConstraint
     */
    public function ceil(): static
    {
        return $this->round(0, RoundingMode::Ceil);
    }

    /**
     * Round to the next integer closest to negative infinity.
     *
     * @return static
     * @throws IntegrityConstraint
     */
    public function floor(): static
    {
        return $this->round(0, RoundingMode::Floor);
    }

    /**
     * The number of digits between the radix and the first non-zero digit in the decimal part.
     *
     * @return int
     */
    public function numberOfLeadingZeros(): int
    {
        $fractional = $this->getDecimalPart();

        $total = strlen($fractional);
        $fractional = ltrim($fractional, '0');

        return ($total - strlen($fractional));
    }

    /**
     * The number of digits (excludes the radix).
     *
     * @return int
     */
    public function numberOfTotalDigits(): int
    {
        return $this->numberOfDecimalDigits() + $this->numberOfIntDigits();
    }

    /**
     * The number of digits in the integer part.
     *
     * @return int
     */
    public function numberOfIntDigits(): int
    {
        return strlen($this->getWholePart());
    }

    /**
     * The number of digits in the decimal part.
     *
     * @return int
     */
    public function numberOfDecimalDigits(): int
    {
        return strlen($this->getDecimalPart());
    }

    /**
     * The number of digits in the decimal part, excluding leading zeros.
     *
     * @return int
     */
    public function numberOfSigDecimalDigits(): int
    {
        $decimalPart = $this->getDecimalPart();

        $sigDigits = ltrim($decimalPart, '0');

        return strlen($sigDigits);
    }

    /**
     * Returns the current value as an integer if it is within the max and min int values on the current system. Uses the
     * intval() function to convert the string to an integer type.
     *
     * @return int
     * @throws IncompatibleObjectState
     */
    public function asInt(): int
    {
        if ($this->isGreaterThan(PHP_INT_MAX) || $this->isLessThan(PHP_INT_MIN)) {
            throw new IncompatibleObjectState(
                'Cannot cast to integer when outside of integer range',
                'Continue using string values for very large magnitude numbers',
                'An attempt was made to cast to an integer when outside of the PHP integer range'
            );
        }

        return intval($this->getAsBaseTenRealNumber());
    }

    /**
     * Returns true if any non-zero digits exist in the decimal part.
     *
     * @return bool
     */
    public function isFloat(): bool
    {

        return (bool)ArithmeticProvider::compare($this->getDecimalPart(), '0');

    }

    /**
     * Returns the current value as a float if it is within the max and min float values on the current system. Uses the
     * (float) explicit cast to convert the string to a float type.
     *
     * @return float
     */
    public function asFloat(): float
    {
        return (float)$this->getAsBaseTenRealNumber();
    }

    /**
     * Returns only the decimal part of the number as a string.
     *
     * @return string
     */
    public function getDecimalPart(): string
    {
        return $this->value[1];
    }

    /**
     * Returns only the integer part of the number as a string.
     *
     * @return string
     */
    public function getWholePart(): string
    {
        return $this->value[0];
    }

}