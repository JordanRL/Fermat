<?php

namespace Samsara\Fermat\Values\Geometry\CoordinateSystems;

use Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\ThreeDCoordinateInterface;
use Samsara\Fermat\Types\Coordinate;
use Samsara\Fermat\Values\ImmutableDecimal;

class SphericalCoordinate extends Coordinate implements ThreeDCoordinateInterface
{
    /** @var CartesianCoordinate */
    protected $cachedCartesian;
    /** @var CylindricalCoordinate */
    protected $cachedCylindrical;

    public function __construct($rho, $theta, $phi)
    {
        $data = [
            'rho' => $rho,
            'theta' => $theta,
            'phi' => $phi
        ];

        parent::__construct($data);
    }

    public function getDistanceFromOrigin(): ImmutableDecimal
    {
        return $this->getAxis('rho');
    }

    public function distanceTo(CoordinateInterface $coordinate): ImmutableDecimal
    {
        return $this->asCartesian()->distanceTo($coordinate);
    }

    public function getPolarAngle(): ImmutableDecimal
    {
        return $this->getAxis('phi');
    }

    public function getPlanarAngle(): ImmutableDecimal
    {
        return $this->getAxis('theta');
    }

    public function asCartesian(): CartesianCoordinate
    {
        if (is_null($this->cachedCartesian)) {
            $sinTheta = $this->getAxis('theta')->sin();
            $cosTheta = $this->getAxis('theta')->cos();
            $sinPhi = $this->getAxis('phi')->sin();
            $cosPhi = $this->getAxis('phi')->cos();

            $x = $this->getAxis('rho')->multiply($sinPhi)->multiply($cosTheta);
            $y = $this->getAxis('rho')->multiply($sinPhi)->multiply($sinTheta);
            $z = $this->getAxis('rho')->multiply($cosPhi);

            $this->cachedCartesian = new CartesianCoordinate($x, $y, $z);
        }

        return $this->cachedCartesian;
    }

    public function asSpherical(): SphericalCoordinate
    {
        return $this;
    }

    public function asCylindrical(): CylindricalCoordinate
    {
        if (is_null($this->cachedCylindrical)) {
            $sinPhi = $this->getAxis('phi')->sin();
            $cosPhi = $this->getAxis('phi')->cos();

            $rho = $this->getAxis('rho')->multiply($sinPhi);
            $theta = $this->getAxis('theta');
            $z = $this->getAxis('rho')->multiply($cosPhi);

            $this->cachedCylindrical = new CylindricalCoordinate($rho, $theta, $z);
        }

        return $this->cachedCylindrical;
    }
}