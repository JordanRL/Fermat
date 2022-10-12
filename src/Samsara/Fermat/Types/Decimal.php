<?php

namespace Samsara\Fermat\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\Currency;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Enums\NumberFormat;
use Samsara\Fermat\Enums\NumberGrouping;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Provider\BaseConversionProvider;
use Samsara\Fermat\Provider\NumberFormatProvider;
use Samsara\Fermat\Provider\RoundingProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Base\Number;
use Samsara\Fermat\Types\Traits\ArithmeticSimpleTrait;
use Samsara\Fermat\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Types\Traits\IntegerMathTrait;
use Samsara\Fermat\Types\Traits\Decimal\ScaleTrait;
use Samsara\Fermat\Types\Traits\InverseTrigonometrySimpleTrait;
use Samsara\Fermat\Types\Traits\LogSimpleTrait;
use Samsara\Fermat\Types\Traits\TrigonometrySimpleTrait;

/**
 *
 */
abstract class Decimal extends Number implements DecimalInterface
{

    protected NumberFormat $format = NumberFormat::English;
    protected NumberGrouping $grouping = NumberGrouping::Standard;
    protected NumberBase $base;

    use ArithmeticSimpleTrait;
    use ComparisonTrait;
    use IntegerMathTrait;
    use TrigonometrySimpleTrait;
    use InverseTrigonometrySimpleTrait;
    use LogSimpleTrait;
    use ScaleTrait;

    /**
     * @param $value
     * @param int|null $scale
     * @param NumberBase $base
     * @param bool $baseTenInput
     * @throws IntegrityConstraint
     */
    public function __construct($value, int $scale = null, NumberBase $base = NumberBase::Ten, bool $baseTenInput = true)
    {

        $this->base = $base;

        $value = $value instanceof NumberInterface ? $value->getValue(NumberBase::Ten) : (string)$value;

        if (str_contains($value, 'i')) {
            $this->imaginary = true;
            $value = str_replace('i', '', $value);
        } else {
            $this->imaginary = false;
        }

        if ($base != NumberBase::Ten && !$baseTenInput) {
            $value = BaseConversionProvider::convertStringToBaseTen($value, $base);
        }

        $this->value = $this->translateValue($value);

        $this->scale = $this->determineScale($this->getDecimalPart(), $scale);

        if ($this->scale < $this->numberOfDecimalDigits()) {
            $this->value = $this->translateValue(RoundingProvider::round($this->getAsBaseTenRealNumber(), $this->scale));
        }

        parent::__construct();

    }

    /**
     * @param NumberFormat $format
     * @param NumberGrouping $grouping
     * @param string $value
     * @param int|null $scale
     * @param NumberBase $base
     * @param bool $baseTenInput
     * @return static
     * @throws IntegrityConstraint
     */
    public static function createFromFormat(
        NumberFormat $format,
        NumberGrouping $grouping,
        string $value,
        int $scale = null,
        NumberBase $base = NumberBase::Ten,
        bool $baseTenInput = true
    ): static
    {
        $value = str_replace(NumberFormatProvider::getDelimiterCharacter($format), '', $value);

        $value = str_replace(NumberFormatProvider::getRadixCharacter($format), '.', $value);

        return (new static($value, $scale, $base, $baseTenInput))->setFormat($format)->setGrouping($grouping);
    }

    /**
     * @param NumberFormat $format
     * @return $this
     */
    public function setFormat(NumberFormat $format): self
    {
        $this->format = $format;

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
     * @param NumberGrouping $grouping
     * @return $this
     */
    public function setGrouping(NumberGrouping $grouping): self
    {
        $this->grouping = $grouping;

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
     * @param $mod
     * @return DecimalInterface
     */
    public function modulo($mod): DecimalInterface
    {
        return $this->setValue(bcmod($this->getAsBaseTenRealNumber(), $mod), $this->getScale(), $this->getBase());
    }

    /**
     * @param string $value
     * @return array
     */
    protected function translateValue(string $value): array
    {
        $value = trim($value);

        if (str_starts_with($value, '-')) {
            $this->sign = true;
            $value = trim($value, '-');
        } else {
            $this->sign = false;
        }

        if (str_contains($value, '.')) {
            if (strpos($value, 'E')) {
                [$baseNum, $exp] = explode('E', $value);
                [$left, $right] = explode('.', $baseNum);
                $exp = intval($exp);

                if ($exp > 0) {
                    $exp -= strlen($right);
                    if ($exp >= 0) {
                        $right = str_pad($right, $exp - 1, '0').'.0';
                    } else {
                        $right = substr($right, 0, strlen($right) + abs($exp)).'.'.substr($right, strlen($right) + abs($exp) + 1);
                    }
                } else {
                    $exp += strlen($left);
                    if ($exp >= 0) {
                        $left = substr($left, 0, $exp).'.'.substr($left, $exp + 1);
                    } else {
                        $left = '0.'.str_pad($left, abs($exp)+1, '0', STR_PAD_LEFT);
                    }
                }
                $value = $left.$right;
            }

            [$wholePart, $decimalPart] = explode('.', $value);

            $resultValue = [
                $wholePart,
                $decimalPart
            ];
        } else {
            $resultValue = [
                $value,
                '0'
            ];
        }

        return $resultValue;
    }

    /**
     * @return string
     * @throws IntegrityConstraint
     */
    protected function getAsBaseConverted(): string
    {
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this);
        $converted = BaseConversionProvider::convertFromBaseTen($num, $this->getBase());

        $converted = rtrim($converted, '0');
        return rtrim($converted, '.');
    }

    /**
     * Returns the current base that the value is in.
     *
     * @return NumberBase
     */
    public function getBase(): NumberBase
    {
        return $this->base;
    }

    /**
     * @return string
     */
    public function getAsBaseTenRealNumber(): string
    {
        $string = '';

        if ($this->sign) {
            $string .= '-';
        }

        $string .= $this->value[0];

        $decimalVal = trim($this->value[1], '0');

        if (strlen($decimalVal) > 0) {
            $string .= '.'.rtrim($this->value[1], '0');
        }

        return $string;
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
     * @param int|null $scale
     * @return string
     */
    public function getScientificValue(?int $scale = null): string
    {
        $baseValue = $this->getValue(NumberBase::Ten);

        if (!is_null($scale)) {
            if ($this->numberOfIntDigits() > $scale+1) {
                $baseValue = substr($this->getWholePart(), 0, $scale+1);
                $baseValue = str_pad($baseValue, $this->numberOfIntDigits(), '0');
            } elseif ($this->getWholePart() == '0' && $this->numberOfSigDecimalDigits() > $scale+1) {
                $baseValue = trim($this->getDecimalPart(), '0');
                $baseValue = substr($baseValue, 0, $scale+1);
                $baseValue = str_pad($baseValue, $this->numberOfLeadingZeros()+strlen($baseValue), '0', STR_PAD_LEFT);
                $baseValue = '0.'.$baseValue;
            } elseif ($this->numberOfTotalDigits() > $scale+1) {
                $baseValue = $this->getWholePart()
                    .'.'
                    .substr($this->getDecimalPart(), 0, ($scale+1)-$this->numberOfIntDigits());
            }

            if ($this->isNegative()) {
                $baseValue = '-'.$baseValue;
            }

            if ($this->isImaginary()) {
                $baseValue .= 'i';
            }
        }

        return NumberFormatProvider::formatScientific($baseValue);
    }

    /**
     * @param NumberBase|null $base
     * @return string
     * @throws IntegrityConstraint
     */
    public function getValue(?NumberBase $base = null): string
    {
        if (is_null($base) && $this->getBase() != NumberBase::Ten) {
            $value = $this->getAsBaseConverted();
        } elseif (!is_null($base) && $base != NumberBase::Ten) {
            $value = BaseConversionProvider::convertFromBaseTen($this, $base);
        } else {
            $value = $this->getAsBaseTenRealNumber();
        }

        if ($this->isImaginary()) {
            $value .= 'i';
        }

        return $value;
    }

    /**
     * Returns the sort compare integer (-1, 0, 1) for the two numbers.
     *
     * @param NumberInterface|int|float|string $value
     * @return int
     * @throws IntegrityConstraint
     */
    public function compare($value): int
    {
        $value = Numbers::makeOrDont($this, $value, $this->getScale());

        if ($this->getValue(NumberBase::Ten) == Number::INFINITY) {
            return match ($value->getValue(NumberBase::Ten)) {
                'INF' => 0,
                default => 1
            };
        } elseif ($this->getValue(NumberBase::Ten) == Number::NEG_INFINITY) {
            return match ($value->getValue(NumberBase::Ten)) {
                '-INF' => 0,
                default => -1
            };
        }

        if ($value->getValue(NumberBase::Ten) == Number::INFINITY) {
            return 1;
        } elseif ($value->getValue(NumberBase::Ten) == Number::NEG_INFINITY) {
            return -1;
        }

        // TODO: Handle comparison of imaginary numbers
        if ($value instanceof FractionInterface) {
            $value = $value->asDecimal($this->getScale());
        }
        $thisValue = $this->getAsBaseTenRealNumber();
        $thatValue = $value->getAsBaseTenRealNumber();

        $scale = ($this->getScale() < $value->getScale()) ? $this->getScale() : $value->getScale();

        return ArithmeticProvider::compare($thisValue, $thatValue, $scale);
    }

    /**
     * Converts the object to a different base.
     *
     * @param NumberBase $base
     * @return DecimalInterface|NumberInterface
     */
    public function setBase(NumberBase $base): DecimalInterface|NumberInterface
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Returns the current object as the absolute value of itself.
     *
     * @return DecimalInterface|NumberInterface
     */
    public function abs(): DecimalInterface|NumberInterface
    {
        $newValue = $this->absValue();

        return $this->setValue($newValue, $this->getScale(), $this->getBase());
    }

    /**
     * @param DecimalInterface $num
     * @return float|int
     */
    protected static function translateToNative(DecimalInterface $num): float|int
    {
        return ($num->isInt() ? $num->asInt() : $num->asFloat());
    }

    /**
     * Returns the string of the absolute value of the current object.
     *
     * @return string
     */
    public function absValue(): string
    {
        $makeImaginary = false;
        $makeNegative = false;

        if ($this->isImaginary()) {
            $makeImaginary = true;
            $this->imaginary = false;
        }

        if ($this->isNegative()) {
            $makeNegative = true;
            $this->sign = false;
        }

        $value = $this->getValue();

        if ($makeNegative) {
            $this->sign = true;
        }

        if ($makeImaginary) {
            $this->imaginary = true;
        }

        return $value;
    }

    /**
     * @return bool
     */
    public function isComplex(): bool
    {
        return false;
    }

    /**
     * @param string $value
     * @param int|null $scale
     * @param NumberBase|null $base
     * @param bool $setToNewBase
     * @return DecimalInterface
     */
    abstract protected function setValue(string $value, ?int $scale = null, ?NumberBase $base = null, bool $setToNewBase = false): DecimalInterface; // TODO: Check usages for base correctness & preservation

    /**
     * @param NumberInterface|string|int|float $mod
     * @return DecimalInterface
     */
    abstract public function continuousModulo(NumberInterface|string|int|float $mod): DecimalInterface;

    /**
     * @param string $decimalPart
     * @param int|null $scale
     * @return int
     * @throws IntegrityConstraint
     */
    protected function determineScale(string $decimalPart, ?int $scale = null): int
    {
        if (!is_null($scale)) {
            if ($scale > 2147483646) {
                throw new IntegrityConstraint(
                    'Scale cannot be larger than 2147483646',
                    'Use a scale of 2147483646 or less',
                    'Scale of any number cannot be calculated beyond 2147483646 digits'
                );
            }

            $calcScale = $scale;
        } else {
            $calcScale = (strlen($decimalPart) > 10) ? strlen($decimalPart) : 10;
        }

        return $calcScale;
    }

}