<?php

namespace Samsara\Fermat\Core\Types\Base;

use Ds\Hashable;
use ReflectionException;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\NumberFormat;
use Samsara\Fermat\Core\Enums\NumberGrouping;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\CalculationModeProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;

/**
 *
 */
abstract class Number implements Hashable, NumberInterface
{
    public const INFINITY = 'INF';
    public const NEG_INFINITY = '-INF';

    /** @var array */
    protected array $value;
    /** @var bool  */
    protected bool $imaginary;
    /** @var bool */
    protected bool $sign;
    /** @var CalcMode|null */
    protected ?CalcMode $calcMode = null;
    /** @var int */
    protected int $scale;
    protected NumberBase $base;

    public function __construct()
    {

    }

    /**
     * Allows you to set a mode on a number to select the calculation methods.
     *
     * @param CalcMode $mode
     * @return $this
     */
    public function setMode(CalcMode $mode): self
    {
        $this->calcMode = $mode;

        return $this;
    }

    /**
     * @return CalcMode
     */
    public function getMode(): CalcMode
    {

        return $this->calcMode ?? CalculationModeProvider::getCurrentMode();

    }

    /**
     * Returns the current value as a string.
     *
     * @return string
     */
    abstract public function getValue(): string;

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
        return get_class($this).$this->getValue(NumberBase::Ten);
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
     * This function returns true if the number is imaginary, and false if the number is real or complex
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

    /**
     * @return string
     */
    public function asReal(): string
    {
        return $this->getAsBaseTenRealNumber();
    }

    /**
     * @return string
     */
    abstract public function getAsBaseTenRealNumber(): string;

    /**
     * @return bool
     */
    abstract public function isComplex(): bool;

    /**
     * @return ImmutableComplexNumber
     * @throws IntegrityConstraint
     */
    public function asComplex(): ImmutableComplexNumber
    {
        if ($this->isReal()) {
            return new ImmutableComplexNumber(clone $this, Numbers::makeZero());
        }

        return new ImmutableComplexNumber(Numbers::makeZero(), clone $this);
    }

    public function getBase(): NumberBase
    {
        return $this->base;
    }

}