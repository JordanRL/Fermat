<?php

namespace Samsara\Fermat\Core\Types\Traits\Decimal;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\Currency;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\NumberFormat;
use Samsara\Fermat\Core\Enums\NumberGrouping;
use Samsara\Fermat\Core\Provider\NumberFormatProvider;
use Samsara\Fermat\Core\Types\Decimal;

/**
 * @package Samsara\Fermat\Core
 */
trait FormatterTrait
{
    protected NumberFormat $format = NumberFormat::English;
    protected NumberGrouping $grouping = NumberGrouping::Standard;

    /**
     * @param NumberFormat $format
     * @param NumberGrouping $grouping
     * @param string $value
     * @param int|null $scale
     * @param NumberBase $base
     * @param bool $baseTenInput
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
     * @param NumberFormat $format
     * @return Decimal
     */
    public function setFormat(NumberFormat $format): Decimal
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return NumberGrouping
     */
    public function getGrouping(): NumberGrouping
    {
        return $this->grouping;
    }

    /**
     * @param NumberGrouping $grouping
     * @return Decimal
     */
    public function setGrouping(NumberGrouping $grouping): Decimal
    {
        $this->grouping = $grouping;

        return $this;
    }

    /**
     * @return NumberFormat
     */
    public function getFormat(): NumberFormat
    {
        return $this->format;
    }

    /**
     * @param Currency $currency
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
     * Returns the current value formatted according to the settings in getGrouping() and getFormat()
     *
     * @param NumberBase|null $base
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
     * @param int|null $scale
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
     * @param int $scale
     * @param string $baseValue
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
     * @param NumberBase $base
     * @return string
     */
    abstract public function getValue(NumberBase $base): string;

    /**
     * @return int
     */
    abstract public function numberOfTotalDigits(): int;

    /**
     * @return int
     */
    abstract public function numberOfIntDigits(): int;

    /**
     * @return int
     */
    abstract public function numberOfDecimalDigits(): int;

    /**
     * @return int
     */
    abstract public function numberOfSigDecimalDigits(): int;

    /**
     * @return int
     */
    abstract public function numberOfLeadingZeros(): int;

    /**
     * @return string
     */
    abstract public function getWholePart(): string;

    /**
     * @return string
     */
    abstract public function getDecimalPart(): string;

    /**
     * @return bool
     */
    abstract public function isNegative(): bool;

    /**
     * @return bool
     */
    abstract public function isImaginary(): bool;
}