<?php

namespace Samsara\Fermat\Types\Base;

interface DecimalInterface
{

    /**
     * @param $mod
     *
     * @return NumberInterface|DecimalInterface
     */
    public function modulo($mod);

    /**
     * @param $mod
     *
     * @return NumberInterface|DecimalInterface
     */
    public function continuousModulo($mod);

    /**
     * @param $num
     *
     * @return NumberInterface|DecimalInterface
     */
    public function getLeastCommonMultiple($num);

    /**
     * @param $num
     *
     * @return NumberInterface|DecimalInterface
     */
    public function getGreatestCommonDivisor($num);

    /**
     * @return bool
     */
    public function isNatural(): bool;

    /**
     * @return bool
     */
    public function isWhole(): bool;

    /**
     * @return bool
     */
    public function isInt(): bool;

    /**
     * @return bool
     */
    public function isPrime(): bool;

    /**
     * @return NumberInterface|DecimalInterface
     */
    public function ceil();

    /**
     * @return NumberInterface|DecimalInterface
     */
    public function floor();

    /**
     * @return int|null
     */
    public function getPrecision();

    /**
     * @return NumberInterface|DecimalInterface
     */
    public function factorial();

    /**
     * @return NumberInterface|DecimalInterface
     */
    public function doubleFactorial();

    /**
     * @return NumberInterface|DecimalInterface
     */
    public function semiFactorial();

    /**
     * @param int  $mult
     * @param int  $div
     * @param null $precision
     *
     * @return NumberInterface|DecimalInterface
     */
    public function sin($mult = 1, $div = 1, $precision = null);

    /**
     * @param int  $mult
     * @param int  $div
     * @param null $precision
     *
     * @return NumberInterface|DecimalInterface
     */
    public function cos($mult = 1, $div = 1, $precision = null);

    public function ln($precision = 10);

    public function log10($precision = 10);

    /**
     * @param int $decimals
     *
     * @return NumberInterface|DecimalInterface
     */
    public function round($decimals = 0);

    /**
     * @param $precision
     *
     * @return NumberInterface|DecimalInterface
     */
    public function roundToPrecision($precision);

    /**
     * @return int
     */
    public function numberOfLeadingZeros();

    /**
     * @return int|bool
     */
    public function convertForModification();

    /**
     * @param $oldBase
     *
     * @return NumberInterface|DecimalInterface
     */
    public function convertFromModification($oldBase);

}