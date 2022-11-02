<?php

namespace Samsara\Fermat\Stats\Distribution\Continuous;

use PHPUnit\Framework\TestCase;

class NormalTest extends TestCase
{

    public function propertiesProvider(): array
    {
        $a = new Normal(0, 1);
        $b = new Normal(5, 2);
        $c = new Normal(20, 6);
        $d = new Normal('12000', '125');


        return [
            'Properties: mean 0 | sd 1' => [$a, '0', '1',],
            'Properties: mean 5 | sd 2' => [$b, '5', '2',],
            'Properties: mean 20 | sd 6' => [$c, '20', '6',],
            'Properties: mean 12000 | sd 125' => [$d, '12000', '125',]
        ];
    }

    /**
     * @dataProvider propertiesProvider
     */
    public function testGetters(Normal $a, string $expectedMean, string $expectedSD)
    {
        $this->assertEquals($expectedMean, $a->getMean()->getValue());
        $this->assertEquals($expectedSD, $a->getSD()->getValue());
    }

    public function evaluateAtProvider(): array
    {
        $a = new Normal(0, 1);
        $b = new Normal(5, 2);
        $c = new Normal(20, 6);
        $d = new Normal('12000', '125');


        return [
            'PDF: mean 0 | sd 1' => [$a, '1', '0.2419707245'],
            'PDF: mean 5 | sd 2' => [$b, '6', '0.1760326633'],
            'PDF: mean 20 | sd 6' => [$c, '21', '0.065573286'],
            'PDF: mean 12000 | sd 125' => [$d, '12001', '0.0031914361']
        ];
    }

    /**
     * @medium
     * @dataProvider evaluateAtProvider
     */
    public function testEvaluateAt(Normal $a, string $z, string $expected)
    {
        $this->assertEquals($expected, $a->pdf($z)->getValue());
    }

    public function CDFProvider(): array
    {
        $a = new Normal(0, 1);
        $b = new Normal(5, 2);
        $c = new Normal(20, 6);
        $d = new Normal('12000', '125');


        return [
            'CDF: mean 0 | sd 1' => [$a, '1', '0.841344746'],
            'CDF: mean 5 | sd 2' => [$b, '6', '0.6914624612'],
            'CDF: mean 20 | sd 6' => [$c, '21', '0.5661838326'],
            'CDF: mean 12000 | sd 125' => [$d, '12001', '0.5031915042']
        ];
    }

    /**
     * @medium
     * @dataProvider CDFProvider
     */
    public function testCDF(Normal $a, string $z, string $expected)
    {
        $this->assertEquals($expected, $a->cdf($z)->getValue());
    }

    public function percentBetweenProvider(): array
    {
        $a = new Normal(0, 1);
        $b = new Normal(5, 2);
        $c = new Normal(20, 6);
        $d = new Normal('12000', '125');


        return [
            'P(x1<=X<=2): mean 0 | sd 1' => [$a, '1', '2', '0.135905122'],
            'P(x1<=X<=2): mean 5 | sd 2' => [$b, '6', '7', '0.1498822848'],
            'P(x1<=X<=2): mean 20 | sd 6' => [$c, '21', '22', '0.0643748272'],
            'P(x1<=X<=2): mean 12000 | sd 125' => [$d, '12001', '12002', '0.0031912999']
        ];
    }

    /**
     * @medium
     * @dataProvider percentBetweenProvider
     */
    public function testPercentBetween(Normal $a, string $x1, string $x2, string $expected)
    {
        $this->assertEquals($expected, $a->percentBetween($x1, $x2)->getValue());
    }

    public function percentBelowXProvider(): array
    {
        $a = new Normal(0, 1);
        $b = new Normal(5, 2);
        $c = new Normal(20, 6);
        $d = new Normal('12000', '125');


        return [
            'P(X>=x): mean 0 | sd 1' => [$a, '1', '0.841344746'],
            'P(X>=x): mean 5 | sd 2' => [$b, '6', '0.6914624612'],
            'P(X>=x): mean 20 | sd 6' => [$c, '21', '0.5661838326'],
            'P(X>=x): mean 12000 | sd 125' => [$d, '12001', '0.5031915042']
        ];
    }

    /**
     * @medium
     * @dataProvider percentBelowXProvider
     */
    public function testPercentBelowX(Normal $a, string $x, string $expected)
    {
        $this->assertEquals($expected, $a->percentBelowX($x)->getValue());
    }

    public function percentAboveXProvider(): array
    {
        $a = new Normal(0, 1);
        $b = new Normal(5, 2);
        $c = new Normal(20, 6);
        $d = new Normal('12000', '125');


        return [
            'P(x<X): mean 0 | sd 1' => [$a, '1', '0.158655254'],
            'P(x<X): mean 5 | sd 2' => [$b, '6', '0.3085375388'],
            'P(x<X): mean 20 | sd 6' => [$c, '21', '0.4338161674'],
            'P(x<X): mean 12000 | sd 125' => [$d, '12001', '0.4968084958']
        ];
    }

    /**
     * @medium
     * @dataProvider percentAboveXProvider
     */
    public function testPercentAboveX(Normal $a, string $x, string $expected)
    {
        $this->assertEquals($expected, $a->percentAboveX($x)->getValue());
    }

    public function zScoreOfXProvider(): array
    {
        $a = new Normal(0, 1);
        $b = new Normal(5, 2);
        $c = new Normal(20, 6);
        $d = new Normal('12000', '125');


        return [
            'Z-Score: mean 0 | sd 1' => [$a, '1', '1'],
            'Z-Score: mean 5 | sd 2' => [$b, '6', '0.5'],
            'Z-Score: mean 20 | sd 6' => [$c, '21', '0.1666666667'],
            'Z-Score: mean 12000 | sd 125' => [$d, '12001', '0.008']
        ];
    }

    /**
     * @medium
     * @dataProvider zScoreOfXProvider
     */
    public function testZScoreOfX(Normal $a, string $x, string $expected)
    {
        $this->assertEquals($expected, $a->zScoreOfX($x)->getValue());
    }

    public function xFromZScoreProvider(): array
    {
        $a = new Normal(0, 1);
        $b = new Normal(5, 2);
        $c = new Normal(20, 6);
        $d = new Normal('12000', '125');


        return [
            'Z-Score: mean 0 | sd 1' => [$a, '1', '1'],
            'Z-Score: mean 5 | sd 2' => [$b, '0.5', '6'],
            'Z-Score: mean 20 | sd 6' => [$c, '0.16666666667', '21'],
            'Z-Score: mean 12000 | sd 125' => [$d, '0.008', '12001']
        ];
    }

    /**
     * @medium
     * @dataProvider xFromZScoreProvider
     */
    public function testXFromZScore(Normal $a, string $z, string $expected)
    {
        $this->assertEquals($expected, $a->xFromZScore($z)->getValue());
    }

    public function makeFromSDProvider(): array
    {
        return [
            'From SD: mean 0 | sd 1' => ['1', '0.841344746', '1', '0'],
            'From SD: mean 5 | sd 2' => ['2', '0.6914624612', '6', '5'],
            'From SD: mean 20 | sd 6' => ['6', '0.5661838326', '21', '20'],
            'From SD: mean 12000 | sd 125' => ['125', '0.5031915042', '12001', '12000']
        ];
    }

    /**
     * @dataProvider makeFromSDProvider
     */
    public function testMakeFromSD(string $sd, string $p, string $x, string $expected)
    {
        $normal = Normal::makeFromSd($p, $x, $sd);

        $this->assertEquals($expected, $normal->getMean()->getValue());
    }

    public function makeFromMeanProvider(): array
    {
        return [
            'From Mean: mean 0 | sd 1' => ['0', '0.841344746', '1', '1'],
            'From Mean: mean 5 | sd 2' => ['5', '0.6914624612', '6', '2'],
            'From Mean: mean 20 | sd 6' => ['20', '0.5661838326', '21', '6'],
            'From Mean: mean 12000 | sd 125' => ['12000', '0.5031915042', '12001', '125']
        ];
    }

    /**
     * @dataProvider makeFromMeanProvider
     */
    public function testMakeFromMean(string $mean, string $p, string $x, string $expected)
    {
        $normal = Normal::makeFromMean($p, $x, $mean);

        $this->assertEquals($expected, $normal->getSD()->getValue());
    }

}