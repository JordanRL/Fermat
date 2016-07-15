<?php

namespace Samsara\Fermat\Provider\Stats\Distribution;

use RandomLib\Factory;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\Base\NumberInterface;

class Poisson
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
        
        $k = Numbers::makeOrDont(Numbers::IMMUTABLE, $k);
        $e = Numbers::makeE();

        if (!$k->isNatural()) {
            throw new \Exception('The number of events must be a natural number (integer) for a Poisson distribution');
        }

        return $this->lambda->pow($k)->multiply($e->pow($this->lambda->multiply(-1)))->divide($k->factorial());
        
    }

    public function cdf($x)
    {

        $cumulative = Numbers::makeZero();
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        for ($i = 0;$x->greaterThanOrEqualTo($i);$i++) {
            $cumulative = $cumulative->add($this->probabilityOfKEvents($i));
        }

        return $cumulative;

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
        
        while ($p->lessThan($L)) {
            $k = $k->add(1);
            $u = $randFactory->getMediumStrengthGenerator()->generateInt() / PHP_INT_MAX;
            $p = $p->multiply($u);
        }
        
        return $k->subtract(1);
        
    }

}