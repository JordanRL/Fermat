<?php

namespace Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates;

use Samsara\Fermat\Coordinates\Values\PolarCoordinate;

interface TwoDCoordinateInterface extends CoordinateInterface
{

    public function asPolar(): PolarCoordinate;

}