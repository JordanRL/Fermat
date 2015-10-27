<?php

namespace Samsara\Fermat\Values\ValueTraits;

use Samsara\Fermat\Types\Tuple;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\Base\NumberInterface;

trait CartesianVectorTrait
{

    /**
     * @var Tuple
     */
    private $dimensions;

    /**
     * @param int[]|float[]|NumberInterface[] ...$dimensions
     */
    public function __construct(...$dimensions)
    {

        $dimensions = new Tuple($dimensions);

        $this->setDimensions($dimensions);

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