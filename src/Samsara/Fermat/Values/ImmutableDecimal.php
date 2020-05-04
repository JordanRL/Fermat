<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Decimal;

class ImmutableDecimal extends Decimal
{

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
     * @param int $precision
     * @param int $base
     *
     * @return ImmutableDecimal
     */
    protected function setValue(string $value, int $precision = null, int $base = 10)
    {
        $imaginary = false;

        if (strpos($value, 'i') !== false) {
            $value = str_replace('i', '', $value);
            $imaginary = true;
        }

        if ($base != 10 || $this->getBase() != 10) {
            $base = $base == 10 ? $this->getBase() : $base;
            $value = $this->convertValue($value, 10, $base);
        }

        if ($imaginary) {
            $value .= 'i';
        }

        if (is_null($precision)) {
            $precision = $this->getPrecision();
        }

        return new ImmutableDecimal($value, $precision, $base);
    }

}