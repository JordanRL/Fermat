<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
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

        $iPart = '';

        if ($this->isImaginary()) {
            $iPart = 'i';
        }

        if ($this->sign == true) {
            $whole = '-'.$whole;
        }

        $fractionalArr = str_split($fractional);

        if (!isset($fractionalArr[$decimals])) {
            return $this;
        } else {
            if ($decimals == 0) {
                if ($fractionalArr[$decimals] >= 5) {
                    return $this->setValue($whole.$iPart)->add(1);
                } else {
                    return $this->setValue($whole.$iPart);
                }
            } else {
                if ($fractionalArr[$decimals] >= 5) {
                    $fractionalArr = $this->reduceDecimals($fractionalArr, $decimals-1, 1);
                }

                if (is_null($fractionalArr)) {
                    return $this->setValue($whole.$iPart)->add(1);
                }

                $rounded = $whole.'.';

                for ($i = 0;$i < $decimals;$i++) {
                    $rounded .= $fractionalArr[$i];
                }

                return $this->setValue($rounded.$iPart);
            }
        }
    }

    public function truncate($decimals = 0)
    {
        $fractional = $this->getDecimalPart();
        $whole = $this->getWholePart();

        if ($this->sign == true) {
            $whole = '-'.$whole;
        }

        if ($decimals == 0) {
            $result = $whole;
        } else {
            $truncated = $whole.'.';

            if ($decimals > strlen($fractional)) {
                $fractional = str_pad($fractional, $decimals, '0', STR_PAD_RIGHT);
            } else {
                $fractional = substr($fractional, 0, $decimals);
            }

            $truncated .= $fractional;

            $result = $truncated;
        }

        if ($this->isImaginary()) {
            $result .= 'i';
        }

        return $this->setValue($result);
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

    /**
     * The number of digits between the radix and the for non-zero digit in the decimal part.
     *
     * @return int
     */
    public function numberOfLeadingZeros(): int
    {
        $fractional = $this->getDecimalPart();

        $total = strlen($fractional);
        $fractional = ltrim($fractional, '0');

        return ($total - strlen($fractional));
    }

    /**
     * The number of digits (excludes the radix).
     *
     * @return int
     * @throws IncompatibleObjectState
     */
    public function numberOfTotalDigits(): int
    {
        $wholeDigits = $this->getWholePart();
        $decimalDigits = $this->getDecimalPart();

        $digits = Numbers::makeZero();

        $digits = $digits->add(strlen($wholeDigits))->add(strlen($decimalDigits));

        return $digits->asInt();
    }

    /**
     * The number of digits in the integer part.
     *
     * @return int
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function numberOfIntDigits(): int
    {
        return Numbers::make(Numbers::IMMUTABLE, strlen($this->getWholePart()))->asInt();
    }

    /**
     * The number of digits in the decimal part.
     *
     * @return int
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function numberOfDecimalDigits(): int
    {
        return Numbers::make(Numbers::IMMUTABLE, strlen($this->getDecimalPart()))->asInt();
    }

    /**
     * The number of digits in the decimal part, excluding leading zeros.
     *
     * @return int
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function numberOfSigDecimalDigits(): int
    {
        $decimalPart = $this->getDecimalPart();

        $sigDigits = ltrim($decimalPart, '0');

        return Numbers::make(Numbers::IMMUTABLE, strlen($sigDigits))->asInt();
    }

    /**
     * Returns the current value as an integer if it is within the max a min int values on the current system. Uses the
     * intval() function to convert the string to an integer type.
     *
     * @return int
     * @throws IncompatibleObjectState
     */
    public function asInt(): int
    {

        if ($this->isGreaterThan(PHP_INT_MAX) || $this->isLessThan(PHP_INT_MIN)) {
            throw new IncompatibleObjectState('Cannot export number as integer because it is out of range');
        }

        return intval($this->getValue());

    }

    public function isFloat(): bool
    {

        return (bool)ArithmeticProvider::compare($this->getDecimalPart(), '0');

    }

    public function asFloat(): float
    {

        if ($this->isGreaterThan(PHP_FLOAT_MAX) || $this->isLessThan(PHP_FLOAT_MIN)) {
            throw new IncompatibleObjectState('Cannot export number as integer because it is out of range');
        }

        return (float)$this->asReal();

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
        return $this->value[1];
    }

    protected function getWholePart()
    {
        return $this->value[0];
    }

}