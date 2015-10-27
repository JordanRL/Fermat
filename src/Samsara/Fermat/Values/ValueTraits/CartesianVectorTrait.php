<?php

namespace Samsara\Fermat\Values\ValueTraits;

use Samsara\Fermat\Types\Tuple;

trait CartesianVectorTrait
{

    /**
     * @var Tuple
     */
    private $dimensions;

    protected function setDimensions(Tuple $dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }

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