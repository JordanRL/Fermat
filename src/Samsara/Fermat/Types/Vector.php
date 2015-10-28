<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Values\ValueTraits\CartesianVectorTrait;

abstract class Vector
{

    use CartesianVectorTrait;

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

}