<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Numbers;

use Samsara\Fermat\Types\ComplexNumber;
use Samsara\Fermat\Values\ImmutableComplexNumber;

/**
 *
 */
interface NumberInterface
{

    /**
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function abs();

    /**
     * @return string
     */
    public function absValue(): string;

    /**
     * @param $num
     *
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function add($num);

    /**
     * @param $num
     *
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function subtract($num);

    /**
     * @param $num
     *
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function multiply($num);

    /**
     * @param $num
     * @param int|null $scale
     *
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function divide($num, ?int $scale = null);

    /**
     * @param $num
     *
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function pow($num);

    /**
     * @param int? $scale
     *
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function sqrt(?int $scale = null);

    /**
     * @param float|int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isEqual(NumberInterface|int|string|float $value): bool;

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
     * @return string
     */
    public function asReal(): string;

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