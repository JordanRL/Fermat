<?php

namespace Samsara\Fermat\Values\Geometry\CoordinateSystems;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\TwoDCoordinateInterface;
use Samsara\Fermat\Types\Coordinate;
use Samsara\Fermat\Values\ImmutableDecimal;

class PolarCoordinate extends Coordinate implements TwoDCoordinateInterface
{
    /** @var CartesianCoordinate */
    protected $cachedCartesian;

    public function __construct($rho, $theta)
    {
        $theta = Numbers::makeOrDont(Numbers::IMMUTABLE, $theta);

        $theta = $theta->continuousModulo(Numbers::TAU);

        if ($theta->isNegative()) {
            $theta = Numbers::makeTau($theta->getScale())->add($theta);
        }

        $data = [
            'rho' => $rho,
            'theta' => $theta
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

    public function asCartesian(): CartesianCoordinate
    {
        if (is_null($this->cachedCartesian)) {
            $x = $this->getAxis('rho')->multiply($this->getAxis('theta')->cos());
            $y = $this->getAxis('rho')->multiply($this->getAxis('theta')->sin());

            $this->cachedCartesian = new CartesianCoordinate($x, $y);
        }

        return $this->cachedCartesian;
    }

    public function getPolarAngle(): ImmutableDecimal
    {
        return $this->getAxis('theta');
    }

    public function asPolar(): PolarCoordinate
    {
        return $this;
    }

}