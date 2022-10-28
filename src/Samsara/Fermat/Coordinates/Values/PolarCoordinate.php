<?php

namespace Samsara\Fermat\Coordinates\Values;

use Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\TwoDCoordinateInterface;
use Samsara\Fermat\Coordinates\Types\Coordinate;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Coordinates
 */
class PolarCoordinate extends Coordinate implements TwoDCoordinateInterface
{
    /** @var CartesianCoordinate */
    protected $cachedCartesian;

    public function __construct($rho, $theta)
    {
        $theta = Numbers::makeOrDont(Numbers::IMMUTABLE, $theta);

        $theta = $theta->continuousModulo(Numbers::makeTau($theta->getScale()));

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

    public function asCartesian(?int $scale = null): CartesianCoordinate
    {
        $scale = $scale ?? 10;
        $intScale = $scale + 2;

        if (is_null($this->cachedCartesian)) {
            $x = $this->getAxis('rho')->multiply($this->getAxis('theta')->cos($intScale))->roundToScale($scale);
            $y = $this->getAxis('rho')->multiply($this->getAxis('theta')->sin($intScale))->roundToScale($scale);

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