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
    private $dimensions;

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

    abstract public function addVector(VectorInterface $vector);

    abstract public function subtractVector(VectorInterface $vector);

    abstract public function multiplyVector(VectorInterface $vector);

    abstract public function divideVector(VectorInterface $vector);

    abstract public function dotProduct(VectorInterface $vector);

    abstract public function addScalar(NumberInterface $number);

    abstract public function subtractScalar(NumberInterface $number);

    abstract public function multiplyScalar(NumberInterface $number);

    abstract public function divideScalar(NumberInterface $number);

    protected function setDimensions(Tuple $dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }

}