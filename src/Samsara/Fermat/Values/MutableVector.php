<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Tuple;
use Samsara\Fermat\Types\Vector;
use Samsara\Fermat\Values\Base\VectorInterface;

class MutableVector extends Vector implements VectorInterface
{

    protected function setDimensions(Tuple $dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }

}