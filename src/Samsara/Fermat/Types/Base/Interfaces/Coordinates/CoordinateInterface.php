<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Coordinates;

use Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate;
use Samsara\Fermat\Values\ImmutableDecimal;

interface CoordinateInterface
{

    public function getAxis($axis): ImmutableDecimal;

    public function axesValues(): array;

    public function getDistanceFromOrigin(): ImmutableDecimal;

    public function numberOfDimensions(): int;

    public function distanceTo(CoordinateInterface $coordinate): ImmutableDecimal;

    public function asCartesian(): CartesianCoordinate;

    public function getPolarAngle(): ImmutableDecimal;

}