<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Coordinates;

use Samsara\Fermat\Values\Geometry\CoordinateSystems\CylindricalCoordinate;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\SphericalCoordinate;
use Samsara\Fermat\Values\ImmutableDecimal;

interface ThreeDCoordinateInterface extends CoordinateInterface
{

    public function getPlanarAngle(): ImmutableDecimal;

    public function asSpherical(): SphericalCoordinate;

    public function asCylindrical(): CylindricalCoordinate;

}