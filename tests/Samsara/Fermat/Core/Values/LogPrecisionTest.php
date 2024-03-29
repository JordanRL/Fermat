<?php

namespace Samsara\Fermat\Core\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Enums\CalcMode;

/**
 * @group Logs
 */
class LogPrecisionTest extends TestCase
{

    /*
     * LN()
     */

    public function logImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $e = (new ImmutableDecimal(100000000000))->setMode(CalcMode::Precision);
        $f = (new ImmutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Precision);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'IDecimal ln(1)' => [$a, '0', 10],
            'IDecimal ln(2)' => [$b, '0.6931471806', 10],
            'IDecimal ln(3)' => [$c, '1.0986122887', 10],
            'IDecimal ln(10)' => [$d, '2.302585093', 10],
            'IDecimal ln(100000000000)' => [$e, '25.3284360229', 10],
            'IDecimal ln(1000000000000000000000000000000)' => [$f, '69.0775527898', 10],
            'IDecimal ln(0.0000000001)' => [$g, '-23.0258509299', 10],
        ];
    }

    public function logMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Precision);
        $e = (new MutableDecimal(100000000000))->setMode(CalcMode::Precision);
        $f = (new MutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Precision);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'MDecimal ln(1)' => [$a, '0', 10],
            'MDecimal ln(2)' => [$b, '0.6931471806', 10],
            'MDecimal ln(3)' => [$c, '1.0986122887', 10],
            'MDecimal ln(10)' => [$d, '2.302585093', 10],
            'MDecimal ln(100000000000)' => [$e, '25.3284360229', 10],
            'MDecimal ln(1000000000000000000000000000000)' => [$f, '69.0775527898', 10],
            'MDecimal ln(0.0000000001)' => [$g, '-23.0258509299', 10],
        ];
    }

    /**
     * @dataProvider logImmutableProvider
     * @dataProvider logMutableProvider
     */
    public function testLn(Decimal $a, string $expected, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->ln();
        } else {
            $answer = $a->ln();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * LOG10()
     */

    public function log10ImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $e = (new ImmutableDecimal(100000000000))->setMode(CalcMode::Precision);
        $f = (new ImmutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Precision);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'IDecimal log10(1)' => [$a, '0', 10],
            'IDecimal log10(2)' => [$b, '0.3010299957', 10],
            'IDecimal log10(3)' => [$c, '0.4771212547', 10],
            'IDecimal log10(10)' => [$d, '1', 10],
            'IDecimal log10(100000000000)' => [$e, '11', 10],
            'IDecimal log10(1000000000000000000000000000000)' => [$f, '30', 10],
            'IDecimal log10(0.0000000001)' => [$g, '-10', 10],
        ];
    }

    public function log10MutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Precision);
        $e = (new MutableDecimal(100000000000))->setMode(CalcMode::Precision);
        $f = (new MutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Precision);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'MDecimal log10(1)' => [$a, '0', 10],
            'MDecimal log10(2)' => [$b, '0.3010299957', 10],
            'MDecimal log10(3)' => [$c, '0.4771212547', 10],
            'MDecimal log10(10)' => [$d, '1', 10],
            'MDecimal log10(100000000000)' => [$e, '11', 10],
            'MDecimal log10(1000000000000000000000000000000)' => [$f, '30', 10],
            'MDecimal log10(0.0000000001)' => [$g, '-10', 10],
        ];
    }

    /**
     * @dataProvider log10ImmutableProvider
     * @dataProvider log10MutableProvider
     */
    public function testLog10(Decimal $a, string $expected, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->log10();
        } else {
            $answer = $a->log10();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * EXP()
     */

    public function expImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $e = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'IDecimal exp(1)' => [$a, '2.7182818285', 10],
            'IDecimal exp(2)' => [$b, '7.3890560989', 10],
            'IDecimal exp(3)' => [$c, '20.0855369232', 10],
            'IDecimal exp(10)' => [$d, '22026.4657948067', 10],
            'IDecimal exp(0.0000000001)' => [$e, '1.0000000001', 10],
        ];
    }

    public function expMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Precision);
        $e = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'MDecimal exp(1)' => [$a, '2.7182818285', 10],
            'MDecimal exp(2)' => [$b, '7.3890560989', 10],
            'MDecimal exp(3)' => [$c, '20.0855369232', 10],
            'MDecimal exp(10)' => [$d, '22026.4657948067', 10],
            'MDecimal exp(0.0000000001)' => [$e, '1.0000000001', 10],
        ];
    }

    /**
     * @dataProvider expImmutableProvider
     * @dataProvider expMutableProvider
     */
    public function testExp(Decimal $a, string $expected, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->exp();
        } else {
            $answer = $a->exp();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

}