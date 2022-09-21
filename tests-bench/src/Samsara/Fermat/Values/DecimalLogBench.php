<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Decimal;

class DecimalLogBench
{

    public function benchLn()
    {
        $ten = new ImmutableDecimal(10);
        $ten->ln();
    }

    public function benchLog10()
    {
        $ten = new ImmutableDecimal(50);
        $ten->log10();
    }

    public function benchExp()
    {
        $ten = new ImmutableDecimal(10);
        $ten->exp();
    }

    public function benchExpFloat()
    {
        $ten = new ImmutableDecimal('12.5');
        $ten->exp();
    }

}