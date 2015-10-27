<?php

namespace Samsara\Fermat\Types;

interface NumberInterface
{

    /**
     * @param int $base
     * @return NumberInterface
     */
    public function convertToBase($base);

    /**
     * @param $mod
     *
     * @return NumberInterface
     */
    public function modulo($mod);

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
     * @param int $decimals
     *
     * @return NumberInterface
     */
    public function round($decimals = 0);

    /**
     * @return NumberInterface
     */
    public function ceil();

    /**
     * @return NumberInterface
     */
    public function floor();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return int
     */
    public function getBase();

    /**
     * @return int|null
     */
    public function getPrecision();

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
    public function exp($num);

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

}