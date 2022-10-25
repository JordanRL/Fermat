<?php

namespace Samsara\Fermat\Core\Types\Base;

use Ds\Hashable;
use ReflectionException;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Core\Types\Traits\CalculationModeTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 * @package Samsara\Fermat\Core
 */
abstract class Number implements Hashable
{
    use CalculationModeTrait;

    public const INFINITY = 'INF';
    public const NEG_INFINITY = '-INF';

    /** @var array */
    protected array $value;
    /** @var bool  */
    protected bool $imaginary;
    /** @var bool */
    protected bool $sign;
    /** @var int */
    protected int $scale;
    protected NumberBase $base;

    public function __construct()
    {

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
        return get_class($this).$this->getValue();
    }

    /**
     * Implemented to satisfy Hashable implementation
     *
     * @param mixed $obj
     * @return bool
     */
    public function equals(mixed $obj): bool
    {
        if ($obj instanceof Number || is_numeric($obj)) {
            return $this->isEqual($obj);
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
     * @return ImmutableDecimal|ImmutableFraction
     */
    abstract public function asReal(): ImmutableDecimal|ImmutableFraction;

    /**
     * @return string
     */
    abstract public function getAsBaseTenRealNumber(): string;

    /**
     * @return bool
     */
    abstract public function isComplex(): bool;

    /**
     * @param Number|int|string|float $value
     * @return bool
     */
    abstract public function isEqual(Number|int|string|float $value): bool;


    /**
     * @param $value
     * @return bool|null
     */
    abstract public function isGreaterThan($value): bool|null;


    /**
     * @param $value
     * @return bool|null
     */
    abstract public function isLessThan($value): bool|null;


    /**
     * @param $value
     * @return bool|null
     */
    abstract public function isGreaterThanOrEqualTo($value): bool|null;

    /**
     * @param $value
     * @return bool|null
     */
    abstract public function isLessThanOrEqualTo($value): bool|null;

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