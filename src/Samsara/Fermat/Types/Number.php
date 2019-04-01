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

    const MODE_PRECISION = 1;
    const MODE_NATIVE = 2;
    const MODE_SIMPLE_TRIG = 3;
    
    protected $value;

    protected $base;

    protected $extensions = true;

    protected $mode;

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
        $this->setMode(Number::MODE_PRECISION);
        
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

    /**
     * Allows you to set a mode on a number to select the calculation methods.
     *
     * MODE_PRECISION: Use what is necessary to provide an answer that is accurate to the precision setting.
     * MODE_NATIVE: Use built-in functions to perform the math, and accept whatever rounding or truncation this might cause.
     * MODE_SIMPLE_TRIG: Use simpler versions of the trig functions, which lose accuracy as significant figures grows.
     *
     * @param int $mode
     * @return $this
     */
    public function setMode(int $mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Returns the current value as a string.
     *
     * @return string
     */
    public function getValue()
    {
        if ($this->getRadixPos()) {
            return rtrim(rtrim($this->value, '0'), '.');
        } else {
            return $this->value;
        }
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

    /**
     * Allows the object to ignore PHP extensions (such a GMP) and use only the Fermat implementations. NOTE: This does
     * not ignore ext-bcmath or ext-decimal, as those are necessary for the string math itself.
     *
     * @param bool $flag
     * @return $this
     */
    public function setExtensions(bool $flag)
    {

        $this->extensions = $flag;

        return $this;

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
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * Implemented to satisfy Hashable implementation
     *
     * @return string
     */
    public function hash(): string
    {
        return get_class($this).$this->getValue();
    }

    /**
     * Implemented to satisfy Hashable implementation
     *
     * @param mixed $object
     * @return bool
     */
    public function equals($object): bool
    {
        try {
            $reflection = new \ReflectionClass($object);
        } catch (\ReflectionException $exception) {
            return false;
        }

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