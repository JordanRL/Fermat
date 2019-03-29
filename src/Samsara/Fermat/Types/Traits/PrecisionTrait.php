<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Fermat\Provider\ArithmeticProvider;

trait PrecisionTrait
{

    protected $precision;

    public function getPrecision()
    {
        return $this->precision;
    }

    public function round($decimals = 0)
    {
        $fractional = $this->getDecimalPart();
        $whole = $this->getWholePart();

        $fractionalArr = str_split($fractional);

        if (!isset($fractionalArr[$decimals])) {
            return $this;
        } else {
            if ($decimals == 0) {
                if ($fractionalArr[$decimals] >= 5) {
                    return $this->setValue($whole)->add(1);
                } else {
                    return $this->setValue($whole);
                }
            } else {
                if ($fractionalArr[$decimals] >= 5) {
                    $fractionalArr = $this->reduceDecimals($fractionalArr, $decimals-1, 1);
                }

                if (is_null($fractionalArr)) {
                    return $this->setValue($whole)->add(1);
                }

                $rounded = $whole.'.';

                for ($i = 0;$i < $decimals;$i++) {
                    $rounded .= $fractionalArr[$i];
                }

                return $this->setValue($rounded);
            }
        }
    }

    public function truncate($decimals = 0)
    {
        $fractional = $this->getDecimalPart();
        $whole = $this->getWholePart();

        if ($decimals == 0) {
            return $this->setValue($whole);
        } else {
            $truncated = $whole.'.';

            if ($decimals > strlen($fractional)) {
                $fractional = str_pad($fractional, $decimals, '0');
            } else {
                $fractional = substr($fractional, 0, $decimals);
            }

            $truncated .= $fractional;

            return $this->setValue($truncated);
        }
    }

    public function roundToPrecision($precision)
    {

        $this->precision = $precision;

        return $this->round($precision);

    }

    public function truncateToPrecision($precision)
    {

        $this->precision = $precision;

        return $this->truncate($precision);

    }

    public function ceil()
    {
        $fractional = $this->getDecimalPart();
        $whole = $this->getWholePart();

        if ($fractional > 0) {
            $whole = ArithmeticProvider::add($whole, 1, $this->getPrecision());
        }

        return $this->setValue($whole);
    }

    public function floor()
    {
        return $this->setValue($this->getWholePart());
    }

    public function numberOfLeadingZeros()
    {
        $fractional = $this->getDecimalPart();

        $total = strlen($fractional);
        $fractional = ltrim($fractional, '0');

        return $total-strlen($fractional);
    }

    public function asInt()
    {

        if ($this->isGreaterThan(PHP_INT_MAX) || $this->isLessThan(PHP_INT_MIN)) {
            throw new IncompatibleObjectState('Cannot export number as integer because it is out of range');
        }

        return intval($this->getValue());

    }

    protected function getRadixPos()
    {
        return strpos($this->value, '.');
    }

    protected function reduceDecimals(array $decimalArray, $pos, $add)
    {

        if ($add == 1) {
            if ($decimalArray[$pos] == 9) {
                $decimalArray[$pos] = 0;

                if ($pos == 0) {
                    return null;
                } else {
                    return $this->reduceDecimals($decimalArray, $pos-1, $add);
                }
            } else {
                $decimalArray[$pos] += 1;
            }
        }

        return $decimalArray;

    }

    protected function getDecimalPart()
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

}