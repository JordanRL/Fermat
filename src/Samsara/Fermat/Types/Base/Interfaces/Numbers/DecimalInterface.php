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
    public function sin(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cos(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function tan(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cot(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sec(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function csc(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arcsin(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccos(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arctan(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccot(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arcsec(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function arccsc(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sinh(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function cosh(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function tanh(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function coth(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function sech(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @param bool $round
     *
     * @return DecimalInterface
     */
    public function csch(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @return DecimalInterface
     */
    public function ln(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @return DecimalInterface
     */
    public function log10(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int|null $scale
     * @return DecimalInterface
     */
    public function exp(int $scale = null, bool $round = true): DecimalInterface;

    /**
     * @param int $decimals
     * @param int|null $mode
     * @return DecimalInterface
     */
    public function round(int $decimals = 0, ?int $mode = null): DecimalInterface;

    /**
     * @param int $decimals
     *
     * @return DecimalInterface
     */
    public function truncate(int $decimals = 0): DecimalInterface;

    /**
     * @param int $scale
     * @param int|null $mode
     * @return DecimalInterface
     */
    public function roundToScale(int $scale, ?int $mode = null): DecimalInterface;

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