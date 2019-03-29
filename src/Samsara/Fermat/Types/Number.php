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
use Samsara\Fermat\Types\Traits\PrecisionTrait;
use Samsara\Fermat\Types\Traits\TrigonometryTrait;
use Samsara\Fermat\Values\ImmutableNumber;

abstract class Number implements Hashable
{
    const INFINITY = 'INF';
    const NEG_INFINITY = '-INF';
    
    protected $value;

    protected $base;

    protected $extensions = true;

    use ArithmeticTrait;
    use IntegerMathTrait;
    use ComparisonTrait;
    use TrigonometryTrait;
    use InverseTrigonometryTrait;
    use LogTrait;
    use PrecisionTrait;

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
            if ($this->getDecimalPart() === 0) {
                $this->precision = (strlen($this->getWholePart()) > 10) ? strlen($this->getWholePart()) : 10;
            } else {
                $this->precision = (strlen($this->getDecimalPart()) > 10) ? strlen($this->getDecimalPart()) : 10;
            }
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
    
    public function numberOfLeadingZeros(): int
    {
        $fractional = $this->getDecimalPart();
        
        $total = Numbers::make(Numbers::IMMUTABLE, strlen($fractional));
        $fractional = ltrim($fractional, '0');
        
        return $total->subtract(strlen($fractional))->asInt();
    }

    public function numberOfTotalDigits(): int
    {
        $wholeDigits = $this->getWholePart();
        $decimalDigits = $this->getDecimalPart();

        $digits = Numbers::makeZero();

        $digits->add(strlen($wholeDigits))->add(strlen($decimalDigits));

        return $digits->asInt();
    }

    public function numberOfIntDigits()
    {
        return Numbers::make(Numbers::IMMUTABLE, strlen($this->getWholePart()))->asInt();
    }

    public function numberOfDecimalDigits()
    {
        return Numbers::make(Numbers::IMMUTABLE, strlen($this->getDecimalPart()))->asInt();
    }

    public function numberOfSigDecimalDigits()
    {
        $decimalPart = $this->getDecimalPart();

        $sigDigits = ltrim($decimalPart, '0');

        return Numbers::make(Numbers::IMMUTABLE, strlen($sigDigits))->asInt();
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