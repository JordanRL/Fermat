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

    public function getAxis($axis): ImmutableDecimal;

    public function axesValues(): array;

    public function getDistanceFromOrigin(): ImmutableDecimal;

    public function numberOfDimensions(): int;

    public function distanceTo(CoordinateInterface $coordinate): ImmutableDecimal;

    public function asCartesian(): CartesianCoordinate;

    public function getPolarAngle(): ImmutableDecimal;

}