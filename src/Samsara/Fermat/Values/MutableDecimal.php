<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Decimal;
use Samsara\Fermat\Numbers;

class MutableDecimal extends Decimal
{

    public function continuousModulo($mod)
    {

        $mod = Numbers::makeOrDont(Numbers::IMMUTABLE, $mod, $this->precision+1);
        $oldNum = Numbers::make(Numbers::IMMUTABLE, $this->getValue(), $this->precision+1);

        $multiple = $oldNum->divide($mod)->floor();

        $remainder = $oldNum->subtract($mod->multiply($multiple));

        return Numbers::make(Numbers::MUTABLE, $remainder->truncate($this->precision)->getValue());

    }

    /**
     * @param string $value
     *
     * @return MutableDecimal
     */
    protected function setValue(string $value, int $precision = null, int $base = 10)
    {
        $imaginary = false;

        if (strpos($value, 'i') !== false) {
            $value = str_replace('i', '', $value);
            $imaginary = true;
        }

        if ($base != 10) {
            $value = $this->convertValue($value, 10, $base);
        }

        if ($imaginary) {
            $value .= 'i';
        }

        if (is_null($precision)) {
            $precision = $this->getPrecision();
        }

        $this->value = $this->translateValue($value);

        return $this;
    }

    /**
     * @return bool
     */
    public function isComplex(): bool
    {
        return false;
    }

}