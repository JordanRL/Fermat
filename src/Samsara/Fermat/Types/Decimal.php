<?php

namespace Samsara\Fermat\Types;

use Riimu\Kit\BaseConversion\BaseConverter;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Types\Base\Interfaces\Characteristics\BaseConversionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Base\Number;
use Samsara\Fermat\Types\Traits\ArithmeticSimpleTrait;
use Samsara\Fermat\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Types\Traits\IntegerMathTrait;
use Samsara\Fermat\Types\Traits\Decimal\InverseTrigonometryTrait;
use Samsara\Fermat\Types\Traits\Decimal\LogTrait;
use Samsara\Fermat\Types\Traits\Decimal\ScaleTrait;
use Samsara\Fermat\Types\Traits\Decimal\TrigonometryTrait;

abstract class Decimal extends Number implements DecimalInterface, BaseConversionInterface
{

    protected $base;

    use ArithmeticSimpleTrait;
    use ComparisonTrait;
    use IntegerMathTrait;
    use TrigonometryTrait;
    use InverseTrigonometryTrait;
    use LogTrait;
    use ScaleTrait;

    public function __construct($value, $scale = null, $base = 10, bool $baseTenInput = false)
    {

        $this->base = $base;

        $value = (string)$value;

        if (strpos($value, 'i') !== false) {
            $this->imaginary = true;
            $value = str_replace('i', '', $value);
        } else {
            $this->imaginary = false;
        }

        if ($base !== 10 && !$baseTenInput) {
            $value = $this->convertValue($value, $base, 10);
        }

        $this->value = $this->translateValue($value);

        if (!is_null($scale)) {
            if ($scale > 2147483646) {
                throw new IntegrityConstraint(
                    'Scale cannot be larger than 2147483646',
                    'Use a scale of 2147483646 or less',
                    'Scale of any number cannot be calculated beyond 2147483646 digits'
                );
            }

            $this->scale = ($scale > strlen($this->getDecimalPart())) ? $scale : strlen($this->getDecimalPart());
        } else {
            $checkVal = $this->getDecimalPart();
            $checkVal = trim($checkVal,'0');

            if (strlen($checkVal) > 0) {
                $this->scale = (strlen($this->getDecimalPart()) > 10) ? strlen($this->getDecimalPart()) : 10;
            } else {
                $this->scale = (strlen($this->getWholePart()) > 10) ? strlen($this->getWholePart()) : 10;
            }
        }

        parent::__construct();

    }

    public function modulo($mod): DecimalInterface
    {
        return $this->setValue(bcmod($this->getAsBaseTenRealNumber(), $mod), $this->getScale(), $this->getBase());
    }

    protected function translateValue(string $value)
    {
        $value = trim($value);
        $valueArr = str_split($value);

        if ($valueArr[0] === '-') {
            $this->sign = true;
            $value = trim($value, '-');
        } else {
            $this->sign = false;
        }

        if (str_contains($value, '.')) {
            if (strpos($value, 'E')) {
                [$baseNum, $exp] = explode('E', $value);
                [$left, $right] = explode('.', $baseNum);

                if ($exp > 0) {
                    $exp -= strlen($right);
                    if ($exp >= 0) {
                        $right = str_pad($right, $exp - 1, '0').'.0';
                    } else {
                        $right = substr($right, 0, strlen($right) + $exp).'.'.substr($right, strlen($right) + $exp + 1);
                    }
                } else {
                    $exp += strlen($left);
                    if ($exp >= 0) {
                        $left = substr($left, 0, $exp).'.'.substr($left, $exp + 1);
                    } else {
                        $left = '0.'.str_pad($left, $exp, '0', STR_PAD_LEFT);
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

    protected function convertObject()
    {
        if ($this->getBase() === 10) {
            return $this->getAsBaseTenRealNumber();
        }

        return $this->convertValue($this->getAsBaseTenRealNumber(), 10, $this->getBase());
    }

    protected function convertValue(string $value, int $oldBase, int $newBase)
    {
        $converter = new BaseConverter($oldBase, $newBase);

        $converter->setPrecision($this->getScale());

        return $converter->convert($value);
    }

    /**
     * Returns the current base that the value is in.
     *
     * @return int
     */
    public function getBase(): int
    {
        return $this->base;
    }

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

    public function getValue($base = null): string
    {
        if (is_null($base)) {
            $value = $this->convertObject();
        } else {
            $value = $this->convertValue($this->getAsBaseTenRealNumber(), 10, $base);
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
     * @param $base
     * @return NumberInterface
     */
    public function convertToBase($base)
    {
        return $this->setValue($this->getValue(10), null, $base);
    }

    /**
     * Returns the current object as the absolute value of itself.
     *
     * @return DecimalInterface|NumberInterface
     */
    public function abs()
    {
        $newValue = $this->absValue();

        return $this->setValue($newValue, $this->getBase());
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

    public function __toString(): string
    {
        return parent::__toString(); // TODO: Change the autogenerated stub
    }

    /**
     * @param string $value
     * @param int $scale
     * @param int $base
     *
     * @return DecimalInterface
     */
    abstract protected function setValue(string $value, int $scale = null, int $base = 10); // TODO: Check usages for base correctness & preservation

    abstract public function continuousModulo($mod): DecimalInterface;

}