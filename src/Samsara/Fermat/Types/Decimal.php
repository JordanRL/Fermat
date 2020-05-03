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
use Samsara\Fermat\Types\Traits\ArithmeticTrait;
use Samsara\Fermat\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Types\Traits\IntegerMathTrait;
use Samsara\Fermat\Types\Traits\InverseTrigonometryTrait;
use Samsara\Fermat\Types\Traits\LogTrait;
use Samsara\Fermat\Types\Traits\PrecisionTrait;
use Samsara\Fermat\Types\Traits\TrigonometryTrait;

abstract class Decimal extends Number implements DecimalInterface, BaseConversionInterface
{

    protected $base;

    use ArithmeticTrait;
    use ComparisonTrait;
    use IntegerMathTrait;
    use TrigonometryTrait;
    use InverseTrigonometryTrait;
    use LogTrait;
    use PrecisionTrait;

    public function __construct($value, $precision = null, $base = 10, bool $baseTenInput = false)
    {

        $this->base = $base;

        if ($base != 10 && !$baseTenInput) {
            $value = $this->convertValue($value, $base, 10);
        }

        $value = (string)$value;
        $this->value = $this->translateValue($value);

        if (!is_null($precision)) {
            if ($precision > 2147483646) {
                throw new IntegrityConstraint(
                    'Precision cannot be larger than 2147483646',
                    'Use a precision of 2147483646 or less',
                    'Precision of any number cannot be calculated beyond 2147483646 digits'
                );
            }

            $this->precision = ($precision > strlen($this->getDecimalPart())) ? $precision : strlen($this->getDecimalPart());
        } else {
            if ($this->getDecimalPart() === 0) {
                $this->precision = (strlen($this->getWholePart()) > 10) ? strlen($this->getWholePart()) : 10;
            } else {
                $this->precision = (strlen($this->getDecimalPart()) > 10) ? strlen($this->getDecimalPart()) : 10;
            }
        }

        parent::__construct($value);

    }

    protected function translateValue(string $value)
    {
        $value = trim($value);

        if ($value[0] == '-') {
            $this->sign = false;
        } else {
            $this->sign = true;
        }

        if (strpos($value, '.') !== false) {
            list($wholePart, $decimalPart) = explode('.', $value);

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
        if ($this->getBase() == 10) {
            return $this->getAsBaseTenRealNumber();
        } else {
            return $this->convertValue($this->getAsBaseTenRealNumber(), 10, $this->getBase());
        }
    }

    protected function convertValue(string $value, int $oldBase, int $newBase)
    {
        $converter = new BaseConverter($oldBase, $newBase);

        $converter->setPrecision($this->getPrecision());

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

    public function getAsBaseTenRealNumber():string
    {
        $string = '';

        if (!$this->sign) {
            $string .= '-';
        }

        $string .= $this->value[1];

        if (Numbers::makeZero()->isLessThan($this->value[1])) {
            $string .= '.'.$this->value[1];
        }

        return $string;
    }

    public function getValue($base = null): string // TODO: Check usages to see if it should be replaced with rawString()
    {
        $value = $this->convertObject();

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
     */
    public function compare($value): int
    {
        $value = Numbers::makeOrDont($this, $value, $this->getPrecision());

        // TODO: Handle comparison of imaginary numbers
        if ($value instanceof FractionInterface) {
            $value = $value->asDecimal($this->getPrecision());
        }
        $thisValue = $this->getAsBaseTenRealNumber();
        $thatValue = $value->getAsBaseTenRealNumber();

        $precision = ($this->getPrecision() < $value->getPrecision()) ? $this->getPrecision() : $value->getPrecision();

        return ArithmeticProvider::compare($thisValue, $thatValue, $precision);
    }

    /**
     * Converts the object to a different base.
     *
     * @param $base
     * @return NumberInterface
     */
    public function convertToBase($base)
    {
        return $this->setValue($this->getValue($base), null, $base);
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
            $this->sign = true;
        }

        $value = $this->getValue();

        if ($makeNegative) {
            $this->sign = false;
        }

        if ($makeImaginary) {
            $this->imaginary = true;
        }

        return $value;
    }

    public function __toString(): string
    {
        return parent::__toString(); // TODO: Change the autogenerated stub
    }

    /**
     * @param string $value
     * @param int $precision
     * @param int $base
     *
     * @return DecimalInterface
     */
    abstract protected function setValue(string $value, int $precision = null, int $base = 10); // TODO: Check usages for base correctness & preservation

}