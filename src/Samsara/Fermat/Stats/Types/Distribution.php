<?php

namespace Samsara\Fermat\Stats\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\NumberCollection;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Stats
 */
abstract class Distribution
{

    /**
     * @param int $sampleSize
     *
     * @return NumberCollection
     *
     * @codeCoverageIgnore
     */
    public function randomSample(int $sampleSize = 10): NumberCollection
    {
        $sample = new NumberCollection();

        for ($i = 1; $i < $sampleSize; $i++) {
            $sample->push($this->random());
        }

        return $sample;
    }

    /**
     * @param int|float|string|Decimal $min
     * @param int|float|string|Decimal $max
     * @param int                      $maxIterations
     *
     * @return ImmutableDecimal
     * @throws OptionalExit
     * @throws IntegrityConstraint
     *
     * @codeCoverageIgnore
     */
    public function rangeRandom($min = 0, $max = PHP_INT_MAX, int $maxIterations = 20): ImmutableDecimal
    {
        $i = 0;

        do {
            $randomNumber = $this->random();
            $i++;
        } while (($randomNumber->isGreaterThan($max) || $randomNumber->isLessThan($min)) && $i < $maxIterations);

        if ($randomNumber->isGreaterThan($max) || $randomNumber->isLessThan($min)) {
            throw new OptionalExit(
                'All random numbers generated were outside of the requested range',
                'Widen the acceptable range of random values, or allow the function to perform mor iterations',
                'A suitable random number, restricted by the $max (' . $max . ') and $min (' . $min . '), could not be found within ' . $maxIterations . ' iterations'
            );
        }

        return $randomNumber;
    }

    /**
     * @param int|float|string|Decimal $x
     * @param int                      $scale
     *
     * @return ImmutableDecimal
     */
    abstract public function cdf(int|float|string|Decimal $x, int $scale = 10): ImmutableDecimal;

    abstract public function getMean(): ImmutableDecimal;

    abstract public function getMedian(): ImmutableDecimal;

    abstract public function getMode(): ImmutableDecimal;

    abstract public function getVariance(): ImmutableDecimal;

    /**
     * @return ImmutableDecimal
     */
    abstract public function random(): ImmutableDecimal;

}