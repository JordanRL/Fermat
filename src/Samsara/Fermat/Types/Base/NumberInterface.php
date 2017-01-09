<?php

namespace Samsara\Fermat\Types\Base;

interface NumberInterface
{

    /**
     * @param int $base
     * @return NumberInterface
     */
    public function convertToBase($base);

    /**
     * @param $value
     *
     * @return int
     */
    public function compare($value);

    /**
     * @return NumberInterface
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
     * @return NumberInterface
     */
    public function add($num);

    /**
     * @param $num
     *
     * @return NumberInterface
     */
    public function subtract($num);

    /**
     * @param $num
     *
     * @return NumberInterface
     */
    public function multiply($num);

    /**
     * @param $num
     *
     * @return NumberInterface
     */
    public function divide($num);

    /**
     * @param $num
     *
     * @return NumberInterface
     */
    public function pow($num);

    /**
     * @return NumberInterface
     */
    public function sqrt();

    /**
     * @return int|bool
     */
    public function convertForModification();

    /**
     * @param $oldBase
     *
     * @return NumberInterface
     */
    public function convertFromModification($oldBase);

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function equals($value);

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function greaterThan($value);

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function lessThan($value);

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function greaterThanOrEqualTo($value);

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function lessThanOrEqualTo($value);

}