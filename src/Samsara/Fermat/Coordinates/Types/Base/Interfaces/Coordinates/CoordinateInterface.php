<?php

namespace Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates;

use Samsara\Fermat\Coordinates\Values\CartesianCoordinate;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Coordinates
 */
interface CoordinateInterface
{

    public function asCartesian(): CartesianCoordinate;

    public function axesValues(): array;

    public function distanceTo(CoordinateInterface $coordinate): ImmutableDecimal;

    public function getAxis($axis): ImmutableDecimal;

    public function getDistanceFromOrigin(): ImmutableDecimal;

    public function getPolarAngle(): ImmutableDecimal;

    public function numberOfDimensions(): int;

}