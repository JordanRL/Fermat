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
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function sin($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function cos($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function tan($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function cot($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function sec($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function csc($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function arcsin($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function arccos($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function arctan($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function arccot($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function arcsec($precision = null, $round = true);

    /**
     * @param null $precision
     * @param bool $round
     *
     * @return NumberInterface|DecimalInterface
     */
    public function arccsc($precision = null, $round = true);

    public function ln($precision = 10);

    public function log10($precision = 10);

    /**
     * @param int $decimals
     *
     * @return NumberInterface|DecimalInterface
     */
    public function round($decimals = 0);

    /**
     * @param int $decimals
     *
     * @return NumberInterface|DecimalInterface
     */
    public function truncate($decimals = 0);

    /**
     * @param $precision
     *
     * @return NumberInterface|DecimalInterface
     */
    public function roundToPrecision($precision);

    /**
     * @param $precision
     *
     * @return NumberInterface|DecimalInterface
     */
    public function truncateToPrecision($precision);

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