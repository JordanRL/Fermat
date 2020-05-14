<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Numbers;

use Samsara\Fermat\Types\NumberCollection;

interface DecimalInterface extends SimpleNumberInterface
{

    /**
     * @param $mod
     *
     * @return DecimalInterface
     */
    public function modulo($mod);

    /**
     * @param $mod
     *
     * @return DecimalInterface
     */
    public function continuousModulo($mod);

    /**
     * @param $num
     *
     * @return DecimalInterface
     */
    public function getLeastCommonMultiple($num);

    /**
     * @param $num
     *
     * @return DecimalInterface
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
     * @return int
     */
    public function asInt(): int;

    public function isFloat(): bool;

    public function asFloat(): float;

    /**
     * @return bool
     */
    public function isPrime(): bool;

    public function asPrimeFactors(): NumberCollection;

    /**
     * @return DecimalInterface
     */
    public function ceil();

    /**
     * @return DecimalInterface
     */
    public function floor();

    /**
     * @return DecimalInterface
     */
    public function factorial();

    /**
     * @return DecimalInterface
     */
    public function doubleFactorial();

    /**
     * @return DecimalInterface
     */
    public function semiFactorial();

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sin($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cos($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function tan($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cot($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sec($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function csc($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arcsin($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccos($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arctan($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccot($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arcsec($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccsc($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sinh($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cosh($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function tanh($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function coth($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sech($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function csch($precision = null, $round = true);

    /**
     * @param int|null $precision
     * @return DecimalInterface
     */
    public function ln($precision = null);

    /**
     * @param int|null $precision
     * @return DecimalInterface
     */
    public function log10($precision = null);

    /**
     * @param int|null $precision
     * @return DecimalInterface
     */
    public function exp($precision = null);

    /**
     * @param int $decimals
     *
     * @return DecimalInterface
     */
    public function round($decimals = 0);

    /**
     * @param int $decimals
     *
     * @return DecimalInterface
     */
    public function truncate($decimals = 0);

    /**
     * @param $precision
     *
     * @return DecimalInterface
     */
    public function roundToPrecision($precision);

    /**
     * @param $precision
     *
     * @return DecimalInterface
     */
    public function truncateToPrecision($precision);

    /**
     * @return int
     */
    public function numberOfLeadingZeros();

}