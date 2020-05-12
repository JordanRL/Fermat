<?php

namespace Samsara\Fermat\Values\Geometry\CoordinateSystems;

use ReflectionException;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\ThreeDCoordinateInterface;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\TwoDCoordinateInterface;
use Samsara\Fermat\Types\Coordinate;
use Samsara\Fermat\Values\ImmutableDecimal;

class CartesianCoordinate extends Coordinate implements TwoDCoordinateInterface, ThreeDCoordinateInterface
{

    /**
     * CartesianCoordinate constructor.
     *
     * @param $x
     * @param null $y
     * @param null $z
     */
    public function __construct($x, $y = null, $z = null)
    {
        $data = [
            'x' => $x
        ];

        if (!is_null($y)) {
            $data['y'] = $y;
            if (!is_null($z)) {
                $data['z'] = $z;
            }
        } else if (!is_null($z)) {
            $data['y'] = $z;
        }

        parent::__construct($data);
    }

    /**
     * @param $axis
     *
     * @return ImmutableDecimal
     * @throws ReflectionException
     * @throws IntegrityConstraint
     */
    public function getAxis($axis): ImmutableDecimal
    {
        if (is_int($axis)) {
            $axisIndex = $axis;
        } else {
            $axisIndex = $this->parameters[$axis];
        }

        if (!$this->values->hasIndex($axisIndex)) {
            return Numbers::makeZero();
        }

        return $this->getAxisByIndex($axisIndex);
    }

    /**
     * @return ImmutableDecimal
     */
    public function getDistanceFromOrigin(): ImmutableDecimal
    {
        $x = 0;

        if ($this->numberOfDimensions() > 1) {
            $y = 0;
        } else {
            $y = null;
        }

        if ($this->numberOfDimensions() > 2) {
            $z = 0;
        } else {
            $z = null;
        }

        return $this->distanceTo(new CartesianCoordinate($x, $y, $z));
    }

    /**
     * @param CoordinateInterface $coordinate
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws ReflectionException
     */
    public function distanceTo(CoordinateInterface $coordinate): ImmutableDecimal
    {
        if (!($coordinate instanceof CartesianCoordinate)) {
            $coordinate = $coordinate->asCartesian();
        }

        /** @var ImmutableDecimal $n */
        $n = Numbers::makeZero();

        $firstValues = ($this->numberOfDimensions() >= $coordinate->numberOfDimensions()) ? $this->axesValues() : $coordinate->axesValues();
        $secondValues = ($this->numberOfDimensions() >= $coordinate->numberOfDimensions()) ? $coordinate->axesValues() : $this->axesValues();

        foreach ($firstValues as $index => $value) {
            $n = $n->add($secondValues[$index]->subtract($value)->pow(2));
        }

        $n = $n->sqrt();

        return $n;
    }

    /**
     * @return CartesianCoordinate
     */
    public function asCartesian(): CartesianCoordinate
    {
        return $this;
    }

    /**
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     */
    public function getPolarAngle(): ImmutableDecimal
    {
        if ($this->numberOfDimensions() === 2) {
            return $this->asPolar()->getPolarAngle();
        }

        if ($this->numberOfDimensions() === 3) {
            return $this->asSpherical()->getPolarAngle();
        }

        throw new IncompatibleObjectState(
            'Can only get a polar angle for a CartesianCoordinate of 2 or 3 dimensions.'
        );
    }

    /**
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     */
    public function getPlanarAngle(): ImmutableDecimal
    {
        if ($this->numberOfDimensions() === 2) {
            return $this->getPolarAngle();
        }

        if ($this->numberOfDimensions() === 3) {
            return $this->asSpherical()->getPlanarAngle();
        }

        throw new IncompatibleObjectState(
            'Can only get a planar angle for a CartesianCoordinate of 2 or 3 dimensions.'
        );
    }

    /**
     * @return SphericalCoordinate
     * @throws IncompatibleObjectState
     */
    public function asSpherical(): SphericalCoordinate
    {
        if ($this->numberOfDimensions() !== 3) {
            throw new IncompatibleObjectState(
                'Attempted to get CartesianCoordinate as SphericalCoordinate without the correct number of dimensions.'
            );
        }

        $rho = $this->getDistanceFromOrigin();
        $theta = '';
        $phi = '';

        return new SphericalCoordinate($rho, $theta, $phi);
    }

    /**
     * @return CylindricalCoordinate
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws ReflectionException
     */
    public function asCylindrical(): CylindricalCoordinate
    {
        if ($this->numberOfDimensions() !== 3) {
            throw new IncompatibleObjectState(
                'Attempted to get CartesianCoordinate as CylindricalCoordinate without the correct number of dimensions.'
            );
        }

        $z = $this->values->get(2);
        $r = $this->distanceTo(new CartesianCoordinate([0, 0, $z]));
        $theta = $this->values->get(1)->divide($this->values->get(0))->arctan();

        return new CylindricalCoordinate($r, $theta, $z);
    }

    /**
     * @return PolarCoordinate
     * @throws IncompatibleObjectState
     */
    public function asPolar(): PolarCoordinate
    {
        if ($this->numberOfDimensions() !== 2) {
            throw new IncompatibleObjectState(
                'Attempted to get CartesianCoordinate as PolarCoordinate without the correct number of dimensions.'
            );
        }

        $rho = $this->getDistanceFromOrigin();

        if ($rho->isEqual(0)) {
            throw new IncompatibleObjectState(
                'Attempted to convert a CartesianCoordinate at the origin into PolarCoordinate'
            );
        }

        /** @var ImmutableDecimal $theta */
        $theta = $this->values->get(0)->divide($rho)->arccos();
        if ($this->values->get(1)->isNegative()) {
            $theta = $theta->multiply(-1);
        }

        return new PolarCoordinate($rho, $theta);
    }
}