<?php

namespace Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates;

use Samsara\Fermat\Coordinates\Values\PolarCoordinate;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Coordinates
 */
interface TwoDCoordinateInterface extends CoordinateInterface
{

    public function asPolar(): PolarCoordinate;

}