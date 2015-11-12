<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\Base\NumberInterface;
use Samsara\Fermat\Values\Base\VectorInterface;

abstract class Vector
{

    /**
     * @var Tuple
     */
    protected $dimensions;

    /**
     * @param Cartesian $end
     * @param Cartesian|null $start
     */
    public function __construct(Cartesian $end, Cartesian $start = null)
    {
        $dimensions = [];

        if (!is_null($start)) {
            if ($end->getDimensions()->getValue() == $start->getDimensions()->getValue()) {
                for ($i = 0;$i < $end->getDimensions()->getValue();$i++) {
                    $dimensions[] = $end->getAxis($i)->subtract($start->getAxis($i));
                }
            } else {
                throw new \InvalidArgumentException('A vector requires that the cartesian points describing the start and end be of the same dimensions.');
            }
        } else {
            $dimensions = $end->getAllAxes();
        }

        $this->setDimensions(new Tuple($dimensions));
    }

    public function getAxis($axis)
    {
        return $this->dimensions->get($axis);
    }

    public function getMagnitude()
    {
        $total = Numbers::make(Numbers::MUTABLE, 0);

        foreach ($this->dimensions->all() as $value) {
            $total->add($value->exp(2));
        }

        $magnitude = Numbers::make(Numbers::IMMUTABLE, $total->sqrt()->getValue());

        return $magnitude;
    }

    public function addVector(VectorInterface $vector)
    {
        $points = $this->dimensions->all();

        $newPoints = [];

        foreach ($points as $axis => $value) {
            $newPoints[] = $value->add($vector->getAxis($axis));
        }

        return $this->setDimensions(new Tuple(...$newPoints));
    }

    public function subtractVector(VectorInterface $vector)
    {
        $points = $this->dimensions->all();

        $newPoints = [];

        foreach ($points as $axis => $value) {
            $newPoints[] = $value->subtract($vector->getAxis($axis));
        }

        return $this->setDimensions(new Tuple(...$newPoints));
    }

    abstract public function multiplyVector(VectorInterface $vector);

    abstract public function divideVector(VectorInterface $vector);

    abstract public function dotProduct(VectorInterface $vector);

    public function addScalar(NumberInterface $number)
    {
        $points = $this->dimensions->all();

        $newPoints = [];

        foreach ($points as $values) {
            $newPoints[] = $values->add($number);
        }

        return $this->setDimensions(new Tuple(...$newPoints));
    }

    public function subtractScalar(NumberInterface $number)
    {
        $points = $this->dimensions->all();

        $newPoints = [];

        foreach ($points as $values) {
            $newPoints[] = $values->subtract($number);
        }

        return $this->setDimensions(new Tuple(...$newPoints));
    }

    public function multiplyScalar(NumberInterface $number)
    {
        $points = $this->dimensions->all();

        $newPoints = [];

        foreach ($points as $values) {
            $newPoints[] = $values->multiply($number);
        }

        return $this->setDimensions(new Tuple(...$newPoints));
    }

    public function divideScalar(NumberInterface $number)
    {
        $points = $this->dimensions->all();

        $newPoints = [];

        foreach ($points as $values) {
            $newPoints[] = $values->divide($number);
        }

        return $this->setDimensions(new Tuple(...$newPoints));
    }

    /**
     * @param Tuple $dimensions
     * @return VectorInterface
     */
    abstract protected function setDimensions(Tuple $dimensions);

}