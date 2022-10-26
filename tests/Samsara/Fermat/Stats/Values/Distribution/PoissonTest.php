<?php

namespace Samsara\Fermat\Stats\Values\Distribution;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;

class PoissonTest extends TestCase
{

    public function pmfProvider(): array
    {
        $a = new Poisson(3);
        $b = new Poisson(12);
        $c = new Poisson(1);
        $d = new Poisson(110);

        return [
            'pmf: lambda 3' => [$a, '6', '0.0504094067'],
            'pmf: lambda 12' => [$b, '10', '0.1048372559'],
            'pmf: lambda 1' => [$c, '0', '0.3678794412'],
            'pmf: lambda 110' => [$d, '96', '0.0160300219'],
        ];
    }

    /**
     * @large
     * @dataProvider pmfProvider
     */
    public function testPmf(Poisson $a, string $k, string $expected)
    {

        $this->assertEquals($expected, $a->pmf($k)->getValue());

    }

    /**
     * @large
     * @dataProvider pmfProvider
     */
    public function testProbabilityOfKEvents(Poisson $a, string $k, string $expected)
    {

        $this->assertEquals($expected, $a->probabilityOfKEvents($k)->getValue());

    }

    public function cdfProvider(): array
    {
        $a = new Poisson(3);
        $b = new Poisson(12);
        $c = new Poisson(1);
        $d = new Poisson(110);

        return [
            'cdf: lambda 3' => [$a, '6', '0.9664914647'],
            'cdf: lambda 12' => [$b, '10', '0.3472294176'],
            'cdf: lambda 1' => [$c, '0', '0.3678794412'],
            'cdf: lambda 110' => [$d, '96', '0.0970245527'],
        ];
    }
    /**
     * @large
     * @dataProvider cdfProvider
     */
    public function testCdf(Poisson $a, string $k, string $expected)
    {

        $this->assertEquals($expected, $a->cdf($k)->getValue());

    }

    public function rangePmfProvider(): array
    {
        $a = new Poisson(3);
        $b = new Poisson(12);
        $c = new Poisson(1);
        $d = new Poisson(110);

        return [
            'range pmf: lambda 3' => [$a, '6', '12', '0.083901793'],
            'range pmf: lambda 12' => [$b, '10', '16', '0.6563168308'],
            'range pmf: lambda 1' => [$c, '0', '3', '0.9810118432'],
            'range pmf: lambda 110' => [$d, '96', '105', '0.2577049151'],
            'range pmf: lambda 3 (same)' => [$a, '6', '6', '0.0504094067'],
        ];
    }

    /**
     * @dataProvider rangePmfProvider
     */
    public function testRangePmf(Poisson $a, string $k1, string $k2, string $expected)
    {

        $this->assertEquals($expected, $a->rangePmf($k1, $k2)->getValue());
        $this->assertEquals($expected, $a->rangePmf($k2, $k1)->getValue());

    }

    public function testExceptions1()
    {
        $this->expectException(IntegrityConstraint::class);
        new Poisson(-1);
    }

    public function testExceptions2()
    {
        $a = new Poisson(1);
        $this->expectException(IntegrityConstraint::class);
        $a->pmf(-1);
    }

    public function testExceptions3()
    {
        $a = new Poisson(1);
        $this->expectException(IntegrityConstraint::class);
        $a->pmf('1.1');
    }

    public function testExceptions4()
    {
        $a = new Poisson(1);
        $this->expectException(IntegrityConstraint::class);
        $a->cdf(-1);
    }

    public function testExceptions5()
    {
        $a = new Poisson(1);
        $this->expectException(IntegrityConstraint::class);
        $a->cdf('1.1');
    }

    public function testExceptions6()
    {
        $a = new Poisson(1);
        $this->expectException(IntegrityConstraint::class);
        $a->rangePmf(-1, 5);
    }

    public function testExceptions7()
    {
        $a = new Poisson(1);
        $this->expectException(IntegrityConstraint::class);
        $a->rangePmf('1.1', 5);
    }
}
