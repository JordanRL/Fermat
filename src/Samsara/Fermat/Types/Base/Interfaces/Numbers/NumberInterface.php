<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Numbers;

use Samsara\Fermat\Types\ComplexNumber;

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
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isEqual($value): bool;

    /**
     * @return int|null
     */
    public function getScale(): ?int;

    public function isImaginary(): bool;

    public function isReal(): bool;

    public function asReal(): string;

    public function isComplex(): bool;

    public function asComplex(): ComplexNumber;

}