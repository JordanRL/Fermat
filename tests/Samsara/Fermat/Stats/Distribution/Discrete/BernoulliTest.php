<?php

namespace Samsara\Fermat\Stats\Distribution\Discrete;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;

class BernoulliTest extends TestCase
{

    public function testPmf()
    {
        $bernoulli = new Bernoulli('0.6');

        $this->assertEquals('0.6', $bernoulli->pmf(1)->getValue());
        $this->assertEquals('0.4', $bernoulli->pmf(0)->getValue());
    }

    public function testPmfException()
    {
        $bernoulli = new Bernoulli('0.6');

        $this->expectException(IntegrityConstraint::class);
        $bernoulli->pmf('0.3');
    }

    public function testCdf()
    {
        $bernoulli = new Bernoulli('0.6');

        $this->assertEquals('1', $bernoulli->cdf(1)->getValue());
        $this->assertEquals('0', $bernoulli->cdf(-1)->getValue());
        $this->assertEquals('0.4', $bernoulli->cdf('0.3')->getValue());
    }

    public function testGetMean()
    {
        $bernoulli = new Bernoulli('0.6');

        $this->assertEquals('0.6', $bernoulli->getMean()->getValue());
    }

    public function testGetMedian()
    {
        $bernoulli = new Bernoulli('0.6');

        $this->assertEquals('1', $bernoulli->getMedian()->getValue());
        $bernoulli = new Bernoulli('0.4');

        $this->assertEquals('0', $bernoulli->getMedian()->getValue());
    }

    public function testGetMode()
    {
        $bernoulli = new Bernoulli('0.6');

        $this->assertEquals('1', $bernoulli->getMode()->getValue());
        $bernoulli = new Bernoulli('0.4');

        $this->assertEquals('0', $bernoulli->getMode()->getValue());
    }

    public function testGetVariance()
    {
        $bernoulli = new Bernoulli('0.6');

        $this->assertEquals('0.24', $bernoulli->getVariance()->getValue());
    }

}