<?php

namespace Samsara\Fermat\Stats\Types;

use Samsara\Fermat\Core\Types\NumberCollection;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Stats
 */
abstract class Distribution
{

    abstract public function random(): ImmutableDecimal;

    /**
     * @param int $sampleSize
     * @return NumberCollection
     */
    public function randomSample(int $sampleSize = 10): NumberCollection
    {
        $sample = new NumberCollection();

        for ($i = 1;$i < $sampleSize;$i++) {
            $sample->push($this->random());
        }

        return $sample;
    }

     abstract public function rangeRandom($min = 0, $max = PHP_INT_MAX, int $maxIterations = 20): ImmutableDecimal;

}