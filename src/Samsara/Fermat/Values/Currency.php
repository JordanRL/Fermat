<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Types\Number;

class Currency extends Number implements NumberInterface, DecimalInterface
{
    public function modulo($mod)
    {
        $oldBase = $this->convertForModification();

        return (new Currency(bcmod($this->getValue(), $mod), $this->getPrecision(), $this->getBase()))->convertFromModification($oldBase);
    }

    public function continuousModulo($mod)
    {
        $mod = Numbers::makeOrDont(Numbers::IMMUTABLE, $mod);

        $multiple = $this->divide($mod)->floor();

        $remainder = $this->subtract($mod->multiply($multiple));

        return $remainder;
    }

    public function compoundInterest($interest, $periods)
    {

        $interest = Numbers::makeOrDont(Numbers::IMMUTABLE, $interest);
        $periods = Numbers::makeOrDont(Numbers::IMMUTABLE, $periods);
        $e = Numbers::makeE();

        return $this->setValue($this->multiply($e->pow($periods->multiply($interest))));

    }

    /**
     * @param $value
     *
     * @return Currency
     */
    protected function setValue($value)
    {
        return new Currency($value, $this->getPrecision(), $this->getBase());
    }

}