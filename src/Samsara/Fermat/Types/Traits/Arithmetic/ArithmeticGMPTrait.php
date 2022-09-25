<?php


namespace Samsara\Fermat\Types\Traits\Arithmetic;

use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

/**
 *
 */
trait ArithmeticGMPTrait
{

    /**
     * @param DecimalInterface $num
     * @return string|false
     */
    protected function addGMP(DecimalInterface $num): string|false
    {
        if (function_exists('gmp_add') && function_exists('gmp_strval')) {
            if ($this->isInt() && $num->isInt()) {
                $result = gmp_add($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

                return gmp_strval($result);
            }
        }

        return false;
    }

    /**
     * @param DecimalInterface $num
     * @return string|false
     */
    protected function subtractGMP(DecimalInterface $num): string|false
    {
        if (function_exists('gmp_sub') && function_exists('gmp_strval')) {
            if ($this->isInt() && $num->isInt()) {
                $result = gmp_sub($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

                return gmp_strval($result);
            }
        }

        return false;
    }

    /**
     * @param DecimalInterface $num
     * @return string|false
     */
    protected function multiplyGMP(DecimalInterface $num): string|false
    {
        if (function_exists('gmp_mul') && function_exists('gmp_strval')) {
            if ($this->isInt() && $num->isInt()) {
                $result = gmp_mul($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

                return gmp_strval($result);
            }
        }

        return false;
    }

    /**
     * @param DecimalInterface $num
     * @return string|false
     */
    protected function divideGMP(DecimalInterface $num): string|false
    {
        if (function_exists('gmp_div_qr') && function_exists('gmp_strval')) {
            if ($this->isInt() && $num->isInt()) {
                $result = gmp_div_qr($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

                if (gmp_strval($result[1]) == '0') {
                    return gmp_strval($result[0]);
                } else {
                    $decimalPart = ArithmeticProvider::divide(gmp_strval($result[1]), $num->getAsBaseTenRealNumber());
                    $decimalPart = explode('.', $decimalPart);

                    return gmp_strval($result[0]).'.'.$decimalPart[1];
                }
            }
        }

        return false;
    }

    /**
     * @param DecimalInterface $num
     * @return string|false
     */
    protected function powGMP(DecimalInterface $num): string|false
    {
        if (function_exists('gmp_pow') && function_exists('gmp_strval')) {
            if ($this->isInt() && $num->isInt() && $num->isPositive()) {
                $result = gmp_pow($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

                return gmp_strval($result);
            }
        }

        return false;
    }

    /**
     * @return string|false
     */
    protected function sqrtGMP(): string|false
    {
        if (function_exists('gmp_sqrtrem') && function_exists('gmp_strval')) {
            if ($this->isInt() && $this->isLessThan(PHP_INT_MAX) && $this->isGreaterThan(PHP_INT_MIN)) {
                $result = gmp_sqrtrem($this->getAsBaseTenRealNumber());

                if ($result[1] == '0') {
                    return gmp_strval($result[0]);
                }
            }
        }

        return false;
    }

}