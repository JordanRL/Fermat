<?php

namespace Samsara\Fermat\Coordinates\Types;

use Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Coordinates\Values\CartesianCoordinate;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Tuple;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Coordinates
 */
abstract class Coordinate implements CoordinateInterface
{

    /** @var array */
    protected $parameters;
    /** @var Tuple */
    protected $values;

    public function __construct(array $data)
    {
        $zeroIndexedData = [];

        foreach ($data as $axis => $value) {
            $this->parameters[$axis] = count($zeroIndexedData);

            if (is_string($value)) {
                $value = str_replace('i', '', $value);
            }

            if ($value instanceof Decimal && $value->isImaginary()) {
                $value = $value->getAsBaseTenRealNumber();
            }

            $zeroIndexedData[] = $value;
        }

        $this->values = new Tuple($zeroIndexedData);
    }

    public function getAxis($axis): ImmutableDecimal
    {
        return $this->values->get($this->parameters[$axis]);
    }

    public function axesValues(): array
    {
        return $this->values->all();
    }

    public function numberOfDimensions(): int
    {
        return $this->values->size();
    }

    protected function getAxisByIndex($axis): ImmutableDecimal
    {
        return $this->values->get($axis);
    }

    abstract public function asCartesian(): CartesianCoordinate;

    abstract public function distanceTo(CoordinateInterface $coordinate): ImmutableDecimal;

    abstract public function getDistanceFromOrigin(): ImmutableDecimal;

}