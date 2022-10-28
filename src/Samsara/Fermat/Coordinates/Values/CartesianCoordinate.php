<?php

namespace Samsara\Fermat\Coordinates\Values;

use ReflectionException;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\ThreeDCoordinateInterface;
use Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\TwoDCoordinateInterface;
use Samsara\Fermat\Coordinates\Types\Coordinate;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Coordinates
 */
class CartesianCoordinate extends Coordinate implements TwoDCoordinateInterface, ThreeDCoordinateInterface
{

    /**
     * CartesianCoordinate constructor.
     *
     * @param      $x
     * @param null $y
     * @param null $z
     */
    public function __construct($x, $y = null, $z = null)
    {
        $data = [
            'x' => $x,
        ];

        if (!is_null($y)) {
            $data['y'] = $y;
            if (!is_null($z)) {
                $data['z'] = $z;
            }
        } elseif (!is_null($z)) {
            $data['y'] = $z;
        }

        parent::__construct($data);

        if (!array_key_exists('y', $this->parameters)) {
            $this->parameters['y'] = 1;
        }

        if (!array_key_exists('z', $this->parameters)) {
            $this->parameters['z'] = 2;
        }
    }

    /**
     * @param $axis
     *
     * @return ImmutableDecimal
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
     * @param int|null $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public function getDistanceFromOrigin(?int $scale = null): ImmutableDecimal
    {
        $scale = $scale ?? 10;
        $intScale = $scale + 2;

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

        return $this->distanceTo(new CartesianCoordinate($x, $y, $z), $intScale)->roundToScale($scale);
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
            'Can only get a polar angle for a CartesianCoordinate of 2 or 3 dimensions.',
            'Ensure the CartesianCoordinate has 2 or 3 dimensions.',
            'Cannot get the polar angle for a CartesianCoordinate unless it has exactly 2 or 3 dimensions.'
        );
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
            'Can only get a polar angle for a CartesianCoordinate of 2 or 3 dimensions.',
            'Ensure the CartesianCoordinate has 2 or 3 dimensions.',
            'Cannot get the polar angle for a CartesianCoordinate unless it has exactly 2 or 3 dimensions.'
        );
    }

    /**
     * @return CartesianCoordinate
     */
    public function asCartesian(): CartesianCoordinate
    {
        return $this;
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
                'Can only get CylindricalCoordinate for a CartesianCoordinate of 3 dimensions.',
                'Ensure the CartesianCoordinate has 3 dimensions.',
                'Cannot get the CylindricalCoordinate for a CartesianCoordinate unless it has exactly 3 dimensions.'
            );
        }

        $z = $this->getAxis('z');
        $r = $this->distanceTo(new CartesianCoordinate(0, 0, $z));
        $theta = $this->getAxis('y')->divide($this->getAxis('x'))->arctan();

        return new CylindricalCoordinate($r, $theta, $z);
    }

    /**
     * @param int|null $scale
     *
     * @return PolarCoordinate
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws OptionalExit
     */
    public function asPolar(?int $scale = null): PolarCoordinate
    {
        $scale = $scale ?? 10;
        $intScale = $scale + 3;

        $xAxis = new ImmutableDecimal($this->getAxis('x'), $intScale);
        $yAxis = new ImmutableDecimal($this->getAxis('y'), $intScale);

        if ($this->numberOfDimensions() !== 2) {
            throw new IncompatibleObjectState(
                'Can only get PolarCoordinate for a CartesianCoordinate of 2 dimensions.',
                'Ensure the CartesianCoordinate has 2 dimensions.',
                'Cannot get the PolarCoordinate for a CartesianCoordinate unless it has exactly 2 dimensions.'
            );
        }

        $rho = $this->getDistanceFromOrigin($intScale);

        if ($rho->isEqual(0)) {
            throw new OptionalExit(
                'Attempted to convert a CartesianCoordinate at the origin into PolarCoordinate',
                'Do not attempt to do this.',
                'The origin has an undefined polar angle in the polar coordinate system.'
            );
        }

        if ($xAxis->isEqual(0)) {
            $theta = Numbers::makePi($intScale)->divide(2);

            if ($yAxis->isNegative()) {
                $theta = $theta->multiply(-1);
            }
        } else {
            /** @var ImmutableDecimal $theta */
            $theta = $yAxis->divide($xAxis, $intScale)->arctan($intScale);
        }

        return new PolarCoordinate($rho->roundToScale($scale), $theta->roundToScale($scale));
    }

    /**
     * @return SphericalCoordinate
     * @throws IncompatibleObjectState
     */
    public function asSpherical(): SphericalCoordinate
    {
        if ($this->numberOfDimensions() !== 3) {
            throw new IncompatibleObjectState(
                'Can only get SphericalCoordinate for a CartesianCoordinate of 3 dimensions.',
                'Ensure the CartesianCoordinate has 3 dimensions.',
                'Cannot get the SphericalCoordinate for a CartesianCoordinate unless it has exactly 3 dimensions.'
            );
        }

        $rho = $this->getDistanceFromOrigin();
        $theta = $this->getAxis('y')->divide($this->getAxis('x'))->arctan();
        $phi = $this->getAxis('z')->divide($rho)->arccos();

        return new SphericalCoordinate($rho, $theta, $phi);
    }

    /**
     * @param CoordinateInterface $coordinate
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws ReflectionException
     */
    public function distanceTo(CoordinateInterface $coordinate, ?int $scale = null): ImmutableDecimal
    {
        $scale = $scale ?? 10;
        $intScale = $scale + 2;

        if (!($coordinate instanceof CartesianCoordinate)) {
            $coordinate = $coordinate->asCartesian();
        }

        $n = Numbers::makeZero($intScale);

        $firstValues = ($this->numberOfDimensions() >= $coordinate->numberOfDimensions()) ? $this->axesValues() : $coordinate->axesValues();
        $secondValues = ($this->numberOfDimensions() >= $coordinate->numberOfDimensions()) ? $coordinate->axesValues() : $this->axesValues();

        foreach ($firstValues as $index => $value) {
            $n = $n->add($secondValues[$index]->subtract($value)->pow(2));
        }

        return $n->sqrt($intScale)->roundToScale($scale);
    }
}