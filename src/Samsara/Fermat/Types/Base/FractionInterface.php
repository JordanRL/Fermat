<?php

namespace Samsara\Fermat\Types\Base;

use Samsara\Fermat\Types\Fraction;

interface FractionInterface
{

    /**
     * @return Fraction
     */
    public function simplify();

}