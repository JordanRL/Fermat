<?php

namespace Samsara\Fermat\Types;

use Ds\Hashable;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Riimu\Kit\BaseConversion\BaseConverter;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Provider\StatsProvider;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Types\Traits\ArithmeticTrait;
use Samsara\Fermat\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Types\Traits\IntegerMathTrait;
use Samsara\Fermat\Types\Traits\InverseTrigonometryTrait;
use Samsara\Fermat\Types\Traits\LogTrait;
use Samsara\Fermat\Types\Traits\TrigonometryTrait;
use Samsara\Fermat\Values\ImmutableNumber;

abstract class Number implements Hashable
{
    const INFINITY = 'INF';
    const NEG_INFINITY = '-INF';
    
    protected $value;

    protected $precision;

    protected $base;

    protected $extensions = true;

    use ArithmeticTrait;
    use IntegerMathTrait;
    use ComparisonTrait;
    use TrigonometryTrait;
    use InverseTrigonometryTrait;
    use LogTrait;

    public function __construct($value, $precision = null, $base = 10)
    {
        $this->base = $base;
        $this->value = (string)$value;
        
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
            $this->precision = (strlen($this->getDecimalPart()) > 10) ? strlen($this->getDecimalPart()) : 10;
        }
    }

    public function getValue()
    {
        if ($this->getRadixPos()) {
            return rtrim(rtrim($this->value, '0'), '.');
        } else {
            return $this->value;
        }
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getPrecision()
    {
        return $this->precision;
    }

    public function setExtensions(bool $flag)
    {

        $this->extensions = $flag;

        return $this;

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

    public function absValue(): string
    {
        if ($this->isNegative()) {
            return substr($this->getValue(), 1);
        } else {
            return $this->getValue();
        }
    }

    public function isNegative(): bool
    {
        if ($this->isEqual(0)) {
            return false;
        }

        if (strpos($this->getValue(), '-') === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isPositive(): bool
    {
        if ($this->isEqual(0)) {
            return false;
        }

        return !$this->isNegative();
    }

    public function isNatural(): bool
    {
        return $this->isInt();
    }

    public function isWhole(): bool
    {
        return $this->isInt();
    }

    public function isInt(): bool
    {
        if ($this->getDecimalPart() == 0) {
            return true;
        } else {
            return false;
        }
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

    /**
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

    public function __toString()
    {
        return $this->getValue();
    }

    public function hash()
    {
        return get_class($this).$this->getValue();
    }

    public function equals($object): bool
    {
        $reflection = new \ReflectionClass($object);

        if ($reflection->implementsInterface(NumberInterface::class) || is_numeric($object)) {
            return $this->isEqual($object);
        } else {
            return false;
        }
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

    /**
     * @param $mod
     *
     * @return NumberInterface|DecimalInterface
     */
    abstract public function modulo($mod);

    /**
     * @param $mod
     *
     * @return NumberInterface|DecimalInterface
     */
    abstract public function continuousModulo($mod);


    /**
     * @param $value
     *
     * @return NumberInterface|DecimalInterface
     */
    abstract protected function setValue($value);

}