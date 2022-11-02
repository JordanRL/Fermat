<?php

namespace Samsara\Fermat\Stats\Distribution\Discrete;

use PHPUnit\Framework\TestCase;

class BinomialTest extends TestCase
{

    public function testGetMean()
    {
        $binom = new Binomial('0.6', 4);

        $this->assertEquals('2.4', $binom->getMean()->getValue());
    }

    public function testGetMedian()
    {
        $binom = new Binomial('0.6', 4);

        $this->assertEquals('2', $binom->getMedian()->getValue());
    }

    public function testGetMode()
    {
        $binom = new Binomial('0.6', 4);

        $this->assertEquals('2', $binom->getMode()->getValue());
    }

    public function testGetVariance()
    {
        $binom = new Binomial('0.6', 4);

        $this->assertEquals('0.96', $binom->getVariance()->getValue());
    }

    public function testCdf()
    {
        $binom = new Binomial('0.6', 4);

        $this->assertEquals('0.8704', $binom->cdf(3)->getValue());
    }

    public function testPmf()
    {
        $binom = new Binomial('0.6', 4);

        $this->assertEquals('0.3456', $binom->pmf(3)->getValue());
    }
}
