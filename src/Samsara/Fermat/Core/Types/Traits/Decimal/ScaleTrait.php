<?php

namespace Samsara\Fermat\Core\Types\Traits\Decimal;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\ArithmeticProvider;
use Samsara\Fermat\Core\Provider\RoundingProvider;
use Samsara\Fermat\Core\Provider\TrigonometryProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
trait ScaleTrait
{

    protected int $scale;

    /**
     * @return int
     */
    public function getScale(): int
    {
        return $this->scale;
    }

    /**
     * @param int $scale
     * @return static
     */
    protected function setScale(int $scale): static
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * @param int $decimals
     * @param RoundingMode|null $mode
     * @return ImmutableDecimal|MutableDecimal|static
     * @throws IntegrityConstraint
     */
    public function round(
        int $decimals = 0,
        ?RoundingMode $mode = null
    ): ImmutableDecimal|MutableDecimal|static
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
     * @param int $decimals
     * @return ImmutableDecimal|MutableDecimal|static
     * @throws IntegrityConstraint
     */
    public function truncate(
        int $decimals = 0
    ): ImmutableDecimal|MutableDecimal|static
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
     * @param int $scale
     * @param RoundingMode|null $mode
     * @return ImmutableDecimal|MutableDecimal|static
     */
    public function roundToScale(
        int $scale,
        ?RoundingMode $mode = null
    ): ImmutableDecimal|MutableDecimal|static
    {
        return $this->round($scale, $mode)->setScale($scale);
    }

    /**
     * @param int $scale
     * @return ImmutableDecimal|MutableDecimal|static
     * @throws IntegrityConstraint
     */
    public function truncateToScale(
        int $scale
    ): ImmutableDecimal|MutableDecimal|static
    {
        return $this->truncate($scale)->setScale($scale);
    }

    /**
     * @return ImmutableDecimal|MutableDecimal|static
     * @throws IntegrityConstraint
     */
    public function ceil(): ImmutableDecimal|MutableDecimal|static
    {
        return $this->round(0, RoundingMode::Ceil);
    }

    /**
     * @return ImmutableDecimal|MutableDecimal|static
     * @throws IntegrityConstraint
     */
    public function floor(): ImmutableDecimal|MutableDecimal|static
    {
        return $this->round(0, RoundingMode::Floor);
    }

    /**
     * The number of digits between the radix and the for non-zero digit in the decimal part.
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
     * Returns the current value as an integer if it is within the max a min int values on the current system. Uses the
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
     * @return bool
     */
    public function isFloat(): bool
    {

        return (bool)ArithmeticProvider::compare($this->getDecimalPart(), '0');

    }

    /**
     * @return float
     */
    public function asFloat(): float
    {
        return (float)$this->getAsBaseTenRealNumber();
    }

    /**
     * @return string
     */
    public function getDecimalPart(): string
    {
        return $this->value[1];
    }

    /**
     * @return string
     */
    public function getWholePart(): string
    {
        return $this->value[0];
    }

}