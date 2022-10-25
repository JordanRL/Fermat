<?php

namespace Samsara\Fermat\Core\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Types\Decimal;

class InverseTrigNativeTest extends TestCase
{

    /*
     * arcsin()
     */

    public function arcsinImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal('0.5'))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal('0.1'))->setMode(CalcMode::Native);
        $d = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $e = (new ImmutableDecimal(0))->setMode(CalcMode::Native);
        $f = (new ImmutableDecimal(-1))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal('-0.5'))->setMode(CalcMode::Native);
        $h = (new ImmutableDecimal('-0.1'))->setMode(CalcMode::Native);
        $i = (new ImmutableDecimal('-0.0000000001'))->setMode(CalcMode::Native);

        return [
            'IDecimal arcsin(1)' => [$a, '1.5707963268', NumberBase::Ten, 10],
            'IDecimal arcsin(0.5)' => [$b, '0.5235987756', NumberBase::Ten, 10],
            'IDecimal arcsin(0.1)' => [$c, '0.1001674212', NumberBase::Ten, 10],
            'IDecimal arcsin(0.0000000001)' => [$d, '0.0000000001', NumberBase::Ten, 10],
            'IDecimal arcsin(0)' => [$e, '0', NumberBase::Ten, 10],
            'IDecimal arcsin(-1)' => [$f, '-1.5707963268', NumberBase::Ten, 10],
            'IDecimal arcsin(-0.5)' => [$g, '-0.5235987756', NumberBase::Ten, 10],
            'IDecimal arcsin(-0.1)' => [$h, '-0.1001674212', NumberBase::Ten, 10],
            'IDecimal arcsin(-0.0000000001)' => [$i, '-0.0000000001', NumberBase::Ten, 10],
        ];
    }

    public function arcsinMutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal('0.5'))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal('0.1'))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $l = (new ImmutableDecimal(0))->setMode(CalcMode::Native);

        return [
            'MDecimal arcsin(1)' => [$a, '1.5707963268', NumberBase::Ten, 10],
            'MDecimal arcsin(0.5)' => [$b, '0.5235987756', NumberBase::Ten, 10],
            'MDecimal arcsin(0.1)' => [$c, '0.1001674212', NumberBase::Ten, 10],
            'MDecimal arcsin(0.0000000001)' => [$g, '0.0000000001', NumberBase::Ten, 10],
            'MDecimal arcsin(0)' => [$l, '0', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider arcsinImmutableProvider
     * @dataProvider arcsinMutableProvider
     */
    public function testArcsin(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->arcsin();
        } else {
            $answer = $num->arcsin();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * arccos()
     */

    public function arccosImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal('0.5'))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal('0.1'))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $l = (new ImmutableDecimal(0))->setMode(CalcMode::Native);

        return [
            'IDecimal arccos(1)' => [$a, '0', NumberBase::Ten, 10],
            'IDecimal arccos(0.5)' => [$b, '1.0471975512', NumberBase::Ten, 10],
            'IDecimal arccos(0.1)' => [$c, '1.4706289056', NumberBase::Ten, 10],
            'IDecimal arccos(0.0000000001)' => [$g, '1.5707963267', NumberBase::Ten, 10],
            'IDecimal arccos(0)' => [$l, '1.5707963268', NumberBase::Ten, 10],
        ];
    }

    public function arccosMutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal('0.5'))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal('0.1'))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $l = (new ImmutableDecimal(0))->setMode(CalcMode::Native);

        return [
            'MDecimal arccos(1)' => [$a, '0', NumberBase::Ten, 10],
            'MDecimal arccos(0.5)' => [$b, '1.0471975512', NumberBase::Ten, 10],
            'MDecimal arccos(0.1)' => [$c, '1.4706289056', NumberBase::Ten, 10],
            'MDecimal arccos(0.0000000001)' => [$g, '1.5707963267', NumberBase::Ten, 10],
            'MDecimal arccos(0)' => [$l, '1.5707963268', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider arccosImmutableProvider
     * @dataProvider arccosMutableProvider
     */
    public function testArccos(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->arccos();
        } else {
            $answer = $num->arccos();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * arctan()
     */

    public function arctanImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Native);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Native);
        $e = (new ImmutableDecimal(100000000000))->setMode(CalcMode::Native);
        $f = (new ImmutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $h = (new ImmutableDecimal(0))->setMode(CalcMode::Native);
        $i = (new ImmutableDecimal(-1))->setMode(CalcMode::Native);
        $j = (new ImmutableDecimal(-2))->setMode(CalcMode::Native);
        $k = (new ImmutableDecimal(-3))->setMode(CalcMode::Native);
        $l = (new ImmutableDecimal(-10))->setMode(CalcMode::Native);
        $m = (new ImmutableDecimal(-100000000000))->setMode(CalcMode::Native);
        $n = (new ImmutableDecimal('-1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $o = (new ImmutableDecimal('-0.0000000001'))->setMode(CalcMode::Native);

        return [
            'IDecimal arctan(1)' => [$a, '0.7853981634', NumberBase::Ten, 10],
            'IDecimal arctan(2)' => [$b, '1.1071487178', NumberBase::Ten, 10],
            'IDecimal arctan(3)' => [$c, '1.2490457724', NumberBase::Ten, 10],
            'IDecimal arctan(10)' => [$d, '1.4711276743', NumberBase::Ten, 10],
            'IDecimal arctan(100000000000)' => [$e, '1.5707963268', NumberBase::Ten, 10],
            'IDecimal arctan(1000000000000000000000000000000)' => [$f, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal arctan(0.0000000001)' => [$g, '0.0000000001', NumberBase::Ten, 10],
            'IDecimal arctan(0)' => [$h, '0', NumberBase::Ten, 10],
            'IDecimal arctan(-1)' => [$i, '-0.7853981634', NumberBase::Ten, 10],
            'IDecimal arctan(-2)' => [$j, '-1.1071487178', NumberBase::Ten, 10],
            'IDecimal arctan(-3)' => [$k, '-1.2490457724', NumberBase::Ten, 10],
            'IDecimal arctan(-10)' => [$l, '-1.4711276743', NumberBase::Ten, 10],
            'IDecimal arctan(-100000000000)' => [$m, '-1.5707963268', NumberBase::Ten, 10],
            'IDecimal arctan(-1000000000000000000000000000000)' => [$n, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal arctan(-0.0000000001)' => [$o, '-0.0000000001', NumberBase::Ten, 10],
        ];
    }

    public function arctanMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Native);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Native);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Native);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Native);
        $e = (new MutableDecimal(100000000000))->setMode(CalcMode::Native);
        $f = (new MutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $h = (new MutableDecimal(0))->setMode(CalcMode::Native);
        $i = (new MutableDecimal(-1))->setMode(CalcMode::Native);
        $j = (new MutableDecimal(-2))->setMode(CalcMode::Native);
        $k = (new MutableDecimal(-3))->setMode(CalcMode::Native);
        $l = (new MutableDecimal(-10))->setMode(CalcMode::Native);
        $m = (new MutableDecimal(-100000000000))->setMode(CalcMode::Native);
        $n = (new MutableDecimal('-1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $o = (new MutableDecimal('-0.0000000001'))->setMode(CalcMode::Native);

        return [
            'MDecimal arctan(1)' => [$a, '0.7853981634', NumberBase::Ten, 10],
            'MDecimal arctan(2)' => [$b, '1.1071487178', NumberBase::Ten, 10],
            'MDecimal arctan(3)' => [$c, '1.2490457724', NumberBase::Ten, 10],
            'MDecimal arctan(10)' => [$d, '1.4711276743', NumberBase::Ten, 10],
            'MDecimal arctan(100000000000)' => [$e, '1.5707963268', NumberBase::Ten, 10],
            'MDecimal arctan(1000000000000000000000000000000)' => [$f, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal arctan(0.0000000001)' => [$g, '0.0000000001', NumberBase::Ten, 10],
            'MDecimal arctan(0)' => [$h, '0', NumberBase::Ten, 10],
            'MDecimal arctan(-1)' => [$i, '-0.7853981634', NumberBase::Ten, 10],
            'MDecimal arctan(-2)' => [$j, '-1.1071487178', NumberBase::Ten, 10],
            'MDecimal arctan(-3)' => [$k, '-1.2490457724', NumberBase::Ten, 10],
            'MDecimal arctan(-10)' => [$l, '-1.4711276743', NumberBase::Ten, 10],
            'MDecimal arctan(-100000000000)' => [$m, '-1.5707963268', NumberBase::Ten, 10],
            'MDecimal arctan(-1000000000000000000000000000000)' => [$n, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal arctan(-0.0000000001)' => [$o, '-0.0000000001', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider arctanImmutableProvider
     * @dataProvider arctanMutableProvider
     * @medium
     */
    public function testArctan(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->arctan();
        } else {
            $answer = $num->arctan();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * arcsec()
     */

    public function arcsecImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Native);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Native);
        $e = (new ImmutableDecimal(100000000000))->setMode(CalcMode::Native);
        $f = (new ImmutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal(-1))->setMode(CalcMode::Native);
        $h = (new ImmutableDecimal(-2))->setMode(CalcMode::Native);
        $i = (new ImmutableDecimal(-3))->setMode(CalcMode::Native);
        $j = (new ImmutableDecimal(-10))->setMode(CalcMode::Native);
        $k = (new ImmutableDecimal(-100000000000))->setMode(CalcMode::Native);
        $l = (new ImmutableDecimal('-1000000000000000000000000000000'))->setMode(CalcMode::Native);

        return [
            'IDecimal arcsec(1)' => [$a, '0', NumberBase::Ten, 10],
            'IDecimal arcsec(2)' => [$b, '1.0471975512', NumberBase::Ten, 10],
            'IDecimal arcsec(3)' => [$c, '1.2309594173', NumberBase::Ten, 10],
            'IDecimal arcsec(10)' => [$d, '1.4706289056', NumberBase::Ten, 10],
            'IDecimal arcsec(100000000000)' => [$e, '1.5707963268', NumberBase::Ten, 10],
            'IDecimal arcsec(1000000000000000000000000000000)' => [$f, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal arcsec(-1)' => [$g, '3.1415926536', NumberBase::Ten, 10],
            'IDecimal arcsec(-2)' => [$h, '2.0943951024', NumberBase::Ten, 10],
            'IDecimal arcsec(-3)' => [$i, '1.9106332362', NumberBase::Ten, 10],
            'IDecimal arcsec(-10)' => [$j, '1.670963748', NumberBase::Ten, 10],
            'IDecimal arcsec(-100000000000)' => [$k, '1.5707963268', NumberBase::Ten, 10],
            'IDecimal arcsec(-1000000000000000000000000000000)' => [$l, IncompatibleObjectState::class, NumberBase::Ten, 10],
        ];
    }

    public function arcsecMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Native);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Native);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Native);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Native);
        $e = (new MutableDecimal(100000000000))->setMode(CalcMode::Native);
        $f = (new MutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $g = (new MutableDecimal(-1))->setMode(CalcMode::Native);
        $h = (new MutableDecimal(-2))->setMode(CalcMode::Native);
        $i = (new MutableDecimal(-3))->setMode(CalcMode::Native);
        $j = (new MutableDecimal(-10))->setMode(CalcMode::Native);
        $k = (new MutableDecimal(-100000000000))->setMode(CalcMode::Native);
        $l = (new MutableDecimal('-1000000000000000000000000000000'))->setMode(CalcMode::Native);

        return [
            'MDecimal arcsec(1)' => [$a, '0', NumberBase::Ten, 10],
            'MDecimal arcsec(2)' => [$b, '1.0471975512', NumberBase::Ten, 10],
            'MDecimal arcsec(3)' => [$c, '1.2309594173', NumberBase::Ten, 10],
            'MDecimal arcsec(10)' => [$d, '1.4706289056', NumberBase::Ten, 10],
            'MDecimal arcsec(100000000000)' => [$e, '1.5707963268', NumberBase::Ten, 10],
            'MDecimal arcsec(1000000000000000000000000000000)' => [$f, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal arcsec(-1)' => [$g, '3.1415926536', NumberBase::Ten, 10],
            'MDecimal arcsec(-2)' => [$h, '2.0943951024', NumberBase::Ten, 10],
            'MDecimal arcsec(-3)' => [$i, '1.9106332362', NumberBase::Ten, 10],
            'MDecimal arcsec(-10)' => [$j, '1.670963748', NumberBase::Ten, 10],
            'MDecimal arcsec(-100000000000)' => [$k, '1.5707963268', NumberBase::Ten, 10],
            'MDecimal arcsec(-1000000000000000000000000000000)' => [$l, IncompatibleObjectState::class, NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider arcsecImmutableProvider
     * @dataProvider arcsecMutableProvider
     */
    public function testArcsec(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->arcsec();
        } else {
            $answer = $num->arcsec();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * arccsc()
     */

    public function arccscImmutableProvider(): array
    {
        $a = (new ImmutableDecimal(1))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal(2))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal(3))->setMode(CalcMode::Native);
        $d = (new ImmutableDecimal(10))->setMode(CalcMode::Native);
        $e = (new ImmutableDecimal(100000000000))->setMode(CalcMode::Native);
        $f = (new ImmutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal(-1))->setMode(CalcMode::Native);
        $h = (new ImmutableDecimal(-2))->setMode(CalcMode::Native);
        $i = (new ImmutableDecimal(-3))->setMode(CalcMode::Native);
        $j = (new ImmutableDecimal(-10))->setMode(CalcMode::Native);
        $k = (new ImmutableDecimal(-100000000000))->setMode(CalcMode::Native);
        $l = (new ImmutableDecimal('-1000000000000000000000000000000'))->setMode(CalcMode::Native);

        return [
            'IDecimal arccsc(1)' => [$a, '1.5707963268', NumberBase::Ten, 10],
            'IDecimal arccsc(2)' => [$b, '0.5235987756', NumberBase::Ten, 10],
            'IDecimal arccsc(3)' => [$c, '0.3398369095', NumberBase::Ten, 10],
            'IDecimal arccsc(10)' => [$d, '0.1001674212', NumberBase::Ten, 10],
            'IDecimal arccsc(100000000000)' => [$e, '0', NumberBase::Ten, 10],
            'IDecimal arccsc(1000000000000000000000000000000)' => [$f, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal arccsc(-1)' => [$g, '-1.5707963268', NumberBase::Ten, 10],
            'IDecimal arccsc(-2)' => [$h, '-0.5235987756', NumberBase::Ten, 10],
            'IDecimal arccsc(-3)' => [$i, '-0.3398369095', NumberBase::Ten, 10],
            'IDecimal arccsc(-10)' => [$j, '-0.1001674212', NumberBase::Ten, 10],
            'IDecimal arccsc(-100000000000)' => [$k, '0', NumberBase::Ten, 10],
            'IDecimal arccsc(-1000000000000000000000000000000)' => [$l, IncompatibleObjectState::class, NumberBase::Ten, 10],
        ];
    }

    public function arccscMutableProvider(): array
    {
        $a = (new MutableDecimal(1))->setMode(CalcMode::Native);
        $b = (new MutableDecimal(2))->setMode(CalcMode::Native);
        $c = (new MutableDecimal(3))->setMode(CalcMode::Native);
        $d = (new MutableDecimal(10))->setMode(CalcMode::Native);
        $e = (new MutableDecimal(100000000000))->setMode(CalcMode::Native);
        $f = (new MutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $g = (new MutableDecimal(-1))->setMode(CalcMode::Native);
        $h = (new MutableDecimal(-2))->setMode(CalcMode::Native);
        $i = (new MutableDecimal(-3))->setMode(CalcMode::Native);
        $j = (new MutableDecimal(-10))->setMode(CalcMode::Native);
        $k = (new MutableDecimal(-100000000000))->setMode(CalcMode::Native);
        $l = (new MutableDecimal('-1000000000000000000000000000000'))->setMode(CalcMode::Native);

        return [
            'MDecimal arccsc(1)' => [$a, '1.5707963268', NumberBase::Ten, 10],
            'MDecimal arccsc(2)' => [$b, '0.5235987756', NumberBase::Ten, 10],
            'MDecimal arccsc(3)' => [$c, '0.3398369095', NumberBase::Ten, 10],
            'MDecimal arccsc(10)' => [$d, '0.1001674212', NumberBase::Ten, 10],
            'MDecimal arccsc(100000000000)' => [$e, '0', NumberBase::Ten, 10],
            'MDecimal arccsc(1000000000000000000000000000000)' => [$f, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal arccsc(-1)' => [$g, '-1.5707963268', NumberBase::Ten, 10],
            'MDecimal arccsc(-2)' => [$h, '-0.5235987756', NumberBase::Ten, 10],
            'MDecimal arccsc(-3)' => [$i, '-0.3398369095', NumberBase::Ten, 10],
            'MDecimal arccsc(-10)' => [$j, '-0.1001674212', NumberBase::Ten, 10],
            'MDecimal arccsc(-100000000000)' => [$k, '0', NumberBase::Ten, 10],
            'MDecimal arccsc(-1000000000000000000000000000000)' => [$l, IncompatibleObjectState::class, NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider arccscImmutableProvider
     * @dataProvider arccscMutableProvider
     */
    public function testArccsc(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->arccsc();
        } else {
            $answer = $num->arccsc();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * arccot()
     */

    public function arccotImmutableProvider(): array
    {
        $a = (new ImmutableDecimal('1'))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal('2'))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal('3'))->setMode(CalcMode::Native);
        $d = (new ImmutableDecimal('10'))->setMode(CalcMode::Native);
        $e = (new ImmutableDecimal('100000000000'))->setMode(CalcMode::Native);
        $f = (new ImmutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $h = (new ImmutableDecimal('-1'))->setMode(CalcMode::Native);
        $i = (new ImmutableDecimal('-2'))->setMode(CalcMode::Native);
        $j = (new ImmutableDecimal('-3'))->setMode(CalcMode::Native);
        $k = (new ImmutableDecimal('-10'))->setMode(CalcMode::Native);
        $l = (new ImmutableDecimal('-100000000000'))->setMode(CalcMode::Native);
        $m = (new ImmutableDecimal('-1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $n = (new ImmutableDecimal('-0.0000000001'))->setMode(CalcMode::Native);

        return [
            'IDecimal arccot(1)' => [$a, '0.7853981634', NumberBase::Ten, 10],
            'IDecimal arccot(2)' => [$b, '0.463647609', NumberBase::Ten, 10],
            'IDecimal arccot(3)' => [$c, '0.3217505544', NumberBase::Ten, 10],
            'IDecimal arccot(10)' => [$d, '0.0996686525', NumberBase::Ten, 10],
            'IDecimal arccot(100000000000)' => [$e, '0', NumberBase::Ten, 10],
            'IDecimal arccot(1000000000000000000000000000000)' => [$f, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal arccot(0.0000000001)' => [$g, '1.5707963267', NumberBase::Ten, 10],
            'IDecimal arccot(-1)' => [$h, '-0.7853981634', NumberBase::Ten, 10],
            'IDecimal arccot(-2)' => [$i, '-0.463647609', NumberBase::Ten, 10],
            'IDecimal arccot(-3)' => [$j, '-0.3217505544', NumberBase::Ten, 10],
            'IDecimal arccot(-10)' => [$k, '-0.0996686525', NumberBase::Ten, 10],
            'IDecimal arccot(-100000000000)' => [$l, '0', NumberBase::Ten, 10],
            'IDecimal arccot(-1000000000000000000000000000000)' => [$m, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal arccot(-0.0000000001)' => [$n, '-1.5707963267', NumberBase::Ten, 10],
        ];
    }

    public function arccotMutableProvider(): array
    {
        $a = (new MutableDecimal('1'))->setMode(CalcMode::Native);
        $b = (new MutableDecimal('2'))->setMode(CalcMode::Native);
        $c = (new MutableDecimal('3'))->setMode(CalcMode::Native);
        $d = (new MutableDecimal('10'))->setMode(CalcMode::Native);
        $e = (new MutableDecimal('100000000000'))->setMode(CalcMode::Native);
        $f = (new MutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $h = (new MutableDecimal('-1'))->setMode(CalcMode::Native);
        $i = (new MutableDecimal('-2'))->setMode(CalcMode::Native);
        $j = (new MutableDecimal('-3'))->setMode(CalcMode::Native);
        $k = (new MutableDecimal('-10'))->setMode(CalcMode::Native);
        $l = (new MutableDecimal('-100000000000'))->setMode(CalcMode::Native);
        $m = (new MutableDecimal('-1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $n = (new MutableDecimal('-0.0000000001'))->setMode(CalcMode::Native);

        return [
            'MDecimal arccot(1)' => [$a, '0.7853981634', NumberBase::Ten, 10],
            'MDecimal arccot(2)' => [$b, '0.463647609', NumberBase::Ten, 10],
            'MDecimal arccot(3)' => [$c, '0.3217505544', NumberBase::Ten, 10],
            'MDecimal arccot(10)' => [$d, '0.0996686525', NumberBase::Ten, 10],
            'MDecimal arccot(100000000000)' => [$e, '0', NumberBase::Ten, 10],
            'MDecimal arccot(1000000000000000000000000000000)' => [$f, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal arccot(0.0000000001)' => [$g, '1.5707963267', NumberBase::Ten, 10],
            'MDecimal arccot(-1)' => [$h, '-0.7853981634', NumberBase::Ten, 10],
            'MDecimal arccot(-2)' => [$i, '-0.463647609', NumberBase::Ten, 10],
            'MDecimal arccot(-3)' => [$j, '-0.3217505544', NumberBase::Ten, 10],
            'MDecimal arccot(-10)' => [$k, '-0.0996686525', NumberBase::Ten, 10],
            'MDecimal arccot(-100000000000)' => [$l, '0', NumberBase::Ten, 10],
            'MDecimal arccot(-1000000000000000000000000000000)' => [$m, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal arccot(-0.0000000001)' => [$n, '-1.5707963267', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider arccotImmutableProvider
     * @dataProvider arccotMutableProvider
     */
    public function testArccot(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->arccot();
        } else {
            $answer = $num->arccot();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

}