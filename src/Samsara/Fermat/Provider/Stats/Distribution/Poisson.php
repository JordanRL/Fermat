<?php

namespace Samsara\Fermat\Provider\Stats\Distribution;

use RandomLib\Factory;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\Stats\Distribution\Base\DistributionInterface;
use Samsara\Fermat\Types\Base\NumberInterface;

class Poisson implements DistributionInterface
{

    /**
     * @var NumberInterface
     */
    private $lambda;

    public function __construct($lambda)
    {
        $this->lambda = Numbers::makeOrDont(Numbers::IMMUTABLE, $lambda);
    }

    public function probabilityOfKEvents($k)
    {

        return $this->pmf($k);
        
    }

    public function cdf($x)
    {

        $cumulative = Numbers::makeZero();
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        for ($i = 0;$x->greaterThanOrEqualTo($i);$i++) {
            $cumulative = $cumulative->add($this->pmf($i));
        }

        return $cumulative;

    }

    public function pdf($x1, $x2)
    {
        $x1 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x1);
        $x2 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x2);

        if ($x1->equals($x2)) {
            return Numbers::makeZero();
        } elseif ($x1->isGreaterThan($x2)) {
            $larger = $x1;
            $smaller = $x2;
        } else {
            $larger = $x2;
            $smaller = $x1;
        }

        if (!$larger->isNatural() || !$smaller->isNatural()) {
            throw new \Exception('Poisson distributions can only have a PDF for integer values');
        }

        $cumulative = Numbers::makeZero();

        for (;$larger->isGreaterThanOrEqualTo($smaller);$smaller->add(1)) {
            $cumulative = $cumulative->add($this->pmf($smaller));
        }

        return $cumulative;
    }

    public function pmf($x)
    {
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);
        $e = Numbers::makeE();

        if (!$x->isNatural()) {
            throw new \Exception('The number of events must be a natural number (integer) for a Poisson distribution');
        }

        return $this->lambda->pow($x)->multiply($e->pow($this->lambda->multiply(-1)))->divide($x->factorial());
    }

    /**
     * WARNING: This function has issues with $lambda values which are very large (in excess of 400-500)
     *
     * @return NumberInterface
     */
    public function random()
    {

        $randFactory = new Factory();
        $L = Numbers::makeE()->pow($this->lambda->multiply(-1));
        $k = Numbers::makeZero();
        $p = Numbers::makeOne();
        
        while ($p->isLessThan($L)) {
            $k = $k->add(1);
            $u = $randFactory->getMediumStrengthGenerator()->generateInt() / PHP_INT_MAX;
            $p = $p->multiply($u);
        }
        
        return $k->subtract(1);
        
    }

    /**
     * WARNING: This function is of very limited use with Poisson distributions, and may recurse almost
     * indefinitely for certain values of $min, $max, and $lambda.
     *
     * @param int $min
     * @param $max
     * @return NumberInterface
     */
    public function rangeRandom($min = 0, $max = PHP_INT_MAX)
    {
        $rand = $this->random();

        if ($rand->isGreaterThanOrEqualTo($min) && $rand->isLessThanOrEqualTo($max)) {
            return $rand;
        } else {
            return $this->rangeRandom($min, $max);
        }
    }

}