<?php

namespace Samsara\Fermat\Stats\Distribution\Discrete;

use Samsara\Fermat\Core\Enums\RandomMode;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\RandomProvider;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Stats\Provider\StatsProvider;
use Samsara\Fermat\Stats\Types\DiscreteDistribution;

class Binomial extends DiscreteDistribution
{

    private ImmutableDecimal $independentProbability;
    private ImmutableDecimal $trials;

    public function __construct(int|float|string|Decimal $p, int|float|string|Decimal $n)
    {
        /** @var ImmutableDecimal $p */
        $p = Numbers::makeOrDont(Numbers::IMMUTABLE, $p);
        /** @var ImmutableDecimal $n */
        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

        $this->independentProbability = $p;
        $this->trials = $n;
    }

    public function getMean(): ImmutableDecimal
    {
        return $this->trials->multiply($this->independentProbability);
    }

    public function getMedian(): ImmutableDecimal
    {
        return $this->getMean()->floor();
    }

    public function getMode(): ImmutableDecimal
    {
        return $this->trials->add(1)->multiply($this->independentProbability)->floor()->subtract(1);
    }

    public function getVariance(): ImmutableDecimal
    {
        return $this->trials->multiply($this->independentProbability)->multiply((new ImmutableDecimal(1))->subtract($this->independentProbability));
    }

    /**
     * @inheritDoc
     */
    public function cdf(float|int|Decimal|string $x, int $scale = 10): ImmutableDecimal
    {
        /** @var ImmutableDecimal $k */
        $k = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);
        $cdf = Numbers::makeZero();

        for ($i = 0; $k->isGreaterThanOrEqualTo($i); $i++) {
            $cdf = $cdf->add($this->pmf($i));
        }

        return $cdf;
    }

    public function pmf(float|int|Decimal|string $k, int $scale = 10): ImmutableDecimal
    {
        return StatsProvider::binomialCoefficient($this->trials, $k)->multiply(
            $this->independentProbability->pow($k)
        )->multiply(
            (new ImmutableDecimal(1))->subtract($this->independentProbability)->pow($this->trials->subtract($k))
        );
    }

    /**
     * @inheritDoc
     */
    public function random(): ImmutableDecimal
    {
        $successes = Numbers::makeZero();

        for ($i = 0; $i <= $this->trials->asInt(); $i++) {
            $randomDecimal = RandomProvider::randomDecimal($this->independentProbability->getScale(), RandomMode::Speed);
            $adj = $randomDecimal->isLessThanOrEqualTo($this->independentProbability) ?
                Numbers::makeOne($this->independentProbability->getScale()) :
                Numbers::makeZero($this->independentProbability->getScale());
            $successes = $successes->add($adj);
        }

        return $successes;
    }
}