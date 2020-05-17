<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Decimal;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

class ImmutableDecimal extends Decimal
{

    public function continuousModulo($mod): DecimalInterface
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
     * @param string $value
     * @param int|null $precision
     * @param int $base
     *
     * @return ImmutableDecimal
     */
    protected function setValue(string $value, int $precision = null, int $base = 10)
    {
        $imaginary = false;

        $precision = $precision ?? $this->getPrecision();

        if (strpos($value, 'i') !== false) {
            $value = str_replace('i', '', $value);
            $imaginary = true;
        }

        if ($base !== 10 || $this->getBase() !== 10) {
            $base = $base === 10 ? $this->getBase() : $base;
            $value = $this->convertValue($value, 10, $base);
        }

        if ($imaginary) {
            $value .= 'i';
        }

        return new ImmutableDecimal($value, $precision, $base);
    }

}