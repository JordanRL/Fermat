<?php

namespace Samsara\Fermat\Values\Geometry\CoordinateSystems;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\ThreeDCoordinateInterface;
use Samsara\Fermat\Types\Coordinate;
use Samsara\Fermat\Values\ImmutableDecimal;

class CylindricalCoordinate extends Coordinate implements ThreeDCoordinateInterface
{
    /** @var CartesianCoordinate */
    protected $cachedCartesian;
    /** @var SphericalCoordinate */
    protected $cachedSpherical;

    public function __construct($r, $theta, $z)
    {
        $data = [
            'r' => $r,
            'theta' => $theta,
            'z' => $z
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
        return Numbers::makeZero();
    }

    public function getPlanarAngle(): ImmutableDecimal
    {
        return $this->getAxis('theta');
    }

    public function asCartesian(): CartesianCoordinate
    {
        if (is_null($this->cachedCartesian)) {
            $x = $this->getAxis('rho')->multiply($this->getAxis('theta')->cos());
            $y = $this->getAxis('rho')->multiply($this->getAxis('theta')->sin());
            $z = $this->getAxis('z');

            $this->cachedCartesian = new CartesianCoordinate($x, $y, $z);
        }

        return $this->cachedCartesian;
    }

    public function asSpherical(): SphericalCoordinate
    {
        if (is_null($this->cachedSpherical)) {
            $rho = $this->getAxis('rho')->pow(2)->add($this->getAxis('z')->pow(2))->sqrt($this->getAxis('z')->getScale());
            $theta = $this->getAxis('theta');
            $phi = $this->getAxis('z')->divide($rho);

            $this->cachedSpherical = new SphericalCoordinate($rho, $theta, $phi);
        }

        return $this->cachedSpherical;
    }

    public function asCylindrical(): CylindricalCoordinate
    {
        return $this;
    }
}