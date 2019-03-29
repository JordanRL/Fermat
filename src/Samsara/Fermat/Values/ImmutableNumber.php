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

        $oldPrecision = $this->precision;
        $newPrecision = $oldPrecision+1;

        $this->precision = $newPrecision;

        $mod = Numbers::makeOrDont(Numbers::IMMUTABLE, $mod, $newPrecision);

        $multiple = $this->divide($mod)->floor();

        $remainder = $this->subtract($mod->multiply($multiple));

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