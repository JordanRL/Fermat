<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Number;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\NumberInterface;

class ImmutableNumber extends Number implements NumberInterface, DecimalInterface
{

    public function modulo($mod)
    {
        $oldBase = $this->convertForModification();

        return (new ImmutableNumber(bcmod($this->getValue(), $mod), $this->getPrecision(), $this->getBase()))->convertFromModification($oldBase);
    }

    public function continuousModulo($mod)
    {

        if (is_object($mod) && method_exists($mod, 'getPrecision')) {
            $precision = ($this->getPrecision() < $mod->getPrecision()) ? $mod->getPrecision() : $this->getPrecision();
        } else {
            $precision = $this->getPrecision();
        }

        $oldPrecision = $this->precision;
        $newPrecision = $precision+1;

        $this->precision = $newPrecision;

        if (is_object($mod) && method_exists($mod, 'truncateToPrecision')) {
            $mod = $mod->truncateToPrecision($newPrecision);
        } else {
            $mod = Numbers::make(Numbers::IMMUTABLE, $mod, $newPrecision);
        }

        $multiple = $this->divide($mod)->floor();

        $remainder = $this->subtract($mod->multiply($multiple));

        $this->precision = $oldPrecision;

        return $remainder->truncateToPrecision($oldPrecision);

    }

    /**
     * @param $value
     *
     * @return ImmutableNumber
     */
    protected function setValue($value)
    {
        return new ImmutableNumber($value, $this->getPrecision(), $this->getBase());
    }

}