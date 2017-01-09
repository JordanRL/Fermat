<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Number;
use Samsara\Fermat\Values\Base\DecimalInterface;
use Samsara\Fermat\Values\Base\NumberInterface;
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

        $multiple = $this->divide($mod)->floor();

        $remainder = $this->subtract($mod->multiply($multiple));

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