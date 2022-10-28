<?php

namespace Samsara\Fermat\Core\Types\Base;

use Ds\Hashable;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Core\Enums\NumberBase;
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
    protected NumberBase $base;
    /** @var bool */
    protected bool $imaginary;
    /** @var int */
    protected int $scale;
    /** @var bool */
    protected bool $sign;
    /** @var array */
    protected array $value;

    public function __construct() {}

    /**
     * Returns the current base that the value is in.
     *
     * @return NumberBase
     */
    public function getBase(): NumberBase
    {
        return $this->base;
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
     * Implemented to satisfy Hashable implementation
     *
     * @param mixed $obj
     *
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
     * Implemented to satisfy Hashable implementation
     *
     * @return string
     */
    public function hash(): string
    {
        return get_class($this) . $this->getValue();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * Returns the string of the absolute value of the current object.
     *
     * @return string
     */
    abstract public function absValue(): string;

    /**
     * @return ImmutableComplexNumber
     * @throws IntegrityConstraint
     */
    abstract public function asComplex(): ImmutableComplexNumber;

    /**
     * Returns a new instance of this object with a base ten imaginary number.
     *
     * @return ImmutableDecimal|ImmutableFraction
     */
    abstract public function asImaginary(): ImmutableDecimal|ImmutableFraction;

    /**
     * Returns a new instance of this object with a base ten real number.
     *
     * @return ImmutableDecimal|ImmutableFraction
     */
    abstract public function asReal(): ImmutableDecimal|ImmutableFraction;

    /**
     * Returns the current value as a string in base 10, converted to a real number. If the number is imaginary, the i is
     * simply not printed. If the number is complex, then the absolute value is returned.
     *
     * @return string
     */
    abstract public function getAsBaseTenRealNumber(): string;

    /**
     * Returns the current value as a string.
     *
     * @return string
     */
    abstract public function getValue(): string;

    /**
     * Returns true if the number is complex, false if the number is real or imaginary.
     *
     * @return bool
     */
    abstract public function isComplex(): bool;

    /**
     * Compares this number to another number and returns whether or not they are equal.
     *
     * @param Number|int|string|float $value The value to compare against
     *
     * @return bool
     */
    abstract public function isEqual(Number|int|string|float $value): bool;

    /**
     * Compares this number to another number and returns true if this number is closer to positive infinity.
     *
     * @param Number|int|string|float $value The value to compare against
     *
     * @return bool|null
     */
    abstract public function isGreaterThan(Number|int|string|float $value): bool|null;

    /**
     * Compares this number to another number and returns true if this number is closer to positive infinity or equal.
     *
     * @param Number|int|string|float $value The value to compare against
     *
     * @return bool|null
     */
    abstract public function isGreaterThanOrEqualTo(Number|int|string|float $value): bool|null;

    /**
     * Compares this number to another number and returns true if this number is closer to negative infinity.
     *
     * @param Number|int|string|float $value The value to compare against
     *
     * @return bool|null
     */
    abstract public function isLessThan(Number|int|string|float $value): bool|null;

    /**
     * Compares this number to another number and returns true if this number is closer to negative infinity or equal.
     *
     * @param Number|int|string|float $value The value to compare against
     *
     * @return bool|null
     */
    abstract public function isLessThanOrEqualTo(Number|int|string|float $value): bool|null;

}