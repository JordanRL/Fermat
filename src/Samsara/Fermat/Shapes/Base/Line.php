<?php

namespace Samsara\Fermat\Shapes\Base;

use Ds\Set;
use Samsara\Fermat\Values\CartesianCoordinate;
use Samsara\Fermat\Values\ImmutableNumber;

class Line
{

    /**
     * @var ImmutableNumber
     */
    private $length;

    /**
     * @var Set
     */
    private $points;

    public function __construct(CartesianCoordinate $coordinate1, CartesianCoordinate $coordinate2)
    {
        $this->points = new Set();
        $this->points->add($coordinate1);
        $this->points->add($coordinate2);
        $this->length = $coordinate1->distanceTo($coordinate2);
    }

    public function length()
    {
        return $this->length;
    }

    public function points()
    {
        return $this->points->toArray();
    }

    public function pointsIterator()
    {
        return $this->points->getIterator();
    }

}