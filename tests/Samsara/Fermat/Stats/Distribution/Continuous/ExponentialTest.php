<?php

namespace Samsara\Fermat\Stats\Distribution\Continuous;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;

class ExponentialTest extends TestCase
{

    public function testConstructException()
    {
        $this->expectException(IntegrityConstraint::class);
        new Exponential(-2);
    }

    public function percentBetweenProvider(): array
    {
        $a = new Exponential(1);
        $b = new Exponential(5);
        $c = new Exponential(20);
        $d = new Exponential(125);


        return [
            'Percent Between: lambda 1' => [$a, '1', '2', '0.232544158'],
            'Percent Between: lambda 5' => [$b, '1', '2', '0.0066925471'],
            'Percent Between: lambda 20' => [$c, '0.05', '0.1', '0.232544158'],
            'Percent Between: lambda 125' => [$d, '0.002', '0.004', '0.1722701234']
        ];
    }

    /**
     * @dataProvider percentBetweenProvider
     */
    public function testPercentBetween(Exponential $a, string $x1, string $x2, string $expected)
    {

        $this->assertEquals($expected, $a->percentBetween($x1, $x2)->getValue());
        
    }

    public function testPercentBetweenException()
    {
        $a = new Exponential(1);

        $this->expectException(IntegrityConstraint::class);
        $a->percentBetween(-4, -2);
    }

    public function cdfProvider(): array
    {
        $a = new Exponential(1);
        $b = new Exponential(5);
        $c = new Exponential('20');
        $d = new Exponential('125');

        return [
            'cdf: lambda 1' => [$a, '2', '0.8646647168'],
            'cdf: lambda 5' => [$b, '2', '0.9999546001'],
            'cdf: lambda 20' => [$c, '0.1', '0.8646647168'],
            'cdf: lambda 125' => [$d, '0.02', '0.9179150014']
        ];
    }

    /**
     * @dataProvider cdfProvider
     */
    public function testCdf(Exponential $a, string $x, string $expected)
    {

        $this->assertEquals($expected, $a->cdf($x)->getValue());

    }

    public function testCdfException()
    {
        $a = new Exponential(1);

        $this->expectException(IntegrityConstraint::class);
        $a->cdf(-4);
    }

    public function pdfProvider(): array
    {
        $a = new Exponential(1);
        $b = new Exponential(5);
        $c = new Exponential('20');
        $d = new Exponential('125');

        return [
            'pdf: lambda 1' => [$a, '2', '0.1353352832'],
            'pdf: lambda 5' => [$b, '2', '0.0002269996'],
            'pdf: lambda 20' => [$c, '0.1', '2.7067056647'],
            'pdf: lambda 125' => [$d, '0.02', '10.260624828']
        ];
    }

    /**
     * @dataProvider pdfProvider
     */
    public function testPdf(Exponential $a, string $x, string $expected)
    {

        $this->assertEquals($expected, $a->pdf($x)->getValue());

    }

    public function testPdfException()
    {
        $a = new Exponential(1);

        $this->expectException(IntegrityConstraint::class);
        $a->pdf(-4);
    }
}
