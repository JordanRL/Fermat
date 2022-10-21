<?php

namespace Samsara\Fermat\Core\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\Currency;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\NumberFormat;
use Samsara\Fermat\Core\Enums\NumberGrouping;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\ArithmeticProvider;
use Samsara\Fermat\Core\Provider\BaseConversionProvider;
use Samsara\Fermat\Core\Provider\NumberFormatProvider;
use Samsara\Fermat\Core\Provider\RoundingProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Traits\ArithmeticSimpleTrait;
use Samsara\Fermat\Core\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Core\Types\Traits\Decimal\FormatterTrait;
use Samsara\Fermat\Core\Types\Traits\IntegerMathTrait;
use Samsara\Fermat\Core\Types\Traits\Decimal\ScaleTrait;
use Samsara\Fermat\Core\Types\Traits\InverseTrigonometrySimpleTrait;
use Samsara\Fermat\Core\Types\Traits\LogSimpleTrait;
use Samsara\Fermat\Core\Types\Traits\TrigonometrySimpleTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 *
 */
abstract class Decimal extends Number implements DecimalInterface
{

    protected NumberBase $base;

    use ArithmeticSimpleTrait;
    use ComparisonTrait;
    use IntegerMathTrait;
    use TrigonometrySimpleTrait;
    use InverseTrigonometrySimpleTrait;
    use LogSimpleTrait;
    use ScaleTrait;
    use FormatterTrait;

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

    public function asReal(): ImmutableDecimal|ImmutableFraction
    {
        return (new ImmutableDecimal($this->getAsBaseTenRealNumber(), $this->getScale()))->setMode($this->getMode());
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