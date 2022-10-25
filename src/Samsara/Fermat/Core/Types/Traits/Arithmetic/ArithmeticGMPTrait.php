<?php


namespace Samsara\Fermat\Core\Types\Traits\Arithmetic;

use Samsara\Fermat\Core\Types\Decimal;

/**
 * @package Samsara\Fermat\Core
 */
trait ArithmeticGMPTrait
{

    /**
     * @param Decimal $num
     * @return string|false
     */
    protected function addGMP(Decimal $num): string|false
    {
        if ($this->isInt() && $num->isInt()) {
            $result = gmp_add($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

            return gmp_strval($result);
        }

        return false;
    }

    /**
     * @param Decimal $num
     * @return string|false
     */
    protected function subtractGMP(Decimal $num): string|false
    {
        if ($this->isInt() && $num->isInt()) {
            $result = gmp_sub($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

            return gmp_strval($result);
        }

        return false;
    }

    /**
     * @param Decimal $num
     * @return string|false
     */
    protected function multiplyGMP(Decimal $num): string|false
    {
        if ($this->isInt() && $num->isInt()) {
            $result = gmp_mul($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

            return gmp_strval($result);
        }

        return false;
    }

    /**
     * @param Decimal $num
     * @return string|false
     */
    protected function divideGMP(Decimal $num): string|false
    {
        if ($this->isInt() && $num->isInt()) {
            $result = gmp_div_qr($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

            if (gmp_strval($result[1]) == '0') {
                return gmp_strval($result[0]);
            }
        }

        return false;
    }

    /**
     * @param Decimal $num
     * @return string|false
     */
    protected function powGMP(Decimal $num): string|false
    {
        if ($this->isInt() && $num->isInt() && $num->isPositive()) {
            $result = gmp_pow($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

            return gmp_strval($result);
        }

        return false;
    }

    /**
     * @return string|false
     */
    protected function sqrtGMP(): string|false
    {
        if ($this->isInt()) {
            $result = gmp_sqrtrem($this->getAsBaseTenRealNumber());

            if ($result[1] == '0') {
                return gmp_strval($result[0]);
            }
        }

        return false;
    }

}