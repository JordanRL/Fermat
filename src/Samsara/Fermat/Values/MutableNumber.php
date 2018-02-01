<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Number;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Numbers;

class MutableNumber extends Number implements NumberInterface, DecimalInterface
{

    public function modulo($mod)
    {
        $oldBase = $this->convertForModification();

        return (new MutableNumber(bcmod($this->getValue(), $mod), $this->getPrecision()))->convertFromModification($oldBase);
    }

    public function continuousModulo($mod)
    {

        $mod = Numbers::makeOrDont(Numbers::IMMUTABLE, $mod);
        $oldNum = Numbers::make(Numbers::IMMUTABLE, $this->getValue());

        $multiple = $oldNum->divide($mod)->floor();

        $remainder = $oldNum->subtract($mod->multiply($multiple));

        return Numbers::make(Numbers::MUTABLE, $remainder->getValue());

    }

    /**
     * @param $value
     *
     * @return MutableNumber
     */
    protected function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

}