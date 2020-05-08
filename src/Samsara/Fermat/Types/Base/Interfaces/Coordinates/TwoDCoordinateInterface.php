<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Coordinates;

use Samsara\Fermat\Values\Geometry\CoordinateSystems\PolarCoordinate;

interface TwoDCoordinateInterface extends CoordinateInterface
{

    public function asPolar(): PolarCoordinate;

}