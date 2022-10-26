<?php

namespace Samsara\Fermat\Core\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\ArithmeticProvider;
use Samsara\Fermat\Core\Provider\BaseConversionProvider;
use Samsara\Fermat\Core\Provider\RoundingProvider;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Traits\SimpleArithmeticTrait;
use Samsara\Fermat\Core\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Core\Types\Traits\Decimal\FormatterTrait;
use Samsara\Fermat\Core\Types\Traits\IntegerMathTrait;
use Samsara\Fermat\Core\Types\Traits\Decimal\ScaleTrait;
use Samsara\Fermat\Core\Types\Traits\SimpleInverseTrigonometryTrait;
use Samsara\Fermat\Core\Types\Traits\SimpleLogTrait;
use Samsara\Fermat\Core\Types\Traits\SimpleTrigonometryTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
abstract class Decimal extends Number
{

    protected NumberBase $base;

    use SimpleArithmeticTrait;
    use ComparisonTrait;
    use IntegerMathTrait;
    use SimpleTrigonometryTrait;
    use SimpleInverseTrigonometryTrait;
    use SimpleLogTrait;
    use ScaleTrait;
    use FormatterTrait;

    /**
     * @param Decimal|string|int|float $value The value to create this number with. Integers and floats are used as real numbers.
     * @param int|null $scale The number of digits you want to return from math operations. Leave null to autodetect based on input.
     * @param NumberBase $base The base you want this number to have any time the value is retrieved.
     * @param bool $baseTenInput If true, the $value argument will be treated as base 10 regardless of $base. If false, the $value will be interpreted as being in $base.
     * @throws IntegrityConstraint
     */
    final public function __construct(Decimal|string|int|float $value, int $scale = null, NumberBase $base = NumberBase::Ten, bool $baseTenInput = true)
    {

        $this->base = $base;

        $value = $value instanceof Decimal ? $value->getValue(NumberBase::Ten) : (string)$value;

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
     * @param $mod
     * @return static
     */
    public function modulo($mod): static
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
            $value = ltrim($value, '0');
            $value = empty($value) ? '0' : $value;

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
     * Returns the current value as a string in base 10, converted to a real number. If the number is imaginary, the i is
     * simply not printed. If the number is complex, then the absolute value is returned.
     *
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
     * Returns a new instance of this object with a base ten real number.
     *
     * @return ImmutableDecimal
     */
    public function asReal(): ImmutableDecimal
    {
        return (new ImmutableDecimal(
            $this->getAsBaseTenRealNumber(),
            $this->getScale()
        ))->setMode($this->getMode());
    }

    /**
     * Returns a new instance of this object with a base ten imaginary number.
     *
     * @return ImmutableDecimal
     */
    public function asImaginary(): ImmutableDecimal
    {
        return (new ImmutableDecimal(
            $this->getAsBaseTenRealNumber().'i',
            $this->getScale()
        ))->setMode($this->getMode());
    }

    /**
     * @return ImmutableComplexNumber
     * @throws IntegrityConstraint
     */
    public function asComplex(): ImmutableComplexNumber
    {
        if ($this->isReal()) {
            return new ImmutableComplexNumber($this->asReal(), Numbers::makeZero());
        }

        return new ImmutableComplexNumber(Numbers::makeZero(), $this->asImaginary());
    }

    /**
     * Returns the current value as a string.
     *
     * @param NumberBase|null $base If provided, will return the value in the provided base, regardless of the object's base setting.
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
     * Returns the sort compare integer (signum) (-1, 0, 1) for the two numbers.
     *
     * @param Number|int|float|string $value
     * @return int
     * @throws IntegrityConstraint
     */
    public function compare(Number|int|float|string $value): int
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

        if ($value instanceof Fraction) {
            $value = $value->asDecimal($this->getScale());
        }
        $thisValue = $this->getAsBaseTenRealNumber();
        $thatValue = $value->getAsBaseTenRealNumber();

        $scale = ($this->getScale() < $value->getScale()) ? $this->getScale() : $value->getScale();

        return ArithmeticProvider::compare($thisValue, $thatValue, $scale);
    }

    /**
     * Changes the base setting for this number.
     *
     * @param NumberBase $base
     * @return static
     */
    public function setBase(NumberBase $base): static
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Returns the current object as the absolute value of itself.
     *
     * @return ImmutableDecimal|MutableDecimal
     */
    public function abs(): ImmutableDecimal|MutableDecimal
    {
        $newValue = $this->absValue();

        return $this->setValue($newValue, $this->getScale(), $this->getBase());
    }

    /**
     * @param Decimal $num
     * @return float|int
     */
    protected static function translateToNative(Decimal $num): float|int
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

        $value = $this->getValue(NumberBase::Ten);

        if ($makeNegative) {
            $this->sign = true;
        }

        if ($makeImaginary) {
            $this->imaginary = true;
        }

        return $value;
    }

    /**
     * Returns true if the number is complex, false if the number is real or imaginary.
     *
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
     * @return ImmutableDecimal|MutableDecimal|static
     */
    abstract protected function setValue(
        string $value,
        ?int $scale = null,
        ?NumberBase $base = null,
        bool $setToNewBase = false
    ): ImmutableDecimal|MutableDecimal|static;

    /**
     * Returns the current number mod input number, including the decimal portion of the modulo.
     *
     * @param Decimal|string|int|float $mod The modulo to use
     * @return static
     */
    abstract public function continuousModulo(Decimal|string|int|float $mod): static;

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