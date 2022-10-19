<?php

namespace Samsara\Fermat\Core\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Enums\CalcMode;

class HyperbolicPrecisionTest extends TestCase
{

    /*
     * sinh()
     */

    public function sinhImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'IDecimal sinh(1)' => [$a, '1.1752011936', NumberBase::Ten, 10],
            'IDecimal sinh(2)' => [$b, '3.6268604078', NumberBase::Ten, 10],
            'IDecimal sinh(3)' => [$c, '10.0178749274', NumberBase::Ten, 10],
            'IDecimal sinh(10)' => [$d, '11013.2328747034', NumberBase::Ten, 10],
            'IDecimal sinh(0.0000000001)' => [$g, '0.0000000001', NumberBase::Ten, 10],
        ];
    }

    public function sinhMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'MDecimal sinh(1)' => [$a, '1.1752011936', NumberBase::Ten, 10],
            'MDecimal sinh(2)' => [$b, '3.6268604078', NumberBase::Ten, 10],
            'MDecimal sinh(3)' => [$c, '10.0178749274', NumberBase::Ten, 10],
            'MDecimal sinh(10)' => [$d, '11013.2328747034', NumberBase::Ten, 10],
            'MDecimal sinh(0.0000000001)' => [$g, '0.0000000001', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider sinhImmutableProvider
     * @dataProvider sinhMutableProvider
     */
    public function testSinh(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->sinh();
        } else {
            $answer = $num->sinh();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * cosh()
     */

    public function coshImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'IDecimal cosh(1)' => [$a, '1.5430806348', NumberBase::Ten, 10],
            'IDecimal cosh(2)' => [$b, '3.7621956911', NumberBase::Ten, 10],
            'IDecimal cosh(3)' => [$c, '10.0676619958', NumberBase::Ten, 10],
            'IDecimal cosh(10)' => [$d, '11013.2329201033', NumberBase::Ten, 10],
            'IDecimal cosh(0.0000000001)' => [$g, '1', NumberBase::Ten, 10],
        ];
    }

    public function coshMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'MDecimal cosh(1)' => [$a, '1.5430806348', NumberBase::Ten, 10],
            'MDecimal cosh(2)' => [$b, '3.7621956911', NumberBase::Ten, 10],
            'MDecimal cosh(3)' => [$c, '10.0676619958', NumberBase::Ten, 10],
            'MDecimal cosh(10)' => [$d, '11013.2329201033', NumberBase::Ten, 10],
            'MDecimal cosh(0.0000000001)' => [$g, '1', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider coshImmutableProvider
     * @dataProvider coshMutableProvider
     */
    public function testCosh(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->cosh();
        } else {
            $answer = $num->cosh();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * tanh()
     */

    public function tanhImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'IDecimal tanh(1)' => [$a, '0.761594156', NumberBase::Ten, 10],
            'IDecimal tanh(2)' => [$b, '0.9640275801', NumberBase::Ten, 10],
            'IDecimal tanh(3)' => [$c, '0.9950547537', NumberBase::Ten, 10],
            'IDecimal tanh(10)' => [$d, '0.9999999959', NumberBase::Ten, 10],
            'IDecimal tanh(0.0000000001)' => [$g, '0.0000000001', NumberBase::Ten, 10],
        ];
    }

    public function tanhMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'MDecimal tanh(1)' => [$a, '0.761594156', NumberBase::Ten, 10],
            'MDecimal tanh(2)' => [$b, '0.9640275801', NumberBase::Ten, 10],
            'MDecimal tanh(3)' => [$c, '0.9950547537', NumberBase::Ten, 10],
            'MDecimal tanh(10)' => [$d, '0.9999999959', NumberBase::Ten, 10],
            'MDecimal tanh(0.0000000001)' => [$g, '0.0000000001', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider tanhImmutableProvider
     * @dataProvider tanhMutableProvider
     */
    public function testTanh(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->tanh();
        } else {
            $answer = $num->tanh();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * sech()
     */

    public function sechImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'IDecimal sech(1)' => [$a, '0.6480542737', NumberBase::Ten, 10],
            'IDecimal sech(2)' => [$b, '0.2658022288', NumberBase::Ten, 10],
            'IDecimal sech(3)' => [$c, '0.0993279274', NumberBase::Ten, 10],
            'IDecimal sech(10)' => [$d, '0.0000907999', NumberBase::Ten, 10],
            'IDecimal sech(0.0000000001)' => [$g, '1', NumberBase::Ten, 10],
        ];
    }

    public function sechMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'MDecimal sech(1)' => [$a, '0.6480542737', NumberBase::Ten, 10],
            'MDecimal sech(2)' => [$b, '0.2658022288', NumberBase::Ten, 10],
            'MDecimal sech(3)' => [$c, '0.0993279274', NumberBase::Ten, 10],
            'MDecimal sech(10)' => [$d, '0.0000907999', NumberBase::Ten, 10],
            'MDecimal sech(0.0000000001)' => [$g, '1', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider sechImmutableProvider
     * @dataProvider sechMutableProvider
     */
    public function testSech(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->sech();
        } else {
            $answer = $num->sech();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * csch()
     */

    public function cschImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'IDecimal csch(1)' => [$a, '0.8509181282', NumberBase::Ten, 10],
            'IDecimal csch(2)' => [$b, '0.2757205648', NumberBase::Ten, 10],
            'IDecimal csch(3)' => [$c, '0.0998215697', NumberBase::Ten, 10],
            'IDecimal csch(10)' => [$d, '0.0000907999', NumberBase::Ten, 10],
            'IDecimal csch(0.0000000001)' => [$g, '10000000000', NumberBase::Ten, 10],
        ];
    }

    public function cschMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'MDecimal csch(1)' => [$a, '0.8509181282', NumberBase::Ten, 10],
            'MDecimal csch(2)' => [$b, '0.2757205648', NumberBase::Ten, 10],
            'MDecimal csch(3)' => [$c, '0.0998215697', NumberBase::Ten, 10],
            'MDecimal csch(10)' => [$d, '0.0000907999', NumberBase::Ten, 10],
            'MDecimal csch(0.0000000001)' => [$g, '10000000000', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider cschImmutableProvider
     * @dataProvider cschMutableProvider
     */
    public function testCsch(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->csch();
        } else {
            $answer = $num->csch();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * coth()
     */

    public function cothImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'IDecimal coth(1)' => [$a, '1.3130352855', NumberBase::Ten, 10],
            'IDecimal coth(2)' => [$b, '1.0373147207', NumberBase::Ten, 10],
            'IDecimal coth(3)' => [$c, '1.0049698233', NumberBase::Ten, 10],
            'IDecimal coth(10)' => [$d, '1.0000000041', NumberBase::Ten, 10],
            'IDecimal coth(0.0000000001)' => [$g, '10000000000', NumberBase::Ten, 10],
        ];
    }

    public function cothMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Precision);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Precision);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Precision);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Precision);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Precision);

        return [
            'MDecimal coth(1)' => [$a, '1.3130352855', NumberBase::Ten, 10],
            'MDecimal coth(2)' => [$b, '1.0373147207', NumberBase::Ten, 10],
            'MDecimal coth(3)' => [$c, '1.0049698233', NumberBase::Ten, 10],
            'MDecimal coth(10)' => [$d, '1.0000000041', NumberBase::Ten, 10],
            'MDecimal coth(0.0000000001)' => [$g, '10000000000', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider cothImmutableProvider
     * @dataProvider cothMutableProvider
     */
    public function testCoth(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->coth();
        } else {
            $answer = $num->coth();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

}