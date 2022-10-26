<?php

namespace Samsara\Fermat\Stats\Values\Distribution;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Core\Enums\RandomMode;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Stats\Types\Distribution;
use Samsara\Fermat\Core\Provider\RandomProvider;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Stats
 */
class Poisson extends Distribution
{

    private ImmutableDecimal $lambda;

    /**
     * Poisson constructor.
     *
     * @param int|float|string|Decimal $lambda
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
                'Poisson distributions number of expected events; the number of expected events (lambda) must be positive.'
            );
        }

        $this->lambda = $lambda;
    }

    /**
     * @param int|float|string|Decimal $k
     * @param int $scale
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function probabilityOfKEvents(int|float|string|Decimal $k, int $scale = 10): ImmutableDecimal
    {

        return $this->pmf($k, $scale);
        
    }

    /**
     * @param int|float|string|Decimal $x
     * @param int $scale
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function cdf(int|float|string|Decimal $x, int $scale = 10): ImmutableDecimal
    {
        $internalScale = $scale + 2;
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x, $internalScale);

        if (!$x->isNatural() || $x->isNegative()) {
            throw new IntegrityConstraint(
                'Only positive integers are valid x values for Poisson distributions',
                'Provide a positive integer value to calculate the CDF',
                'Poisson distributions describe discrete occurrences; only positive integers are valid x values'
            );
        }

        $cumulative = Numbers::makeZero($internalScale);

        for ($i = 0;$x->isGreaterThanOrEqualTo($i);$i++) {
            $cumulative =
                $cumulative->add(
                    $this->pmf(
                        $i,
                        $internalScale
                    )
                );
        }

        return $cumulative->roundToScale($scale);

    }

    /**
     * @param int|float|string|Decimal $x
     * @param int $scale
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function pmf(int|float|string|Decimal $x, int $scale = 10): ImmutableDecimal
    {

        $internalScale = $scale + 2 + $this->lambda->numberOfIntDigits();
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x, $internalScale);

        if (!$x->isNatural() || $x->isNegative()) {
            throw new IntegrityConstraint(
                'Only positive integers are valid x values for Poisson distributions',
                'Provide a positive integer value to calculate the PMF',
                'Poisson distributions describe discrete occurrences; only positive integers are valid x values'
            );
        }

        $e = Numbers::makeE($internalScale + ceil($this->lambda->asInt() * Numbers::makeNaturalLog2($internalScale)->asFloat() ));

        /** @var ImmutableDecimal $pmf */
        $pmf =
            $this->lambda
            ->pow($x)
            ->multiply(
                $e->pow(
                    $this
                        ->lambda
                        ->multiply(-1)
                )
            )->divide(
                $x->factorial(),
                $internalScale
            );

        return $pmf->roundToScale($scale);
    }

    /**
     * @param int|float|string|Decimal $x1
     * @param int|float|string|Decimal $x2
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public function rangePmf(int|float|string|Decimal $x1, int|float|string|Decimal $x2): ImmutableDecimal
    {
        $x1 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x1);
        $x2 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x2);

        if ($x1->equals($x2)) {
            return $this->pmf($x1);
        } elseif ($x1->isGreaterThan($x2)) {
            $larger = $x1;
            $smaller = $x2;
        } else {
            $larger = $x2;
            $smaller = $x1;
        }

        if (!$larger->isNatural() || !$smaller->isNatural() || $larger->isNegative() || $smaller->isNegative()) {
            throw new IntegrityConstraint(
                'Only positive integers are valid x values for Poisson distributions',
                'Provide positive integer values to calculate the range PMF',
                'Poisson distributions describe discrete occurrences; only positive integers are valid x values'
            );
        }

        $cumulative = Numbers::makeZero();

        for (;$larger->isGreaterThanOrEqualTo($smaller);$smaller = $smaller->add(1)) {
            $cumulative =
                $cumulative->add(
                    $this->pmf($smaller)
                );
        }

        return $cumulative;
    }

    /**
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     *
     * @codeCoverageIgnore
     */
    public function random(): ImmutableDecimal
    {
        if ($this->lambda->isLessThanOrEqualTo(30)) {
            return $this->knuthRandom();
        } else {
            return $this->methodPARandom();
        }
    }

    /**
     * WARNING: This function is of very limited use with Poisson distributions, and may represent a SIGNIFICANT
     * performance hit for certain values of $min, $max, $lambda, and $maxIterations
     *
     * @param int|float|Decimal $min
     * @param int|float|Decimal $max
     * @param int $maxIterations
     *
     * @return ImmutableDecimal
     * @throws OptionalExit
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
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
                'A suitable random number, restricted by the $max ('.$max.') and $min ('.$min.'), could not be found within '.$maxIterations.' iterations'
            );
        } else {
            return $randomNumber;
        }
    }

    /**
     * Method PA from The Computer Generation of Poisson Random Variables by A. C. Atkinson, 1979
     * Journal of the Royal Statistical Society Series C, Vol. 28, No. 1, Pages 29-35
     *
     * As described by John D. Cook: http://www.johndcook.com/blog/2010/06/14/generating-poisson-random-values/
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     *
     * @codeCoverageIgnore
     */
    protected function methodPARandom(): ImmutableDecimal
    {
        /** @var ImmutableDecimal $c */
        $c = $this->lambda->pow(-1)->multiply(3.36)->multiply(-1)->add(0.767);
        /** @var ImmutableDecimal $beta */
        $beta = Numbers::makePi()->divide($this->lambda->multiply(3)->sqrt());
        /** @var ImmutableDecimal $alpha */
        $alpha = $this->lambda->multiply($beta);
        /** @var ImmutableDecimal $k */
        $k = $c->ln(20)->subtract($this->lambda)->subtract($beta->ln(20));
        
        $one = Numbers::makeOne();
        /** @var ImmutableDecimal $oneHalf */
        $oneHalf = Numbers::make(Numbers::IMMUTABLE, '0.5');
        
        $e = Numbers::makeE();

        while (true) {
            $u = RandomProvider::randomDecimal(10, RandomMode::Speed);
            /** @var ImmutableDecimal $x */
            $x = $alpha->subtract($one->subtract($u)->divide($u)->ln(20)->divide($beta));
            /** @var ImmutableDecimal $n */
            $n = $x->add($oneHalf)->floor();

            if ($n->isNegative()) {
                continue;
            }

            $v = RandomProvider::randomDecimal(10, RandomMode::Speed);
            /** @var ImmutableDecimal $y */
            $y = $alpha->subtract($beta->multiply($x));
            /** @var ImmutableDecimal $lhs */
            $lhs = $y->add($v->divide($e->pow($y)->add($one)->pow(2)));
            /** @var ImmutableDecimal $rhs */
            $rhs = $k->add($n->multiply($this->lambda->ln(20)))->subtract($n->factorial()->ln(20));

            if ($lhs->isLessThanOrEqualTo($rhs)) {
                return $n;
            }

            /*
             * At least attempt to free up some memory, since this particular method is extra hard on object instantiation
             */
            unset($u);
            unset($x);
            unset($n);
            unset($v);
            unset($y);
            unset($lhs);
            unset($rhs);
        }
    }

    /**
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     *
     * @codeCoverageIgnore
     */
    protected function knuthRandom(): ImmutableDecimal
    {
        /** @var ImmutableDecimal $L */
        $L = Numbers::makeE()->pow($this->lambda->multiply(-1));
        
        $k = Numbers::makeZero();
        
        $p = Numbers::makeOne();

        do {
            $k = $k->add(1);
            
            $u = RandomProvider::randomDecimal(10);
            $p = $p->multiply($u);
        } while ($p->isGreaterThan($L));

        return $k->subtract(1);
    }

}