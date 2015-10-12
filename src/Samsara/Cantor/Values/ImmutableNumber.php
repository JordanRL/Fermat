<?php

namespace Samsara\Cantor\Values;

use Samsara\Cantor\Bases;
use Samsara\Cantor\Numbers;
use Samsara\Cantor\Provider\BCProvider;
use Samsara\Cantor\Values\Base\BaseValue;
use Samsara\Cantor\Values\Base\NumberInterface;

class ImmutableNumber extends BaseValue implements NumberInterface
{

    /**
     * @param ImmutableNumber|int $value
     * @return int
     */
    public function compare($value)
    {
        $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value);

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

    public function convertToBase($base)
    {
        if ($base < 2 || $base > 36) {
            throw new \InvalidArgumentException('Cannot convert to a base less than 2 or greater than 36. '.$base.' given.');
        }

        if ($this->getRadixPos() !== false) {
            throw new \Exception('This number has a fractional part. Currently, only whole numbers may be base converted. Floating point numbers must be in base 10.');
        }

        $base = (int)$base;

        $oldBase = $this->getBase();

        if ($oldBase == $base) {
            return clone $this;
        }

        $whole = $this->getWholePart();
        $fractional = $this->getFractionalPart();

        if ($this->isNegative()) {
            $whole = substr($whole, 1);
        }

        // Whole part transformation

        $value = Bases::doBaseConvMath($whole, $oldBase, $base);

        // TODO: Fractional part transformation

        /**
        if ($fractional !== 0) {
            $value = $value.'.'.Bases::doBaseConvMath($fractional, $oldBase, $base);
        }
        /**/

        // Preserve sign

        if ($this->isNegative()) {
            $value = '-'.$value;
        }

        return new ImmutableNumber($value, $this->getPrecision(), $base);
    }

    public function modulo($mod)
    {
        $oldBase = $this->getBase();
        $number = $this->convertForModification();

        return (new ImmutableNumber(bcmod($number->getValue(), $mod), $number->getPrecision()))->convertToBase($oldBase);
    }

    public function abs()
    {
        $newValue = $this->absValue();

        return new ImmutableNumber($newValue, $this->getPrecision(), $this->getBase());
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

    public function round()
    {
        $fractional = $this->getFractionalPart();
        $whole = $this->getWholePart();

        $fractionalArr = str_split($fractional);

        if ($fractionalArr[0] >= 5) {
            $whole = BCProvider::add($whole, 1);
        }

        return new ImmutableNumber($whole, $this->getPrecision(), $this->getBase());
    }

    public function ceil()
    {
        $fractional = $this->getFractionalPart();
        $whole = $this->getWholePart();

        if ($fractional > 0) {
            $whole = BCProvider::add($whole, 1);
        }

        return new ImmutableNumber($whole, $this->getPrecision(), $this->getBase());
    }

    public function floor()
    {
        return new ImmutableNumber($this->getWholePart(), $this->getPrecision(), $this->getBase());
    }

    protected function convertForModification()
    {
        if ($this->getBase() == 10) {
            return $this;
        } else {
            $newNumber = $this->convertToBase(10);

            return $newNumber;
        }
    }

}