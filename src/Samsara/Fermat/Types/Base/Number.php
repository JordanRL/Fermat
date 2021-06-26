<?php

namespace Samsara\Fermat\Types\Base;

use Ds\Hashable;
use ReflectionException;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\ComplexNumber;
use Samsara\Fermat\Values\ImmutableComplexNumber;

abstract class Number implements Hashable, NumberInterface
{
    public const INFINITY = 'INF';
    public const NEG_INFINITY = '-INF';

    /** @var array */
    protected array $value;
    /** @var bool  */
    protected $extensions = true;
    /** @var bool  */
    protected $imaginary;
    /** @var bool */
    protected bool $sign;

    public function __construct()
    {
        $this->setMode(Numbers::getDefaultCalcMode());
    }

    /**
     * Allows you to set a mode on a number to select the calculation methods.
     *
     * MODE_PRECISION: Use what is necessary to provide an answer that is accurate to the scale setting.
     * MODE_NATIVE: Use built-in functions to perform the math, and accept whatever rounding or truncation this might cause.
     *
     * @param int $mode
     * @return $this
     */
    public function setMode(int $mode): self
    {
        $this->calcMode = $mode;

        return $this;
    }

    /**
     * Returns the current value as a string.
     *
     * @return string
     */
    abstract public function getValue(): string;

    /**
     * Allows the object to ignore PHP extensions (such a GMP) and use only the Fermat implementations. NOTE: This does
     * not ignore ext-bcmath or ext-decimal, as those are necessary for the string math itself.
     *
     * @param bool $flag
     * @return $this
     */
    public function setExtensions(bool $flag): self
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
        } catch (ReflectionException $exception) {
            return false;
        }

        if ($reflection->implementsInterface(NumberInterface::class) || is_numeric($object)) {
            return $this->isEqual($object);
        } else {
            return false;
        }
    }

    /**
     * This function returns true if the number is imaginary, and false in the number is real or complex
     *
     * @return bool
     */
    public function isImaginary(): bool
    {
        return $this->imaginary;
    }

    /**
     * This function returns true if the number is real, and false if the number is imaginary or complex
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return !$this->imaginary;
    }

    public function asReal(): string
    {
        return $this->getAsBaseTenRealNumber();
    }

    abstract public function getAsBaseTenRealNumber(): string;

    abstract public function isComplex(): bool;

    public function asComplex(): ComplexNumber
    {
        if ($this->isReal()) {
            return new ImmutableComplexNumber(clone $this, Numbers::makeZero());
        }

        return new ImmutableComplexNumber(Numbers::makeZero(), clone $this);
    }

}