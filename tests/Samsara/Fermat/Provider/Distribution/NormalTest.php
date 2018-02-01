<?php

namespace Samsara\Fermat\Provider\Distribution;

use PHPUnit\Framework\TestCase;

class NormalTest extends TestCase
{

    public function testPercentAboveX()
    {

        $normal = new Normal(10, 2);

        $this->assertEquals('0.8413447461', $normal->percentAboveX(8)->getValue());

        $this->assertEquals('0.1498822848', $normal->pdf(8, 9)->getValue());

    }

    public function testCDF()
    {

        $normal = new Normal(10, 2);

        $this->assertEquals('0.1586552539', $normal->cdf(8)->getValue());

    }

    public function testZScore()
    {

        $normal = new Normal(10, 2);

        $this->assertEquals('-1', $normal->zScoreOfX(8)->getValue());

        $this->assertEquals('12', $normal->xFromZScore(1)->getValue());

    }

    /*public function testMakes()
    {

        $normal = Normal::makeFromMean('0.1', 6, 10);

        $this->assertEquals('0.60', $normal->pdf(8)->getValue());

    }*/

}