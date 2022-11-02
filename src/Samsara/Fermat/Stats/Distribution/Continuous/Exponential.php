<?php

namespace Samsara\Fermat\Stats\Distribution\Continuous;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\RandomProvider;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Stats\Types\ContinuousDistribution;

/**
 * @package Samsara\Fermat\Stats
 */
class Exponential extends ContinuousDistribution
{

    private ImmutableDecimal $lambda;

    /**
     * Exponential constructor.
     *
     * @param int|float|string|Decimal $lambda This is the *rate parameter* not the *scale parameter*
     *
     * @throws IntegrityConstraint
     */
    public function __construct(int|float|string|Decimal $lambda)
    {
        /** @var ImmutableDecimal $lambda */
        $lambda = Numbers::makeOrDont(Numbers::IMMUTABLE, $lambda);

        if (!$lambda->isPositive()) {
            throw new IntegrityConstraint(
                'Lambda must be positive',
                'Provide a positive lambda',
                'Exponential distributions work on time to occurrence; the mean time to occurrence (lambda) must be positive'
            );
        }

        $this->lambda = $lambda;
    }

    /**
     * @return ImmutableDecimal
     */
    public function getMean(): ImmutableDecimal
    {
        return Numbers::makeOne($this->lambda->getScale())->divide($this->lambda);
    }

    /**
     * @return ImmutableDecimal
     */
    public function getMedian(): ImmutableDecimal
    {
        return Numbers::makeNaturalLog2($this->lambda->getScale())->divide($this->lambda);
    }

    /**
     * @return ImmutableDecimal
     */
    public function getMode(): ImmutableDecimal
    {
        return Numbers::makeZero($this->lambda->getScale());
    }

    /**
     * @return ImmutableDecimal
     */
    public function getVariance(): ImmutableDecimal
    {
        return Numbers::makeOne($this->lambda->getScale())->divide($this->lambda->pow(2));
    }

    /**
     * @param int|float|string|Decimal $x
     * @param int|null                 $scale
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function cdf(int|float|string|Decimal $x, ?int $scale = null): ImmutableDecimal
    {

        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);
        $one = Numbers::makeOne();

        if (!$x->isPositive()) {
            throw new IntegrityConstraint(
                'X must be positive',
                'Provide a positive x',
                'Exponential distributions work on time to occurrence; the time to occurrence (x) must be positive'
            );
        }

        $scale = $scale ?? $x->getScale();
        $internalScale = $scale + 2;

        $e = Numbers::makeE($internalScale);

        /** @var ImmutableDecimal $cdf */
        $cdf =
            $one->subtract(
                $e->pow(
                    $x->multiply($this->lambda)
                        ->multiply(-1)
                )
            )->truncateToScale($scale);

        return $cdf;

    }

    /**
     * @param int|float|string|Decimal $x
     * @param int|null                 $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function pdf(int|float|string|Decimal $x, ?int $scale = null): ImmutableDecimal
    {

        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        if (!$x->isPositive()) {
            throw new IntegrityConstraint(
                'X must be positive',
                'Provide a positive x',
                'Exponential distributions work on time to occurrence; the time to occurrence (x) must be positive'
            );
        }

        $scale = $scale ?? $x->getScale();
        $internalScale = $scale + 2;

        $e = Numbers::makeE($internalScale);

        /** @var ImmutableDecimal $pdf */
        $pdf =
            $this->lambda
                ->multiply(
                    $e->pow(
                        $this->lambda
                            ->multiply(-1)
                            ->multiply($x)
                    )
                )->truncateToScale($scale);

        return $pdf;

    }

    public function percentBetween(int|float|string|Decimal $x1, int|float|string|Decimal $x2, ?int $scale = null): ImmutableDecimal
    {
        $x1 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x1);
        $x2 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x2);

        if (!$x1->isPositive() || !$x2->isPositive()) {
            throw new IntegrityConstraint(
                'X must be positive',
                'Provide a positive x',
                'Exponential distributions work on time to occurrence; the time to occurrence (x) must be positive'
            );
        }

        return parent::percentBetween($x1, $x2, $scale);
    }

    /**
     * @return ImmutableDecimal
     *
     * @codeCoverageIgnore
     */
    public function random(): ImmutableDecimal
    {

        $u = RandomProvider::randomDecimal(10);
        $one = Numbers::makeOne();

        /** @var ImmutableDecimal $random */
        $random =
            $one->subtract($u)
                ->ln()
                ->divide(
                    $this->lambda
                        ->multiply(-1)
                );

        return $random;

    }

    /**
     * @param int|float|Decimal $min
     * @param int|float|Decimal $max
     * @param int               $maxIterations
     *
     * @return ImmutableDecimal
     * @throws OptionalExit
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
                'A suitable random number, restricted by the $max (' . $max . ') and $min (' . $min . '), could not be found within ' . $maxIterations . ' iterations'
            );
        }

        return $randomNumber;
    }
}