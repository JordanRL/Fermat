<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\BCProvider;
use Riimu\Kit\BaseConversion\BaseConverter;

abstract class Value
{
    protected $value;

    protected $precision;

    protected $base;

    public function __construct($value, $precision = null, $base = 10)
    {
        if (!is_null($precision)) {
            $this->precision = $precision;
        }

        $this->base = $base;
        $this->value = (string)$value;

        if ($this->base != 10 && $this->getRadixPos() !== false) {
            throw new \Exception('This number has a fractional part. Currently, only whole numbers may be base converted. Floating point numbers must be in base 10.');
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getPrecision()
    {
        return $this->precision;
    }

    public function add($num)
    {
        $num = Numbers::makeOrDont($this, $num, $this->getPrecision());

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = BCProvider::add($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    public function subtract($num)
    {
        $num = Numbers::makeOrDont($this, $num, $this->getPrecision());

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = BCProvider::subtract($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    public function multiply($num)
    {
        $num = Numbers::makeOrDont($this, $num, $this->getPrecision());

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = BCProvider::multiply($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    /**
     * Note about precision: it uses the smaller of the two precisions (significant figures).
     *
     * @param $num
     * @return mixed
     */
    public function divide($num)
    {
        $num = Numbers::makeOrDont($this, $num, $this->getPrecision());

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $precision = ($this->getPrecision() > $num->getPrecision()) ? $num->getPrecision() : $this->getPrecision();

        $value = BCProvider::divide($this->getValue(), $num->getValue(), $precision);

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    public function exp($num)
    {
        $num = Numbers::makeOrDont($this, $num, $this->getPrecision());

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = BCProvider::exp($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

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

    public function convertFromModification($oldBase)
    {
        if ($oldBase !== false) {
            return $this->convertToBase($oldBase);
        }

        return $this;
    }

    public function abs()
    {
        $newValue = $this->absValue();

        return $this->setValue($newValue);
    }

    public function absValue()
    {
        if ($this->isNegative()) {
            return substr($this->getValue(), 1);
        } else {
            return $this->getValue();
        }
    }

    public function isNegative()
    {
        if (strpos($this->getValue(), '-') === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isPositive()
    {
        return !$this->isNegative();
    }

    public function round($decimals = 0)
    {
        $fractional = $this->getFractionalPart();
        $whole = $this->getWholePart();

        $fractionalArr = str_split($fractional);

        if ($fractionalArr[$decimals] >= 5) {
            if ($decimals == 0) {
                $rounded = BCProvider::add($whole, 1);
            } else {
                $rounded = $whole.'.';
                for ($i = 0; $i < $decimals; $i++) {
                    if (($i+1) == $decimals) {
                        $rounded .= ($fractionalArr[$i]+1);
                    } else {
                        $rounded .= $fractionalArr[$i];
                    }
                }
            }
        } else {
            if ($decimals == 0) {
                $rounded = $whole;
            } else {
                $rounded = $whole.'.';
                for ($i = 0; $i < $decimals; $i++) {
                    $rounded .= $fractionalArr[$i];
                }
            }
        }

        return $this->setValue($rounded);
    }

    public function ceil()
    {
        $fractional = $this->getFractionalPart();
        $whole = $this->getWholePart();

        if ($fractional > 0) {
            $whole = BCProvider::add($whole, 1);
        }

        return $this->setValue($whole);
    }

    public function floor()
    {
        return $this->setValue($this->getWholePart());
    }

    /**
     * @param NumberInterface|int|float|string $value
     * @return int
     */
    public function compare($value)
    {
        $value = Numbers::makeOrDont($this, $value, $this->getPrecision());

        if ($this->getBase() != 10) {
            $thisValue = $this->convertToBase(10)->getValue();
        } else {
            $thisValue = $this->getValue();
        }

        if ($value->getBase() != 10) {
            $thatValue = $value->convertToBase(10)->getValue();
        } else {
            $thatValue = $value->getValue();
        }

        $scale = ($this->getPrecision() < $value->getPrecision()) ? $this->getPrecision() : $value->getPrecision();

        return BCProvider::compare($thisValue, $thatValue, $scale);
    }

    /**
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

    public function __toString()
    {
        return $this->getValue();
    }

    protected function getRadixPos()
    {
        return strpos($this->getValue(), '.');
    }

    protected function getFractionalPart()
    {
        $radix = $this->getRadixPos();
        if ($radix !== false) {
            return substr($this->getValue(), $radix+1);
        } else {
            return 0;
        }
    }

    protected function getWholePart()
    {
        $radix = $this->getRadixPos();
        if ($radix !== false) {
            return substr($this->getValue(), 0, $radix);
        } else {
            return $this->getValue();
        }
    }

    abstract protected function setValue($value);

}