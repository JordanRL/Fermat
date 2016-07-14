<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Number;
use Samsara\Fermat\Values\Base\NumberInterface;

class ImmutableNumber extends Number implements NumberInterface
{

    public function modulo($mod)
    {
        $oldBase = $this->convertForModification();

        return (new ImmutableNumber(bcmod($this->getValue(), $mod), $this->getPrecision()))->convertFromModification($oldBase);
    }

    public function continuousModulo($mod)
    {

        $mod = Numbers::makeOrDont(Numbers::IMMUTABLE, $mod);

        $multiple = $this->divide($mod)->floor();

        $remainder = $this->subtract($mod->multiply($multiple));

        return $remainder;

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