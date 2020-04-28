<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Decimal;

class ImmutableDecimal extends Decimal
{

    public function modulo($mod)
    {
        $oldBase = $this->convertForModification();

        return (new ImmutableDecimal(bcmod($this->getValue(), $mod), $this->getPrecision(), $this->getBase()))->convertFromModification($oldBase);
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
     * @return bool
     */
    public function isComplex(): bool
    {
        return false;
    }

    /**
     * @param string $value
     *
     * @return ImmutableDecimal
     */
    protected function setValue(string $value)
    {
        return new ImmutableDecimal($value, $this->getPrecision(), $this->getBase());
    }

}