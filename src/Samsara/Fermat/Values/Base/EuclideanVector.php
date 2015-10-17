<?php

namespace Samsara\Fermat\Values\Base;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\BCProvider;

class EuclideanVector
{

    /**
     * @var NumberInterface[]
     */
    private $dimensions;

    /**
     * @param int[]|float[]|NumberInterface[] ...$dimensions
     */
    public function __construct(...$dimensions)
    {

        foreach ($dimensions as $axis => $value) {
            $this->dimensions[$axis] = Numbers::makeOrDont(Numbers::IMMUTABLE, $value);
        }

    }

    public function getAxis($axis)
    {
        if (!array_key_exists($axis, $this->dimensions)) {
            throw new \InvalidArgumentException('This vector does not have a '.$axis.' component.');
        }

        return $this->dimensions[$axis];
    }

    public function getMagnitude()
    {
        $total = Numbers::make(Numbers::MUTABLE, 0);

        foreach ($this->dimensions as $value) {
            $total->add(BCProvider::exp($value->getValue(), 2));
        }

        $magnitude = Numbers::make(Numbers::IMMUTABLE, BCProvider::squareRoot($total->getValue()));

        return $magnitude;
    }

}