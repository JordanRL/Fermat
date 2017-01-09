<?php

namespace Samsara\Fermat\Values\Base;

interface DecimalInterface
{

    /**
     * @param $mod
     *
     * @return NumberInterface
     */
    public function modulo($mod);

    /**
     * @param $mod
     *
     * @return NumberInterface
     */
    public function continuousModulo($mod);

    /**
     * @return bool
     */
    public function isNatural();

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
     * @return int|null
     */
    public function getPrecision();

    /**
     * @return NumberInterface
     */
    public function factorial();

    /**
     * @return NumberInterface
     */
    public function doubleFactorial();

    /**
     * @return NumberInterface
     */
    public function semiFactorial();

    /**
     * @param int  $mult
     * @param int  $div
     * @param null $precision
     *
     * @return NumberInterface
     */
    public function sin($mult = 1, $div = 1, $precision = null);

    /**
     * @param int  $mult
     * @param int  $div
     * @param null $precision
     *
     * @return NumberInterface
     */
    public function cos($mult = 1, $div = 1, $precision = null);

    /**
     * @param $precision
     *
     * @return NumberInterface
     */
    public function roundToPrecision($precision);

    /**
     * @return int
     */
    public function numberOfLeadingZeros();

}