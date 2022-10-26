<?php

namespace Samsara\Fermat\Stats\Types;

use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\NumberCollection;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Stats
 */
abstract class Distribution
{

    /**
     * @param int|float|string|Decimal $x
     * @param int $scale
     * @return ImmutableDecimal
     */
    abstract public function cdf(int|float|string|Decimal $x, int $scale = 10): ImmutableDecimal;

    /**
     * @return ImmutableDecimal
     */
    abstract public function random(): ImmutableDecimal;

    /**
     * @param int $sampleSize
     * @return NumberCollection
     *
     * @codeCoverageIgnore
     */
    public function randomSample(int $sampleSize = 10): NumberCollection
    {
        $sample = new NumberCollection();

        for ($i = 1;$i < $sampleSize;$i++) {
            $sample->push($this->random());
        }

        return $sample;
    }

    /**
     * @param $min
     * @param $max
     * @param int $maxIterations
     * @return ImmutableDecimal
     */
    abstract public function rangeRandom($min = 0, $max = PHP_INT_MAX, int $maxIterations = 20): ImmutableDecimal;

}