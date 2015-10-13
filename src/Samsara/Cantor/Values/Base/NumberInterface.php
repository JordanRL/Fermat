<?php

namespace Samsara\Cantor\Values\Base;

interface NumberInterface
{

    /**
     * @param int $base
     * @return $this
     */
    public function convertToBase($base);

    public function modulo($mod);

    public function compare($value);

    public function abs();

    public function absValue();

    public function isNegative();

    public function isPositive();

    public function round();

    public function ceil();

    public function floor();

    public function getValue();

    public function getBase();

    public function getPrecision();

    public function add($num);

    public function subtract($num);

    public function multiply($num);

    public function divide($num);

    public function exp($num);

    public function convertForModification();

    public function convertFromModification($oldBase);

}