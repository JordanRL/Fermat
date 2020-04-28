<?php

namespace Samsara\Fermat\Types;

use Riimu\Kit\BaseConversion\BaseConverter;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Types\Base\Interfaces\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\BaseConversionInterface;
use Samsara\Fermat\Types\Base\Number;
use Samsara\Fermat\Types\Base\Interfaces\NumberInterface;
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

    public function __construct($value, $precision = null, $base = 10)
    {

        $this->base = $base;

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
        $value = trim(rtrim($value));

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

    /**
     * Returns the current base that the value is in.
     *
     * @return int
     */
    public function getBase(): int
    {
        return $this->base;
    }

    public function getValue()
    {
        $string = '';

        if (!$this->sign) {
            $string .= '-';
        }

        $string .= $this->value[1];

        if (Numbers::makeZero()->isLessThan($this->value[1])) {
            $string .= '.'.$this->value[1];
        }

        if ($this->isImaginary()) {
            $string .= 'i';
        }

        return $string;
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

        $thisBase = $this->convertForModification();
        $thatBase = $value->convertForModification();

        $thisValue = $this->getValue();
        $thatValue = $value->getValue();

        $precision = ($this->getPrecision() < $value->getPrecision()) ? $this->getPrecision() : $value->getPrecision();

        $comparison = ArithmeticProvider::compare($thisValue, $thatValue, $precision);

        $this->convertFromModification($thisBase);
        $value->convertFromModification($thatBase);

        return $comparison;
    }

    /**
     * Converts the object to a different base.
     *
     * @param $base
     * @return NumberInterface
     */
    public function convertToBase($base)
    {
        $converter = new BaseConverter($this->getBase(), $base);

        $converter->setPrecision($this->getPrecision());

        $value = $converter->convert($this->getValue());

        $this->base = $base;

        return $this->setValue($value);
    }

    /**
     * Takes an object and converts it to base10 so that math can be performed on it. Returns the native base if it is
     * something other than 10, and returns false (for a performance early exit in convertFromModification()) if the
     * native base is already 10.
     *
     * @return bool|int
     */
    public function convertForModification()
    {
        if ($this->getBase() == 10) {
            return false;
        } else {
            $oldBase = $this->getBase();
            $this->convertToBase(10);
            return $oldBase;
        }
    }

    /**
     * Returns an object to its native base after calculation.
     *
     * @param $oldBase
     * @return $this|NumberInterface
     */
    public function convertFromModification($oldBase)
    {
        if ($oldBase !== false) {
            return $this->convertToBase($oldBase);
        }

        return $this;
    }

    /**
     * Returns the current object as the absolute value of itself.
     *
     * @return DecimalInterface|NumberInterface
     */
    public function abs()
    {
        $newValue = $this->absValue();

        return $this->setValue($newValue);
    }

    /**
     * Returns the string of the absolute value of the current object.
     *
     * @return string
     */
    public function absValue(): string
    {
        if ($this->isNegative()) {
            return substr($this->getValue(), 1);
        } else {
            return $this->getValue();
        }
    }

    /**
     * @param string $value
     *
     * @return NumberInterface|DecimalInterface
     */
    abstract protected function setValue(string $value);

}