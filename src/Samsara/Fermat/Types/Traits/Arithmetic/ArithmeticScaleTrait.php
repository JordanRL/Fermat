<?php

declare(strict_types=1);

namespace Samsara\Fermat\Types\Traits\Arithmetic;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

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

        return ArithmeticProvider::divide($this->asReal(), $num->asReal(), $scale);

    }

    /**
     * @param DecimalInterface $num
     * @return string
     * @throws MissingPackage
     * @throws IntegrityConstraint
     */
    protected function powScale(DecimalInterface $num): string
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        if (!$num->isWhole()) {
            $scale += 5;
            $exponent = $num->multiply($this->ln($scale));
            return $exponent->exp($scale)->truncateToScale($scale - 5)->getValue();
        }

        return ArithmeticProvider::pow($this->asReal(), $num->asReal(), $scale);

    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function sqrtScale(?int $scale): string
    {

        $scale = $scale ?? $this->getScale();

        return ArithmeticProvider::squareRoot($this->getAsBaseTenRealNumber(), $scale);

    }

}