<?php

namespace Samsara\Fermat\Values;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Tuple;
use Samsara\Fermat\Types\Base\CoordinateInterface;

class CartesianCoordinate extends Tuple implements CoordinateInterface
{

    /**
     * @var array
     */
    protected $axes;

    public function __construct(array $data)
    {
        $zeroIndexedData = [];

        foreach ($data as $axis => $value) {
            $this->axes[$axis] = count($zeroIndexedData);
            $zeroIndexedData[] = $value;
        }

        parent::__construct($zeroIndexedData);
    }

    public function getAxis($axis): ImmutableNumber
    {
        return $this->get($this->axes[$axis]);
    }

    public function numberOfDimensions(): int
    {
        return $this->size();
    }

    public function axisValues(): array
    {
        return $this->all();
    }

    public function distanceTo(CoordinateInterface $coordinate): ImmutableNumber
    {
        if (!($coordinate instanceof CartesianCoordinate)) {
            throw new IntegrityConstraint(
                'Must be the same coordinate system',
                'Only attempt to get distance between coordinates that use the same coordinate system',
                'Distance cannot be calculated between coordinates that use different coordinate systems because the properties necessary to convert them cannot be assumed'
            );
        }

        if ($this->size() != $coordinate->size()) {
            throw new IntegrityConstraint(
                'Must have same dimensionality',
                'Check dimensionality of both coordinates before getting distance',
                'Coordinates must share the same number of axes in order for a distance to be calculated'
            );
        }

        /** @var ImmutableNumber $n */
        $n = Numbers::makeZero();

        foreach ($this->all() as $index => $value) {
            $n = $n->add($coordinate->get($index)->subtract($value)->pow(2));
        }

        $n = $n->sqrt();

        return $n;
    }

}