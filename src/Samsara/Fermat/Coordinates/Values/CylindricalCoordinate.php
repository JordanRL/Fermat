<?php

namespace Samsara\Fermat\Coordinates\Values;

use Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\ThreeDCoordinateInterface;
use Samsara\Fermat\Coordinates\Types\Coordinate;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Coordinates
 */
class CylindricalCoordinate extends Coordinate implements ThreeDCoordinateInterface
{
    protected ?CartesianCoordinate $cachedCartesian = null;
    protected ?SphericalCoordinate $cachedSpherical = null;

    public function __construct($r, $theta, $z)
    {
        $data = [
            'r' => $r,
            'theta' => $theta,
            'z' => $z,
        ];

        parent::__construct($data);
    }

    public function getDistanceFromOrigin(): ImmutableDecimal
    {
        return $this->getAxis('r')->pow(2)->add($this->getAxis('z')->pow(2))->sqrt();
    }

    public function getPlanarAngle(): ImmutableDecimal
    {
        return $this->getAxis('theta');
    }

    public function getPolarAngle(): ImmutableDecimal
    {
        return Numbers::makeZero();
    }

    public function getRho(): ImmutableDecimal
    {
        return $this->getAxis('r');
    }

    public function asCartesian(): CartesianCoordinate
    {
        if (is_null($this->cachedCartesian)) {
            $x = $this->getAxis('r')->multiply($this->getAxis('theta')->cos());
            $y = $this->getAxis('r')->multiply($this->getAxis('theta')->sin());
            $z = $this->getAxis('z');

            $this->cachedCartesian = new CartesianCoordinate($x, $y, $z);
        }

        return $this->cachedCartesian;
    }

    public function asCylindrical(): CylindricalCoordinate
    {
        return $this;
    }

    public function asSpherical(): SphericalCoordinate
    {
        if (is_null($this->cachedSpherical)) {
            $rho = $this->getAxis('r')->pow(2)->add($this->getAxis('z')->pow(2))->sqrt($this->getAxis('z')->getScale());
            $theta = $this->getAxis('theta');
            $phi = $this->getAxis('r')->divide($this->getAxis('z'))->arctan();

            $this->cachedSpherical = new SphericalCoordinate($rho, $theta, $phi);
        }

        return $this->cachedSpherical;
    }

    public function distanceTo(CoordinateInterface $coordinate): ImmutableDecimal
    {
        return $this->asCartesian()->distanceTo($coordinate);
    }
}