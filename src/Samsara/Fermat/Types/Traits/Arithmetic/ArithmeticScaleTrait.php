<?php

declare(strict_types=1);

namespace Samsara\Fermat\Types\Traits\Arithmetic;

use Samsara\Fermat\Provider\ArithmeticProvider;

trait ArithmeticScaleTrait
{

    protected function addScale($num)
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        return ArithmeticProvider::add($this->asReal(), $num->asReal(), $scale);

    }

    protected function subtractScale($num)
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        return ArithmeticProvider::subtract($this->asReal(), $num->asReal(), $scale);

    }

    protected function multiplyScale($num)
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        return ArithmeticProvider::multiply($this->asReal(), $num->asReal(), $scale);

    }

    protected function divideScale($num, ?int $scale)
    {

        if (is_null($scale)) {
            $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();
        }

        return ArithmeticProvider::divide($this->asReal(), $num->asReal(), $scale);

    }

    protected function powScale($num)
    {

        $scale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();

        if (!$num->isWhole()) {
            $scale += 5;
            $exponent = $num->multiply($this->ln($scale));
            return $exponent->exp($scale)->truncateToScale($scale - 5)->getValue();
        }

        return ArithmeticProvider::pow($this->asReal(), $num->asReal(), $scale);

    }

    protected function sqrtScale(?int $scale)
    {

        $scale = $scale ?? $this->getScale();

        return ArithmeticProvider::squareRoot($this->getAsBaseTenRealNumber(), $scale);

    }

}