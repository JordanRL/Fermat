<?php

namespace Samsara\Fermat\Coordinates\Values;

use Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\ThreeDCoordinateInterface;
use Samsara\Fermat\Coordinates\Types\Coordinate;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Coordinates
 */
class SphericalCoordinate extends Coordinate implements ThreeDCoordinateInterface
{
    protected ?CartesianCoordinate $cachedCartesian = null;
    protected ?CylindricalCoordinate $cachedCylindrical = null;

    public function __construct($rho, $theta, $phi)
    {
        $data = [
            'rho' => $rho,
            'theta' => $theta,
            'phi' => $phi,
        ];

        parent::__construct($data);
    }

    public function getDistanceFromOrigin(): ImmutableDecimal
    {
        return $this->getAxis('rho');
    }

    public function getPlanarAngle(): ImmutableDecimal
    {
        return $this->getAxis('theta');
    }

    public function getPolarAngle(): ImmutableDecimal
    {
        return $this->getAxis('phi');
    }

    public function asCartesian(): CartesianCoordinate
    {
        if (is_null($this->cachedCartesian)) {
            $sinTheta = $this->getAxis('theta')->sin(12);
            $cosTheta = $this->getAxis('theta')->cos(12);
            $sinPhi = $this->getAxis('phi')->sin(12);
            $cosPhi = $this->getAxis('phi')->cos(12);

            $x = $this->getAxis('rho')->multiply($sinPhi)->multiply($cosTheta);
            $y = $this->getAxis('rho')->multiply($sinPhi)->multiply($sinTheta);
            $z = $this->getAxis('rho')->multiply($cosPhi);

            $this->cachedCartesian = new CartesianCoordinate($x->roundToScale(10), $y->roundToScale(10), $z->roundToScale(10));
        }

        return $this->cachedCartesian;
    }

    public function asCylindrical(): CylindricalCoordinate
    {
        if (is_null($this->cachedCylindrical)) {
            $sinPhi = $this->getAxis('phi')->sin(12);
            $cosPhi = $this->getAxis('phi')->cos(12);

            $rho = $this->getAxis('rho')->multiply($sinPhi);
            $theta = $this->getAxis('theta');
            $z = $this->getAxis('rho')->multiply($cosPhi);

            $this->cachedCylindrical = new CylindricalCoordinate($rho->roundToScale(10), $theta->roundToScale(10), $z->roundToScale(10));
        }

        return $this->cachedCylindrical;
    }

    public function asSpherical(): SphericalCoordinate
    {
        return $this;
    }

    public function distanceTo(CoordinateInterface $coordinate): ImmutableDecimal
    {
        return $this->asCartesian()->distanceTo($coordinate);
    }
}