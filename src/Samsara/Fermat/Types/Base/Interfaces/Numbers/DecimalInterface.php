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
    public function subFactorial(): DecimalInterface;

    /**
     * @return DecimalInterface
     */
    public function doubleFactorial(): DecimalInterface;

    /**
     * @return DecimalInterface
     */
    public function semiFactorial(): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sin($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cos($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function tan($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cot($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sec($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function csc($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arcsin($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccos($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arctan($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccot($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arcsec($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccsc($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sinh($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cosh($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function tanh($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function coth($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sech($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function csch($scale = null, $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @return DecimalInterface
     */
    public function ln($scale = null): DecimalInterface;

    /**
     * @param int|null $scale
     * @return DecimalInterface
     */
    public function log10($scale = null): DecimalInterface;

    /**
     * @param int|null $scale
     * @return DecimalInterface
     */
    public function exp($scale = null): DecimalInterface;

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
     * @param $scale
     *
     * @return DecimalInterface
     */
    public function roundToScale($scale): DecimalInterface;

    /**
     * @param $scale
     *
     * @return DecimalInterface
     */
    public function truncateToScale($scale): DecimalInterface;

    /**
     * @return int
     */
    public function numberOfLeadingZeros(): int;

}