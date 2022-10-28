<?php

namespace Samsara\Fermat\Core\Types\Traits\Decimal;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\Currency;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\NumberFormat;
use Samsara\Fermat\Core\Enums\NumberGrouping;
use Samsara\Fermat\Core\Provider\NumberFormatProvider;

/**
 * @package Samsara\Fermat\Core
 */
trait FormatterTrait
{
    protected NumberFormat $format = NumberFormat::English;
    protected NumberGrouping $grouping = NumberGrouping::Standard;

    /**
     * Creates an instance of this class from a number string that has been formatted by the Fermat formatter.
     *
     * @param NumberFormat   $format
     * @param NumberGrouping $grouping
     * @param string         $value
     * @param int|null       $scale
     * @param NumberBase     $base
     * @param bool           $baseTenInput
     *
     * @return static
     */
    public static function createFromFormat(
        NumberFormat   $format,
        NumberGrouping $grouping,
        string         $value,
        int            $scale = null,
        NumberBase     $base = NumberBase::Ten,
        bool           $baseTenInput = true
    ): static
    {
        $value = str_replace(NumberFormatProvider::getDelimiterCharacter($format), '', $value);

        $value = str_replace(NumberFormatProvider::getRadixCharacter($format), '.', $value);

        return (new static($value, $scale, $base, $baseTenInput))->setFormat($format)->setGrouping($grouping);
    }

    /**
     * Returns a formatting string according to this number's current settings as a currency.
     *
     * @param Currency $currency The currency you want this number to appear in.
     *
     * @return string
     */
    public function getCurrencyValue(Currency $currency): string
    {
        return NumberFormatProvider::formatCurrency(
            $this->getValue(NumberBase::Ten),
            $currency,
            $this->getFormat(),
            $this->getGrouping()
        );
    }

    /**
     * Gets the current format setting of this number.
     *
     * @return NumberFormat
     */
    public function getFormat(): NumberFormat
    {
        return $this->format;
    }

    /**
     * Sets the format of this number for when a format export function is used.
     *
     * @param NumberFormat $format
     *
     * @return static
     */
    public function setFormat(NumberFormat $format): static
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Returns the current value formatted according to the settings in getGrouping() and getFormat()
     *
     * @param NumberBase|null $base The base you want the formatted number to be in.
     *
     * @return string
     * @throws IntegrityConstraint
     */
    public function getFormattedValue(?NumberBase $base = null): string
    {
        return NumberFormatProvider::formatNumber(
            $this->getValue($base),
            $this->getFormat(),
            $this->getGrouping()
        );
    }

    /**
     * Gets the current number grouping setting of this number.
     *
     * @return NumberGrouping
     */
    public function getGrouping(): NumberGrouping
    {
        return $this->grouping;
    }

    /**
     * Sets the number grouping of this number for when a format export function is used.
     *
     * @param NumberGrouping $grouping
     *
     * @return static
     */
    public function setGrouping(NumberGrouping $grouping): static
    {
        $this->grouping = $grouping;

        return $this;
    }

    /**
     * Returns the current value in scientific notation compatible with the way PHP coerces float values into strings.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     *
     * @return string
     */
    public function getScientificValue(?int $scale = null): string
    {
        $baseValue = $this->getValue(NumberBase::Ten);

        if (!is_null($scale)) {
            $baseValue = $this->trimmedScientificString($scale, $baseValue);

            if ($this->isNegative()) {
                $baseValue = '-' . $baseValue;
            }

            if ($this->isImaginary()) {
                $baseValue .= 'i';
            }
        }

        return NumberFormatProvider::formatScientific($baseValue);
    }

    /**
     * @param int    $scale
     * @param string $baseValue
     *
     * @return string
     */
    private function trimmedScientificString(int $scale, string $baseValue): string
    {
        if ($this->numberOfIntDigits() > $scale + 1) {
            $baseValue = substr($this->getWholePart(), 0, $scale + 1);
            $baseValue = str_pad($baseValue, $this->numberOfIntDigits(), '0');
        } elseif ($this->getWholePart() == '0' && $this->numberOfSigDecimalDigits() > $scale + 1) {
            $baseValue = trim($this->getDecimalPart(), '0');
            $baseValue = substr($baseValue, 0, $scale + 1);
            $baseValue = str_pad($baseValue, $this->numberOfLeadingZeros() + strlen($baseValue), '0', STR_PAD_LEFT);
            $baseValue = '0.' . $baseValue;
        } elseif ($this->numberOfTotalDigits() > $scale + 1) {
            $baseValue = $this->getWholePart()
                . '.'
                . substr($this->getDecimalPart(), 0, ($scale + 1) - $this->numberOfIntDigits());
        }

        return $baseValue;
    }

    /**
     * @return string
     */
    abstract public function getDecimalPart(): string;

    /**
     * @param NumberBase $base
     *
     * @return string
     */
    abstract public function getValue(NumberBase $base): string;

    /**
     * @return string
     */
    abstract public function getWholePart(): string;

    /**
     * @return bool
     */
    abstract public function isImaginary(): bool;

    /**
     * @return bool
     */
    abstract public function isNegative(): bool;

    /**
     * @return int
     */
    abstract public function numberOfDecimalDigits(): int;

    /**
     * @return int
     */
    abstract public function numberOfIntDigits(): int;

    /**
     * @return int
     */
    abstract public function numberOfLeadingZeros(): int;

    /**
     * @return int
     */
    abstract public function numberOfSigDecimalDigits(): int;

    /**
     * @return int
     */
    abstract public function numberOfTotalDigits(): int;
}