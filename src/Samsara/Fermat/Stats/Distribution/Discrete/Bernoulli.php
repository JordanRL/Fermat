<?php

namespace Samsara\Fermat\Stats\Distribution\Discrete;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\RandomMode;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\RandomProvider;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Stats\Types\DiscreteDistribution;

class Bernoulli extends DiscreteDistribution
{

    private ImmutableDecimal $independentProbability;

    public function __construct(int|float|string|Decimal $p)
    {
        /** @var ImmutableDecimal $p */
        $p = Numbers::makeOrDont(Numbers::IMMUTABLE, $p);

        $this->independentProbability = $p;
    }

    public function getMean(): ImmutableDecimal
    {
        return $this->independentProbability;
    }

    public function getMedian(): ImmutableDecimal
    {
        if ($this->independentProbability->isLessThanOrEqualTo('0.5')) {
            return Numbers::makeZero($this->independentProbability->getScale());
        } else {
            return Numbers::makeOne($this->independentProbability->getScale());
        }
    }

    public function getMode(): ImmutableDecimal
    {
        return $this->getMedian();
    }

    public function getVariance(): ImmutableDecimal
    {
        return $this->independentProbability->subtract($this->independentProbability->pow(2));
    }

    /**
     * @inheritDoc
     */
    public function cdf(int|float|string|Decimal $k, int $scale = 10): ImmutableDecimal
    {
        /** @var ImmutableDecimal $k */
        $k = Numbers::makeOrDont(Numbers::IMMUTABLE, $k);

        if ($k->isLessThan(0)) {
            return Numbers::makeZero($this->independentProbability->getScale());
        } elseif ($k->isGreaterThanOrEqualTo(1)) {
            return Numbers::makeOne($this->independentProbability->getScale());
        } else {
            return Numbers::makeOne()
                ->subtract($this->independentProbability)
                ->roundToScale($this->independentProbability->getScale());
        }
    }

    public function pmf(int|float|string|Decimal $k, int $scale = 10): ImmutableDecimal
    {
        /** @var ImmutableDecimal $k */
        $k = Numbers::makeOrDont(Numbers::IMMUTABLE, $k);

        if (!$k->isInt() || (!$k->isEqual(0) && !$k->isEqual(1))) {
            throw new IntegrityConstraint(
                'For a Bernoulli distribution, the pmf(k) requires k to be either 0 or 1',
                'Provide a valid k'
            );
        }

        return $k->isEqual(1) ?
            $this->independentProbability :
            Numbers::makeOne()
                ->subtract($this->independentProbability)
                ->roundToScale($this->independentProbability->getScale());
    }

    /**
     * @inheritDoc
     */
    public function random(): ImmutableDecimal
    {
        $randomDecimal = RandomProvider::randomDecimal($this->independentProbability->getScale(), RandomMode::Speed);

        return $randomDecimal->isLessThanOrEqualTo($this->independentProbability) ?
            Numbers::makeOne($this->independentProbability->getScale()) :
            Numbers::makeZero($this->independentProbability->getScale());
    }
}