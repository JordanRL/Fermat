<?php

namespace Samsara\Fermat\Stats\Types;

use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

abstract class DiscreteDistribution extends Distribution
{

    abstract public function pmf(int|float|string|Decimal $k, int $scale = 10): ImmutableDecimal;

}