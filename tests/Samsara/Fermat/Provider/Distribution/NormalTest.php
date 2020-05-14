<?php

namespace Samsara\Fermat\Provider\Distribution;

use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NormalTest extends TestCase
{
    /**
     * @medium
     */
    public function testPercentAboveX()
    {

        $normal = new Normal(10, 2);

        $this->assertEquals('0.8413447461', $normal->percentAboveX(8)->getValue());

        $this->assertEquals('0.1498822848', $normal->pdf(8, 9)->getValue());

    }
    /**
     * @medium
     */
    public function testPercentBelowX()
    {

        $normal = new Normal(10, 2);

        $this->assertEquals('0.1586552539', $normal->percentBelowX(8)->getValue());

        $this->assertEquals('0.1586552539', $normal->cdf(8)->getValue());

    }
    /**
     * @medium
     */
    public function testZScore()
    {

        $normal = new Normal(10, 2);

        $this->assertEquals('-1', $normal->zScoreOfX(8)->getValue());

        $this->assertEquals('12', $normal->xFromZScore(1)->getValue());

    }

    public function testPdf()
    {

        $normal = new Normal(0, 1);

        $this->assertEquals('0.6826894921', $normal->pdf(-1, 1)->getValue());
        $this->assertEquals('0.6826894921', $normal->pdf(1, -1)->getValue());

        $this->assertEquals('0.6826894921', $normal->pdf(1)->getValue());
        $this->assertEquals('0.6826894921', $normal->pdf(-1)->getValue());

    }

    /*
     * @large
     *
    public function testMakes()
    {

        $normal = Normal::makeFromMean('0.1', 6, 10);

        $this->assertEquals('0.60', $normal->pdf(8)->getValue());

    }
    */

}