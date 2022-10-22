<?php

namespace Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates;

use Samsara\Fermat\Coordinates\Values\CylindricalCoordinate;
use Samsara\Fermat\Coordinates\Values\SphericalCoordinate;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Coordinates
 */
interface ThreeDCoordinateInterface extends CoordinateInterface
{

    public function getPlanarAngle(): ImmutableDecimal;

    public function asSpherical(): SphericalCoordinate;

    public function asCylindrical(): CylindricalCoordinate;

}