<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Values\ValueTraits\CartesianVectorTrait;

class Vector3
{

    use CartesianVectorTrait;

    public function getX()
    {
        return $this->dimensions->get(0);
    }

    public function getY()
    {
        return $this->dimensions->get(1);
    }

    public function getZ()
    {
        return $this->dimensions->get(2);
    }


}