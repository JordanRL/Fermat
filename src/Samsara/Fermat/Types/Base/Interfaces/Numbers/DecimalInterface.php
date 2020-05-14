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
    public function modulo($mod): DecimalInterface;

    /**
     * @param $mod
     *
     * @return DecimalInterface
     */
    public function continuousModulo($mod): DecimalInterface;

    /**
     * @param $num
     *
     * @return DecimalInterface
     */
    public function getLeastCommonMultiple($num): DecimalInterface;

    /**
     * @param $num
     *
     * @return DecimalInterface
     */
    public function getGreatestCommonDivisor($num): DecimalInterface;

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
    public function ceil(): DecimalInterface;

    /**
     * @return DecimalInterface
     */
    public function floor(): DecimalInterface;

    /**
     * @return DecimalInterface
     */
    public function factorial(): DecimalInterface;

    /**
     * @return DecimalInterface
     */
    public function doubleFactorial(): DecimalInterface;

    /**
     * @return DecimalInterface
     */
    public function semiFactorial(): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sin($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cos($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function tan($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cot($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sec($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function csc($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arcsin($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccos($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arctan($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccot($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arcsec($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccsc($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sinh($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cosh($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function tanh($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function coth($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sech($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function csch($precision = null, $round = true): DecimalInterface;

    /**
     * @param int|null $precision
     * @return DecimalInterface
     */
    public function ln($precision = null): DecimalInterface;

    /**
     * @param int|null $precision
     * @return DecimalInterface
     */
    public function log10($precision = null): DecimalInterface;

    /**
     * @param int|null $precision
     * @return DecimalInterface
     */
    public function exp($precision = null): DecimalInterface;

    /**
     * @param int $decimals
     *
     * @return DecimalInterface
     */
    public function round($decimals = 0): DecimalInterface;

    /**
     * @param int $decimals
     *
     * @return DecimalInterface
     */
    public function truncate($decimals = 0): DecimalInterface;

    /**
     * @param $precision
     *
     * @return DecimalInterface
     */
    public function roundToPrecision($precision): DecimalInterface;

    /**
     * @param $precision
     *
     * @return DecimalInterface
     */
    public function truncateToPrecision($precision): DecimalInterface;

    /**
     * @return int
     */
    public function numberOfLeadingZeros(): int;

}