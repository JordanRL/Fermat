<?php

namespace Samsara\Fermat\Values\ValueTraits;

use Samsara\Fermat\Types\Tuple;
use Samsara\Fermat\Numbers;

trait CartesianVectorTrait
{

    /**
     * @var Tuple
     */
    private $dimensions;

    public function getAxis($axis)
    {
        return $this->dimensions->get($axis);
    }

    public function getMagnitude()
    {
        $total = Numbers::make(Numbers::MUTABLE, 0);

        foreach ($this->dimensions->all() as $value) {
            $total->add($value->exp(2));
        }

        $magnitude = Numbers::make(Numbers::IMMUTABLE, $total->sqrt()->getValue());

        return $magnitude;
    }

    protected function setDimensions(Tuple $dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }
}