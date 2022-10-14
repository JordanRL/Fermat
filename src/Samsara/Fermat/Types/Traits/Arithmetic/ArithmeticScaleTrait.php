<?php

declare(strict_types=1);

namespace Samsara\Fermat\Types\Traits\Arithmetic;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

/**
 *
 */
trait ArithmeticScaleTrait
{

    /**
     * @param DecimalInterface $num
     * @return string
     */
    protected function addScale(DecimalInterface $num): string
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        return ArithmeticProvider::add($this->asReal(), $num->asReal(), $scale);

    }

    /**
     * @param DecimalInterface $num
     * @return string
     */
    protected function subtractScale(DecimalInterface $num): string
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        return ArithmeticProvider::subtract($this->asReal(), $num->asReal(), $scale);

    }

    /**
     * @param DecimalInterface $num
     * @return string
     */
    protected function multiplyScale(DecimalInterface $num): string
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        return ArithmeticProvider::multiply($this->asReal(), $num->asReal(), $scale);

    }

    /**
     * @param DecimalInterface $num
     * @param int|null $scale
     * @return string
     */
    protected function divideScale(DecimalInterface $num, ?int $scale): string
    {

        if (is_null($scale)) {
            $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();
        }

        $scale = $scale + $this->numberOfLeadingZeros() + $num->numberOfLeadingZeros();

        return ArithmeticProvider::divide($this->asReal(), $num->asReal(), $scale+1);

    }

    /**
     * @param DecimalInterface $num
     * @return string
     * @throws IntegrityConstraint
     */
    protected function powScale(DecimalInterface $num): string
    {

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

        return ArithmeticProvider::pow($this->asReal(), $num->asReal(), $scale+1);

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