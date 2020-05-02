<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate;
use Samsara\Fermat\Values\ImmutableDecimal;

abstract class Coordinate implements CoordinateInterface
{

    /** @var array */
    protected $parameters;
    /** @var Tuple  */
    protected $values;

    public function __construct(array $data)
    {
        $zeroIndexedData = [];

        foreach ($data as $axis => $value) {
            $this->parameters[$axis] = count($zeroIndexedData);
            $zeroIndexedData[] = $value;
        }

        $this->values = new Tuple($zeroIndexedData);
    }

    public function getAxis($axis): ImmutableDecimal
    {
        return $this->values->get($this->parameters[$axis]);
    }

    public function numberOfDimensions(): int
    {
        return $this->values->size();
    }

    public function axesValues(): array
    {
        return $this->values->all();
    }

    abstract public function getDistanceFromOrigin(): ImmutableDecimal;

    abstract public function distanceTo(CoordinateInterface $coordinate): ImmutableDecimal;

    abstract public function asCartesian(): CartesianCoordinate;

}