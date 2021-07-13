<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Provider\RoundingProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

trait ScaleTrait
{

    protected $scale;

    public function getScale(): ?int
    {
        return $this->scale;
    }

    public function round(int $decimals = 0, ?int $mode = null): DecimalInterface
    {
        if ($mode) {
            $defaultMode = RoundingProvider::getRoundingMode();
            RoundingProvider::setRoundingMode($mode);
            $return = $this->setValue(RoundingProvider::round($this, $decimals));
            RoundingProvider::setRoundingMode($defaultMode);

            return $return;
        }

        return $this->setValue(RoundingProvider::round($this, $decimals));
    }

    public function truncate(int $decimals = 0): DecimalInterface
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

    public function roundToScale(int $scale, ?int $mode = null): DecimalInterface
    {

        $this->scale = $scale;

        return $this->round($scale, $mode);

    }

    public function truncateToScale($scale): DecimalInterface
    {

        $this->scale = $scale;

        return $this->truncate($scale);

    }

    public function ceil(): DecimalInterface
    {
        return $this->round(0, RoundingProvider::MODE_CEIL);
    }

    public function floor(): DecimalInterface
    {
        return $this->round(0, RoundingProvider::MODE_FLOOR);
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
        return intval($this->getValue());
    }

    public function isFloat(): bool
    {

        return (bool)ArithmeticProvider::compare($this->getDecimalPart(), '0');

    }

    public function asFloat(): float
    {
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