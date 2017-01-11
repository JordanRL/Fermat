<?php

namespace Samsara\Fermat\Shapes\Base;

use Ds\Map;
use Ds\Set;
use Samsara\Fermat\Values\CartesianCoordinate;

class TwoDimensionNonCurved
{
    /** @var Set  */
    private $lines;

    /** @var Set  */
    private $points;

    /** @var Map  */
    private $linesByPoint;

    /** @var bool */
    private $closed;

    public function __construct(Line ...$lines)
    {
        $this->lines = new Set($lines);
        $this->points = new Set();
        $this->linesByPoint = new Map();

        foreach ($lines as $line) {
            /** @var CartesianCoordinate $point */
            foreach ($line->pointsIterator() as $point) {
                if (!$this->points->contains($point)) {
                    $this->points->add($point);
                }

                if (!$this->linesByPoint->hasKey($point)) {
                    $this->linesByPoint->put($point, new Set());
                }

                if (!$this->linesByPoint->get($point)->contains($line)) {
                    $this->linesByPoint->get($point)->add($line);
                }
            }
        }

        $this->closed = true;

        /** @var Set $pointLines */
        foreach ($this->linesByPoint as $pointLines) {
            if ($pointLines->count() < 2) {
                $this->closed = false;
                break;
            }
        }
    }

    public function isClosed()
    {
        return $this->closed;
    }

}