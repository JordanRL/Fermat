<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Provider\BCProvider;
use Samsara\Fermat\Types\EuclideanVector;
use Samsara\Fermat\Numbers;

class Euclidean3DVector extends EuclideanVector
{

    public function __construct($endX, $endY, $endZ, $startX = 0, $startY = 0, $startZ = 0)
    {
        $dimensions['x'] = Numbers::make(Numbers::IMMUTABLE, BCProvider::subtract($endX, $startX));
        $dimensions['y'] = Numbers::make(Numbers::IMMUTABLE, BCProvider::subtract($endY, $startY));
        $dimensions['z'] = Numbers::make(Numbers::IMMUTABLE, BCProvider::subtract($endZ, $startZ));

        parent::__construct($dimensions);
    }

    public function getX()
    {
        return $this->getAxis('x');
    }

    public function getY()
    {
        return $this->getAxis('y');
    }

    public function getZ()
    {
        return $this->getAxis('z');
    }

}