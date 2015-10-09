<?php

namespace Samsara\Cantor\Values;

use Samsara\Cantor\Values\Base\BaseValue;
use Samsara\Cantor\Values\Base\NumberInterface;

class ImmutableNumber extends BaseValue implements NumberInterface
{

    /**
     * @param ImmutableNumber $value
     * @return int
     */
    public function compare($value)
    {
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

        return bccomp($thisValue, $thatValue, $scale);
    }

    public function convertToBase($base)
    {
        if ($base < 2 || $base > 36) {
            throw new \InvalidArgumentException('Cannot convert to a base less than 2 or greater than 36. '.$base.' given.');
        }

        $value = $this->getValue();
        $oldBase = $this->getBase();

        if ($oldBase == $base) {
            return $this;
        }

        // TODO: convert values to new base

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


    }

    public function ceil()
    {

    }

    public function floor()
    {

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