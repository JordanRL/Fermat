<?php

namespace Samsara\Fermat\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;

class StatsProviderTest extends TestCase
{

    /**
     * @medium
     */
    public function testGaussErrorFunction()
    {

        $answer = StatsProvider::gaussErrorFunction(1);

        $this->assertEquals('0.8427007929', $answer->getValue());

    }

    /**
     * @medium
     */
    public function testBinomialCoefficient()
    {
        $answer1 = StatsProvider::binomialCoefficient(5, 2);

        $this->assertEquals('10', $answer1->getValue());
    }

    /**
     * @small
     */
    public function testBinomialCoefficientException1()
    {
        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('For $n choose $k, the values of $n and $k must satisfy the inequality 0 <= $k <= $n');

        StatsProvider::binomialCoefficient(5, -1);
    }

    /**
     * @small
     */
    public function testBinomialCoefficientException2()
    {
        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('For $n choose $k, the values of $n and $k must satisfy the inequality 0 <= $k <= $n');

        StatsProvider::binomialCoefficient(5, 6);
    }

    /**
     * @small
     */
    public function testBinomialCoefficientException3()
    {
        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('For $n choose $k, the values $n and $k must be whole numbers');

        StatsProvider::binomialCoefficient('3.5', '2.5');
    }

    /**
     * @medium
     */
    public function testComplementNormalCDF()
    {
        $answer = StatsProvider::complementNormalCDF(-1);

        $this->assertEquals('0.8413447461', $answer->getValue());
    }

    /**
     * @large
     */
    public function testInverseNormalCDF()
    {

        $answer = StatsProvider::inverseNormalCDF('0.1586552539', 9);

        $this->assertEquals('-1', $answer->getValue());

    }

    /**
     * @medium
     */
    public function testNormalCDF()
    {

        $answer = StatsProvider::normalCDF(-1);

        $this->assertEquals('0.1586552539', $answer->getValue());

    }

    /**
     * @medium
     */
    public function testInverseErrorCoefficients()
    {

        $answer = StatsProvider::inverseErrorCoefficients(5);

        $this->assertEquals('34807/16200', $answer->getValue());

    }

    /**
     * @large
     */
    public function testInverseErrorFunction()
    {

        $answer = StatsProvider::inverseGaussErrorFunction('0.842700792949714');

        $this->assertEquals('1', $answer->getValue());

    }
}
