<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Cartesian;
use Samsara\Fermat\Types\Tuple;
use Samsara\Fermat\Types\Vector;
use Samsara\Fermat\Values\Base\VectorInterface;

class ImmutableVector extends Vector implements VectorInterface
{

    protected function setDimensions(Tuple $dimensions)
    {
        $points = $dimensions->all();

        $cartesian = new Cartesian(...$points);

        return new ImmutableVector($cartesian);
    }

}