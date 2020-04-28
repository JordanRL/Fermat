<?php

namespace Samsara\Fermat\Types\Base;

use Ds\Hashable;
use Samsara\Fermat\Types\Base\Interfaces\NumberInterface;

abstract class Number implements Hashable, NumberInterface
{
    const INFINITY = 'INF';
    const NEG_INFINITY = '-INF';

    const MODE_PRECISION = 1;
    const MODE_NATIVE = 2;
    const MODE_SIMPLE_TRIG = 3;

    /** @var array */
    protected $value;
    /** @var bool  */
    protected $extensions = true;
    /** @var int */
    protected $mode;
    /** @var bool  */
    protected $imaginary;
    /** @var bool */
    protected $sign;

    public function __construct($value)
    {
        $this->setMode(Number::MODE_PRECISION);

        if (strpos($value, 'i') !== false) {
            $this->imaginary = true;
        } else {
            $this->imaginary = false;
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
    abstract public function getValue();

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
     * This function returns true if the number is imaginary, and false in the number is real
     *
     * @return bool
     */
    public function isImaginary(): bool
    {
        return $this->imaginary;
    }

    /**
     * This function returns true if the number is real, and false if the number is imaginary
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return !$this->isImaginary();
    }

    abstract public function isComplex(): bool;

}