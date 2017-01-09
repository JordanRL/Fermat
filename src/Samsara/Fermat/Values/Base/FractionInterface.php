<?php

namespace Samsara\Fermat\Values\Base;

use Samsara\Fermat\Types\Fraction;

interface FractionInterface
{

    /**
     * @return Fraction
     */
    public function simplify();

}