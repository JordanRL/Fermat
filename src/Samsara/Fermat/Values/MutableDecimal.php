<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Decimal;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

class MutableDecimal extends Decimal
{

    public function continuousModulo($mod): DecimalInterface
    {

        $mod = Numbers::makeOrDont(Numbers::IMMUTABLE, $mod, $this->scale+1);
        $oldNum = Numbers::make(Numbers::IMMUTABLE, $this->getValue(), $this->scale+1);

        $multiple = $oldNum->divide($mod)->floor();

        $remainder = $oldNum->subtract($mod->multiply($multiple));

        return Numbers::make(Numbers::MUTABLE, $remainder->truncate($this->scale)->getValue());

    }

    /**
     * @param string $value
     *
     * @return MutableDecimal
     */
    protected function setValue(string $value, int $scale = null, int $base = 10)
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

        if (is_null($scale)) {
            $this->scale = $this->getScale();
        }

        $this->value = $this->translateValue($value);

        return $this;
    }

}