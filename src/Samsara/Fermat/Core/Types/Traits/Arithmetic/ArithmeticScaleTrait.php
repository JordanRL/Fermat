<?php

declare(strict_types=1);

namespace Samsara\Fermat\Core\Types\Traits\Arithmetic;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\ArithmeticProvider;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
trait ArithmeticScaleTrait
{

    /**
     * @param Decimal $num
     * @return string
     */
    protected function addScale(Decimal $num): string
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        return ArithmeticProvider::add($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber(), $scale);

    }

    /**
     * @param Decimal $num
     * @return string
     */
    protected function subtractScale(Decimal $num): string
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        return ArithmeticProvider::subtract($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber(), $scale);

    }

    /**
     * @param Decimal $num
     * @return string
     */
    protected function multiplyScale(Decimal $num): string
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        return ArithmeticProvider::multiply($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber(), $scale);

    }

    /**
     * @param Decimal $num
     * @param int|null $scale
     * @return string
     */
    protected function divideScale(Decimal $num, ?int $scale): string
    {
        /**
         * This method is never reached from Fraction, even though it is defined on the class
         *
         * @var ImmutableDecimal|Decimal|MutableDecimal $this
         */

        if (is_null($scale)) {
            $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();
        }

        $scale = $scale + $this->numberOfLeadingZeros() + $num->numberOfLeadingZeros();

        return ArithmeticProvider::divide($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber(), $scale+1);

    }

    /**
     * @param Decimal $num
     * @return string
     * @throws IntegrityConstraint
     */
    protected function powScale(Decimal $num): string
    {
        /**
         * This method is never reached from Fraction, even though it is defined on the class
         *
         * @var ImmutableDecimal|Decimal|MutableDecimal $this
         */

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        $scale += $this->numberOfDecimalDigits() + $num->numberOfDecimalDigits();

        if ($this->isWhole() && $num->isPositive() && $num->isWhole() && $num->isLessThan(PHP_INT_MAX)) {
            return gmp_strval(gmp_pow($this->getAsBaseTenRealNumber(), $num->asInt()));
        } elseif (!$num->isWhole() && !extension_loaded('decimal')) {
            $scale += 3;
            $thisNum = Numbers::make(Numbers::IMMUTABLE, $this->getValue(NumberBase::Ten), $scale);
            $thatNum = Numbers::make(Numbers::IMMUTABLE, $num->getValue(NumberBase::Ten), $scale);
            $exponent = $thatNum->multiply($thisNum->ln($scale, false));
            return $exponent->exp($scale, false)->getValue(NumberBase::Ten);
        }

        return ArithmeticProvider::pow($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber(), $scale+1);

    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function sqrtScale(?int $scale): string
    {

        $scale = $scale ?? $this->getScale();

        return ArithmeticProvider::squareRoot($this->abs()->getAsBaseTenRealNumber(), $scale);

    }

}