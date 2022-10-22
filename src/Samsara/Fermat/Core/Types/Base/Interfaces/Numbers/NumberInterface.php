<?php

namespace Samsara\Fermat\Core\Types\Base\Interfaces\Numbers;

use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;

/**
 *
 */
interface NumberInterface
{

    /**
     * @return MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|NumberInterface
     */
    public function abs(): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static;

    /**
     * @return string
     */
    public function absValue(): string;

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     *
     * @return MutableDecimal|ImmutableComplexNumber|MutableFraction|ImmutableDecimal|MutableComplexNumber|ImmutableFraction|NumberInterface
     */
    public function add(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static;

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     *
     * @return MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|NumberInterface
     */
    public function subtract(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static;

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     *
     * @return MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|NumberInterface
     */
    public function multiply(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static;

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @param int|null $scale
     *
     * @return MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|NumberInterface
     */
    public function divide(
        string|int|float|Decimal|Fraction|ComplexNumber $num,
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static;

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     *
     * @return MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|NumberInterface
     */
    public function pow(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static;

    /**
     * @param int|null $scale
     * @return MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|NumberInterface
     */
    public function sqrt(
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static;

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $value
     *
     * @return bool
     */
    public function isEqual(string|int|float|Decimal|Fraction|ComplexNumber $value): bool;

    /**
     * @return int|null
     */
    public function getScale(): ?int;

    /**
     * @return bool
     */
    public function isImaginary(): bool;

    /**
     * @return bool
     */
    public function isReal(): bool;

    /**
     * @return ImmutableDecimal|ImmutableFraction
     */
    public function asReal(): ImmutableDecimal|ImmutableFraction;

    /**
     * @return bool
     */
    public function isComplex(): bool;

    /**
     * @return ImmutableComplexNumber
     */
    public function asComplex(): ImmutableComplexNumber;

    /**
     * Returns the current value as a string.
     *
     * @return string
     */
    public function getValue(): string;

}