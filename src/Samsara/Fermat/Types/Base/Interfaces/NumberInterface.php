<?php

namespace Samsara\Fermat\Types\Base\Interfaces;

interface NumberInterface
{

    /**
     * @param $value
     *
     * @return int
     */
    public function compare($value);

    /**
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function abs();

    /**
     * @return string
     */
    public function absValue();

    /**
     * @return bool
     */
    public function isNegative();

    /**
     * @return bool
     */
    public function isPositive();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return int
     */
    public function getBase();

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
     *
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function divide($num);

    /**
     * @param $num
     *
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function pow($num);

    /**
     * @param $precision
     *
     * @return NumberInterface|DecimalInterface|FractionInterface
     */
    public function sqrt($precision);

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isEqual($value): bool;

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isGreaterThan($value): bool;

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isLessThan($value): bool;

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isGreaterThanOrEqualTo($value): bool;

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isLessThanOrEqualTo($value): bool;

    public function isImaginary(): bool;

    public function isReal(): bool;

    public function isComplex(): bool;

}