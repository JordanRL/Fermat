<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Decimal;

/**
 * @group BasicTrigonometry
 */
class TrigonometryAutoTest extends TestCase
{

    /*
     * SIN()
     */

    public function sinImmutableProvider(): array
    {
        $a = new ImmutableDecimal(1);
        $b = new ImmutableDecimal(2);
        $c = new ImmutableDecimal(3);
        $d = new ImmutableDecimal(10);
        $e = new ImmutableDecimal(100000000000);
        $f = new ImmutableDecimal('1000000000000000000000000000000');
        $g = new ImmutableDecimal('0.0000000001');
        $h = new ImmutableDecimal(Numbers::makePi(10), 10);
        $i = new ImmutableDecimal(Numbers::makePi(15), 15);
        $j = new ImmutableDecimal(Numbers::makeTau(10), 10);
        $k = new ImmutableDecimal(Numbers::makeTau(15), 15);
        $l = new ImmutableDecimal(0);
        $m = new ImmutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new ImmutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);

        return [
            'IDecimal sin(1)' => [$a, '0.8414709848', NumberBase::Ten, 10],
            'IDecimal sin(2)' => [$b, '0.9092974268', NumberBase::Ten, 10],
            'IDecimal sin(3)' => [$c, '0.1411200081', NumberBase::Ten, 10],
            'IDecimal sin(10)' => [$d, '-0.5440211109', NumberBase::Ten, 10],
            'IDecimal sin(100000000000)' => [$e, '0.9286936605', NumberBase::Ten, 10],
            'IDecimal sin(1000000000000000000000000000000)' => [$f, '-0.0901169019', NumberBase::Ten, 10],
            'IDecimal sin(0.0000000001)' => [$g, '0.0000000001', NumberBase::Ten, 10],
            'IDecimal sin(Pi) scale 10' => [$h, '0', NumberBase::Ten, 10],
            'IDecimal sin(Pi) scale 15' => [$i, '0', NumberBase::Ten, 15],
            'IDecimal sin(2Pi) scale 10' => [$j, '0', NumberBase::Ten, 10],
            'IDecimal sin(2Pi) scale 15' => [$k, '0', NumberBase::Ten, 15],
            'IDecimal sin(0)' => [$l, '0', NumberBase::Ten, 10],
            'IDecimal sin(Pi/2) scale 10' => [$m, '1', NumberBase::Ten, 10],
            'IDecimal sin(Pi/2) scale 15' => [$n, '1', NumberBase::Ten, 15],
        ];
    }

    public function sinMutableProvider(): array
    {
        $a = new MutableDecimal(1);
        $b = new MutableDecimal(2);
        $c = new MutableDecimal(3);
        $d = new MutableDecimal(10);
        $e = new MutableDecimal(100000000000);
        $f = new MutableDecimal('1000000000000000000000000000000');
        $g = new MutableDecimal('0.0000000001');
        $h = new MutableDecimal(Numbers::makePi(10), 10);
        $i = new MutableDecimal(Numbers::makePi(15), 15);
        $j = new MutableDecimal(Numbers::makeTau(10), 10);
        $k = new MutableDecimal(Numbers::makeTau(15), 15);
        $l = new MutableDecimal(0);
        $m = new MutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new MutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);

        return [
            'MDecimal sin(1)' => [$a, '0.8414709848', NumberBase::Ten, 10],
            'MDecimal sin(2)' => [$b, '0.9092974268', NumberBase::Ten, 10],
            'MDecimal sin(3)' => [$c, '0.1411200081', NumberBase::Ten, 10],
            'MDecimal sin(10)' => [$d, '-0.5440211109', NumberBase::Ten, 10],
            'MDecimal sin(100000000000)' => [$e, '0.9286936605', NumberBase::Ten, 10],
            'MDecimal sin(1000000000000000000000000000000)' => [$f, '-0.0901169019', NumberBase::Ten, 10],
            'MDecimal sin(0.0000000001)' => [$g, '0.0000000001', NumberBase::Ten, 10],
            'MDecimal sin(Pi) scale 10' => [$h, '0', NumberBase::Ten, 10],
            'MDecimal sin(Pi) scale 15' => [$i, '0', NumberBase::Ten, 15],
            'MDecimal sin(2Pi) scale 10' => [$j, '0', NumberBase::Ten, 10],
            'MDecimal sin(2Pi) scale 15' => [$k, '0', NumberBase::Ten, 15],
            'MDecimal sin(0)' => [$l, '0', NumberBase::Ten, 10],
            'MDecimal sin(Pi/2) scale 10' => [$m, '1', NumberBase::Ten, 10],
            'MDecimal sin(Pi/2) scale 15' => [$n, '1', NumberBase::Ten, 15],
        ];
    }

    /**
     * @dataProvider sinImmutableProvider
     * @dataProvider sinMutableProvider
     */
    public function testSin(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->sin();
        } else {
            $answer = $num->sin();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * COS()
     */

    public function cosImmutableProvider(): array
    {
        $a = new ImmutableDecimal(1);
        $b = new ImmutableDecimal(2);
        $c = new ImmutableDecimal(3);
        $d = new ImmutableDecimal(10);
        $e = new ImmutableDecimal(100000000000);
        $f = new ImmutableDecimal('1000000000000000000000000000000');
        $g = new ImmutableDecimal('0.0000000001');
        $h = new ImmutableDecimal(Numbers::makePi(10), 10);
        $i = new ImmutableDecimal(Numbers::makePi(15), 15);
        $j = new ImmutableDecimal(Numbers::makeTau(10), 10);
        $k = new ImmutableDecimal(Numbers::makeTau(15), 15);
        $l = new ImmutableDecimal(0);
        $m = new ImmutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new ImmutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);
        $o = new ImmutableDecimal(Numbers::makePi(12)->multiply(3)->divide(2)->truncateToScale(10), 10);
        $p = new ImmutableDecimal(Numbers::makePi(17)->multiply(3)->divide(2)->truncateToScale(15), 15);

        return [
            'IDecimal cos(1)' => [$a, '0.5403023059', NumberBase::Ten, 10],
            'IDecimal cos(2)' => [$b, '-0.4161468365', NumberBase::Ten, 10],
            'IDecimal cos(3)' => [$c, '-0.9899924966', NumberBase::Ten, 10],
            'IDecimal cos(10)' => [$d, '-0.8390715291', NumberBase::Ten, 10],
            'IDecimal cos(100000000000)' => [$e, '0.3708477922', NumberBase::Ten, 10],
            'IDecimal cos(1000000000000000000000000000000)' => [$f, '-0.9959311944', NumberBase::Ten, 10],
            'IDecimal cos(0.0000000001)' => [$g, '1', NumberBase::Ten, 10],
            'IDecimal cos(Pi) scale 10' => [$h, '-1', NumberBase::Ten, 10],
            'IDecimal cos(Pi) scale 15' => [$i, '-1', NumberBase::Ten, 15],
            'IDecimal cos(2Pi) scale 10' => [$j, '1', NumberBase::Ten, 10],
            'IDecimal cos(2Pi) scale 15' => [$k, '1', NumberBase::Ten, 15],
            'IDecimal cos(0)' => [$l, '1', NumberBase::Ten, 10],
            'IDecimal cos(Pi/2) scale 10' => [$m, '0', NumberBase::Ten, 10],
            'IDecimal cos(Pi/2) scale 15' => [$n, '0', NumberBase::Ten, 15],
            'IDecimal cos(3Pi/2) scale 10' => [$o, '0', NumberBase::Ten, 10],
            'IDecimal cos(3Pi/2) scale 15' => [$p, '0', NumberBase::Ten, 15],
        ];
    }

    public function cosMutableProvider(): array
    {
        $a = new MutableDecimal(1);
        $b = new MutableDecimal(2);
        $c = new MutableDecimal(3);
        $d = new MutableDecimal(10);
        $e = new MutableDecimal(100000000000);
        $f = new MutableDecimal('1000000000000000000000000000000');
        $g = new MutableDecimal('0.0000000001');
        $h = new MutableDecimal(Numbers::makePi(10), 10);
        $i = new MutableDecimal(Numbers::makePi(15), 15);
        $j = new MutableDecimal(Numbers::makeTau(10), 10);
        $k = new MutableDecimal(Numbers::makeTau(15), 15);
        $l = new MutableDecimal(0);
        $m = new MutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new MutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);
        $o = new ImmutableDecimal(Numbers::makePi(12)->multiply(3)->divide(2)->truncateToScale(10), 10);
        $p = new ImmutableDecimal(Numbers::makePi(17)->multiply(3)->divide(2)->truncateToScale(15), 15);

        return [
            'MDecimal cos(1)' => [$a, '0.5403023059', NumberBase::Ten, 10],
            'MDecimal cos(2)' => [$b, '-0.4161468365', NumberBase::Ten, 10],
            'MDecimal cos(3)' => [$c, '-0.9899924966', NumberBase::Ten, 10],
            'MDecimal cos(10)' => [$d, '-0.8390715291', NumberBase::Ten, 10],
            'MDecimal cos(100000000000)' => [$e, '0.3708477922', NumberBase::Ten, 10],
            'MDecimal cos(1000000000000000000000000000000)' => [$f, '-0.9959311944', NumberBase::Ten, 10],
            'MDecimal cos(0.0000000001)' => [$g, '1', NumberBase::Ten, 10],
            'MDecimal cos(Pi) scale 10' => [$h, '-1', NumberBase::Ten, 10],
            'MDecimal cos(Pi) scale 15' => [$i, '-1', NumberBase::Ten, 15],
            'MDecimal cos(2Pi) scale 10' => [$j, '1', NumberBase::Ten, 10],
            'MDecimal cos(2Pi) scale 15' => [$k, '1', NumberBase::Ten, 15],
            'MDecimal cos(0)' => [$l, '1', NumberBase::Ten, 10],
            'MDecimal cos(Pi/2) scale 10' => [$m, '0', NumberBase::Ten, 10],
            'MDecimal cos(Pi/2) scale 15' => [$n, '0', NumberBase::Ten, 15],
            'MDecimal cos(3Pi/2) scale 10' => [$o, '0', NumberBase::Ten, 10],
            'MDecimal cos(3Pi/2) scale 15' => [$p, '0', NumberBase::Ten, 15],
        ];
    }

    /**
     * @dataProvider cosImmutableProvider
     * @dataProvider cosMutableProvider
     */
    public function testCos(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->cos();
        } else {
            $answer = $num->cos();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * TAN()
     */

    public function tanImmutableProvider(): array
    {
        $a = new ImmutableDecimal(1);
        $b = new ImmutableDecimal(2);
        $c = new ImmutableDecimal(3);
        $d = new ImmutableDecimal(10);
        $e = new ImmutableDecimal(100000000000);
        $f = new ImmutableDecimal('1000000000000000000000000000000');
        $g = new ImmutableDecimal('0.0000000001');
        $h = new ImmutableDecimal(Numbers::makePi(10), 10);
        $i = new ImmutableDecimal(Numbers::makePi(15), 15);
        $j = new ImmutableDecimal(Numbers::makeTau(10), 10);
        $k = new ImmutableDecimal(Numbers::makeTau(15), 15);
        $l = new ImmutableDecimal(0);
        $m = new ImmutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new ImmutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);
        $o = new ImmutableDecimal(Numbers::makePi(12)->multiply(3)->divide(2)->truncateToScale(10), 10);
        $p = new ImmutableDecimal(Numbers::makePi(17)->multiply(3)->divide(2)->truncateToScale(15), 15);

        return [
            'IDecimal tan(1)' => [$a, '1.5574077247', NumberBase::Ten, 10],
            'IDecimal tan(2)' => [$b, '-2.1850398633', NumberBase::Ten, 10],
            'IDecimal tan(3)' => [$c, '-0.1425465431', NumberBase::Ten, 10],
            'IDecimal tan(10)' => [$d, '0.6483608275', NumberBase::Ten, 10],
            'IDecimal tan(100000000000)' => [$e, '2.5042448145', NumberBase::Ten, 10],
            'IDecimal tan(1000000000000000000000000000000)' => [$f, '0.0904850681', NumberBase::Ten, 10],
            'IDecimal tan(0.0000000001)' => [$g, '0', NumberBase::Ten, 10],
            'IDecimal tan(Pi) scale 10' => [$h, '0', NumberBase::Ten, 10],
            'IDecimal tan(Pi) scale 15' => [$i, '0', NumberBase::Ten, 15],
            'IDecimal tan(2Pi) scale 10' => [$j, '0', NumberBase::Ten, 10],
            'IDecimal tan(2Pi) scale 15' => [$k, '0', NumberBase::Ten, 15],
            'IDecimal tan(0)' => [$l, '0', NumberBase::Ten, 10],
            'IDecimal tan(Pi/2) scale 10' => [$m, 'INF', NumberBase::Ten, 10],
            'IDecimal tan(Pi/2) scale 15' => [$n, 'INF', NumberBase::Ten, 15],
            'IDecimal tan(3Pi/2) scale 10' => [$o, '-INF', NumberBase::Ten, 10],
            'IDecimal tan(3Pi/2) scale 15' => [$p, '-INF', NumberBase::Ten, 15],
        ];
    }

    public function tanMutableProvider(): array
    {
        $a = new MutableDecimal(1);
        $b = new MutableDecimal(2);
        $c = new MutableDecimal(3);
        $d = new MutableDecimal(10);
        $e = new MutableDecimal(100000000000);
        $f = new MutableDecimal('1000000000000000000000000000000');
        $g = new MutableDecimal('0.0000000001');
        $h = new MutableDecimal(Numbers::makePi(10), 10);
        $i = new MutableDecimal(Numbers::makePi(15), 15);
        $j = new MutableDecimal(Numbers::makeTau(10), 10);
        $k = new MutableDecimal(Numbers::makeTau(15), 15);
        $l = new MutableDecimal(0);
        $m = new MutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new MutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);
        $o = new ImmutableDecimal(Numbers::makePi(12)->multiply(3)->divide(2)->truncateToScale(10), 10);
        $p = new ImmutableDecimal(Numbers::makePi(17)->multiply(3)->divide(2)->truncateToScale(15), 15);

        return [
            'MDecimal tan(1)' => [$a, '1.5574077247', NumberBase::Ten, 10],
            'MDecimal tan(2)' => [$b, '-2.1850398633', NumberBase::Ten, 10],
            'MDecimal tan(3)' => [$c, '-0.1425465431', NumberBase::Ten, 10],
            'MDecimal tan(10)' => [$d, '0.6483608275', NumberBase::Ten, 10],
            'MDecimal tan(100000000000)' => [$e, '2.5042448145', NumberBase::Ten, 10],
            'MDecimal tan(1000000000000000000000000000000)' => [$f, '0.0904850681', NumberBase::Ten, 10],
            'MDecimal tan(0.0000000001)' => [$g, '0', NumberBase::Ten, 10],
            'MDecimal tan(Pi) scale 10' => [$h, '0', NumberBase::Ten, 10],
            'MDecimal tan(Pi) scale 15' => [$i, '0', NumberBase::Ten, 15],
            'MDecimal tan(2Pi) scale 10' => [$j, '0', NumberBase::Ten, 10],
            'MDecimal tan(2Pi) scale 15' => [$k, '0', NumberBase::Ten, 15],
            'MDecimal tan(0)' => [$l, '0', NumberBase::Ten, 10],
            'MDecimal tan(Pi/2) scale 10' => [$m, 'INF', NumberBase::Ten, 10],
            'MDecimal tan(Pi/2) scale 15' => [$n, 'INF', NumberBase::Ten, 15],
            'MDecimal tan(3Pi/2) scale 10' => [$o, '-INF', NumberBase::Ten, 10],
            'MDecimal tan(3Pi/2) scale 15' => [$p, '-INF', NumberBase::Ten, 15],
        ];
    }

    /**
     * @dataProvider tanImmutableProvider
     * @dataProvider tanMutableProvider
     */
    public function testTan(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->tan();
        } else {
            $answer = $num->tan();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * SEC()
     */

    public function secImmutableProvider(): array
    {
        $a = new ImmutableDecimal(1);
        $b = new ImmutableDecimal(2);
        $c = new ImmutableDecimal(3);
        $d = new ImmutableDecimal(10);
        $e = new ImmutableDecimal(100000000000);
        $f = new ImmutableDecimal('1000000000000000000000000000000');
        $g = new ImmutableDecimal('0.0000000001');
        $h = new ImmutableDecimal(Numbers::makePi(10), 10);
        $i = new ImmutableDecimal(Numbers::makePi(15), 15);
        $j = new ImmutableDecimal(Numbers::makeTau(10), 10);
        $k = new ImmutableDecimal(Numbers::makeTau(15), 15);
        $l = new ImmutableDecimal(0);
        $m = new ImmutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new ImmutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);
        $o = new ImmutableDecimal(Numbers::makePi(12)->multiply(3)->divide(2)->truncateToScale(10), 10);
        $p = new ImmutableDecimal(Numbers::makePi(17)->multiply(3)->divide(2)->truncateToScale(15), 15);

        return [
            'IDecimal sec(1)' => [$a, '1.8508157177', NumberBase::Ten, 10],
            'IDecimal sec(2)' => [$b, '-2.4029979617', NumberBase::Ten, 10],
            'IDecimal sec(3)' => [$c, '-1.0101086659', NumberBase::Ten, 10],
            'IDecimal sec(10)' => [$d, '-1.1917935067', NumberBase::Ten, 10],
            'IDecimal sec(100000000000)' => [$e, '2.6965240757', NumberBase::Ten, 10],
            'IDecimal sec(1000000000000000000000000000000)' => [$f, '-1.0040854284', NumberBase::Ten, 10],
            'IDecimal sec(0.0000000001)' => [$g, '1', NumberBase::Ten, 10],
            'IDecimal sec(Pi) scale 10' => [$h, '-1', NumberBase::Ten, 10],
            'IDecimal sec(Pi) scale 15' => [$i, '-1', NumberBase::Ten, 15],
            'IDecimal sec(2Pi) scale 10' => [$j, '1', NumberBase::Ten, 10],
            'IDecimal sec(2Pi) scale 15' => [$k, '1', NumberBase::Ten, 15],
            'IDecimal sec(0)' => [$l, '1', NumberBase::Ten, 10],
            'IDecimal sec(Pi/2) scale 10' => [$m, 'INF', NumberBase::Ten, 10],
            'IDecimal sec(Pi/2) scale 15' => [$n, 'INF', NumberBase::Ten, 15],
            'IDecimal sec(3Pi/2) scale 10' => [$o, '-INF', NumberBase::Ten, 10],
            'IDecimal sec(3Pi/2) scale 15' => [$p, '-INF', NumberBase::Ten, 15],
        ];
    }

    public function secMutableProvider(): array
    {
        $a = new MutableDecimal(1);
        $b = new MutableDecimal(2);
        $c = new MutableDecimal(3);
        $d = new MutableDecimal(10);
        $e = new MutableDecimal(100000000000);
        $f = new MutableDecimal('1000000000000000000000000000000');
        $g = new MutableDecimal('0.0000000001');
        $h = new MutableDecimal(Numbers::makePi(10), 10);
        $i = new MutableDecimal(Numbers::makePi(15), 15);
        $j = new MutableDecimal(Numbers::makeTau(10), 10);
        $k = new MutableDecimal(Numbers::makeTau(15), 15);
        $l = new MutableDecimal(0);
        $m = new MutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new MutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);
        $o = new ImmutableDecimal(Numbers::makePi(12)->multiply(3)->divide(2)->truncateToScale(10), 10);
        $p = new ImmutableDecimal(Numbers::makePi(17)->multiply(3)->divide(2)->truncateToScale(15), 15);

        return [
            'MDecimal sec(1)' => [$a, '1.8508157177', NumberBase::Ten, 10],
            'MDecimal sec(2)' => [$b, '-2.4029979617', NumberBase::Ten, 10],
            'MDecimal sec(3)' => [$c, '-1.0101086659', NumberBase::Ten, 10],
            'MDecimal sec(10)' => [$d, '-1.1917935067', NumberBase::Ten, 10],
            'MDecimal sec(100000000000)' => [$e, '2.6965240757', NumberBase::Ten, 10],
            'MDecimal sec(1000000000000000000000000000000)' => [$f, '-1.0040854284', NumberBase::Ten, 10],
            'MDecimal sec(0.0000000001)' => [$g, '1', NumberBase::Ten, 10],
            'MDecimal sec(Pi) scale 10' => [$h, '-1', NumberBase::Ten, 10],
            'MDecimal sec(Pi) scale 15' => [$i, '-1', NumberBase::Ten, 15],
            'MDecimal sec(2Pi) scale 10' => [$j, '1', NumberBase::Ten, 10],
            'MDecimal sec(2Pi) scale 15' => [$k, '1', NumberBase::Ten, 15],
            'MDecimal sec(0)' => [$l, '1', NumberBase::Ten, 10],
            'MDecimal sec(Pi/2) scale 10' => [$m, 'INF', NumberBase::Ten, 10],
            'MDecimal sec(Pi/2) scale 15' => [$n, 'INF', NumberBase::Ten, 15],
            'MDecimal sec(3Pi/2) scale 10' => [$o, '-INF', NumberBase::Ten, 10],
            'MDecimal sec(3Pi/2) scale 15' => [$p, '-INF', NumberBase::Ten, 15],
        ];
    }

    /**
     * @dataProvider secImmutableProvider
     * @dataProvider secMutableProvider
     */
    public function testSec(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->sec();
        } else {
            $answer = $num->sec();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * CSC()
     */

    public function cscImmutableProvider(): array
    {
        $a = new ImmutableDecimal(1);
        $b = new ImmutableDecimal(2);
        $c = new ImmutableDecimal(3);
        $d = new ImmutableDecimal(10);
        $e = new ImmutableDecimal(100000000000);
        $f = new ImmutableDecimal('1000000000000000000000000000000');
        $g = new ImmutableDecimal('0.0000000001');
        $h = new ImmutableDecimal(Numbers::makePi(10), 10);
        $i = new ImmutableDecimal(Numbers::makePi(15), 15);
        $j = new ImmutableDecimal(Numbers::makeTau(10), 10);
        $k = new ImmutableDecimal(Numbers::makeTau(15), 15);
        $l = new ImmutableDecimal(0);
        $m = new ImmutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new ImmutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);
        $o = new ImmutableDecimal(Numbers::makePi(12)->multiply(3)->divide(2)->truncateToScale(10), 10);
        $p = new ImmutableDecimal(Numbers::makePi(17)->multiply(3)->divide(2)->truncateToScale(15), 15);

        return [
            'IDecimal csc(1)' => [$a, '1.1883951058', NumberBase::Ten, 10],
            'IDecimal csc(2)' => [$b, '1.0997501703', NumberBase::Ten, 10],
            'IDecimal csc(3)' => [$c, '7.0861673957', NumberBase::Ten, 10],
            'IDecimal csc(10)' => [$d, '-1.8381639609', NumberBase::Ten, 10],
            'IDecimal csc(100000000000)' => [$e, '1.0767813355', NumberBase::Ten, 10],
            'IDecimal csc(1000000000000000000000000000000)' => [$f, '-11.0966974983', NumberBase::Ten, 10],
            'IDecimal csc(0.0000000001)' => [$g, '10000000000', NumberBase::Ten, 10],
            'IDecimal csc(Pi) scale 10' => [$h, 'INF', NumberBase::Ten, 10],
            'IDecimal csc(Pi) scale 15' => [$i, 'INF', NumberBase::Ten, 15],
            'IDecimal csc(2Pi) scale 10' => [$j, '-INF', NumberBase::Ten, 10],
            'IDecimal csc(2Pi) scale 15' => [$k, '-INF', NumberBase::Ten, 15],
            'IDecimal csc(0)' => [$l, 'INF', NumberBase::Ten, 10],
            'IDecimal csc(Pi/2) scale 10' => [$m, '1', NumberBase::Ten, 10],
            'IDecimal csc(Pi/2) scale 15' => [$n, '1', NumberBase::Ten, 15],
            'IDecimal csc(3Pi/2) scale 10' => [$o, '-1', NumberBase::Ten, 10],
            'IDecimal csc(3Pi/2) scale 15' => [$p, '-1', NumberBase::Ten, 15],
        ];
    }

    public function cscMutableProvider(): array
    {
        $a = new MutableDecimal(1);
        $b = new MutableDecimal(2);
        $c = new MutableDecimal(3);
        $d = new MutableDecimal(10);
        $e = new MutableDecimal(100000000000);
        $f = new MutableDecimal('1000000000000000000000000000000');
        $g = new MutableDecimal('0.0000000001');
        $h = new MutableDecimal(Numbers::makePi(10), 10);
        $i = new MutableDecimal(Numbers::makePi(15), 15);
        $j = new MutableDecimal(Numbers::makeTau(10), 10);
        $k = new MutableDecimal(Numbers::makeTau(15), 15);
        $l = new MutableDecimal(0);
        $m = new MutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new MutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);
        $o = new ImmutableDecimal(Numbers::makePi(12)->multiply(3)->divide(2)->truncateToScale(10), 10);
        $p = new ImmutableDecimal(Numbers::makePi(17)->multiply(3)->divide(2)->truncateToScale(15), 15);

        return [
            'MDecimal csc(1)' => [$a, '1.1883951058', NumberBase::Ten, 10],
            'MDecimal csc(2)' => [$b, '1.0997501703', NumberBase::Ten, 10],
            'MDecimal csc(3)' => [$c, '7.0861673957', NumberBase::Ten, 10],
            'MDecimal csc(10)' => [$d, '-1.8381639609', NumberBase::Ten, 10],
            'MDecimal csc(100000000000)' => [$e, '1.0767813355', NumberBase::Ten, 10],
            'MDecimal csc(1000000000000000000000000000000)' => [$f, '-11.0966974983', NumberBase::Ten, 10],
            'MDecimal csc(0.0000000001)' => [$g, '10000000000', NumberBase::Ten, 10],
            'MDecimal csc(Pi) scale 10' => [$h, 'INF', NumberBase::Ten, 10],
            'MDecimal csc(Pi) scale 15' => [$i, 'INF', NumberBase::Ten, 15],
            'MDecimal csc(2Pi) scale 10' => [$j, '-INF', NumberBase::Ten, 10],
            'MDecimal csc(2Pi) scale 15' => [$k, '-INF', NumberBase::Ten, 15],
            'MDecimal csc(0)' => [$l, 'INF', NumberBase::Ten, 10],
            'MDecimal csc(Pi/2) scale 10' => [$m, '1', NumberBase::Ten, 10],
            'MDecimal csc(Pi/2) scale 15' => [$n, '1', NumberBase::Ten, 15],
            'MDecimal csc(3Pi/2) scale 10' => [$o, '-1', NumberBase::Ten, 10],
            'MDecimal csc(3Pi/2) scale 15' => [$p, '-1', NumberBase::Ten, 15],
        ];
    }

    /**
     * @dataProvider cscImmutableProvider
     * @dataProvider cscMutableProvider
     */
    public function testCsc(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->csc();
        } else {
            $answer = $num->csc();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * COT()
     */

    public function cotImmutableProvider(): array
    {
        $a = new ImmutableDecimal(1);
        $b = new ImmutableDecimal(2);
        $c = new ImmutableDecimal(3);
        $d = new ImmutableDecimal(10);
        $e = new ImmutableDecimal(100000000000);
        $f = new ImmutableDecimal('1000000000000000000000000000000');
        $g = new ImmutableDecimal('0.0000000001');
        $h = new ImmutableDecimal(Numbers::makePi(10), 10);
        $i = new ImmutableDecimal(Numbers::makePi(15), 15);
        $j = new ImmutableDecimal(Numbers::makeTau(10), 10);
        $k = new ImmutableDecimal(Numbers::makeTau(15), 15);
        $l = new ImmutableDecimal(0);
        $m = new ImmutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new ImmutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);
        $o = new ImmutableDecimal(Numbers::makePi(12)->multiply(3)->divide(2)->truncateToScale(10), 10);
        $p = new ImmutableDecimal(Numbers::makePi(17)->multiply(3)->divide(2)->truncateToScale(15), 15);

        return [
            'IDecimal cot(1)' => [$a, '0.6420926159', NumberBase::Ten, 10],
            'IDecimal cot(2)' => [$b, '-0.4576575544', NumberBase::Ten, 10],
            'IDecimal cot(3)' => [$c, '-7.0152525514', NumberBase::Ten, 10],
            'IDecimal cot(10)' => [$d, '1.5423510454', NumberBase::Ten, 10],
            'IDecimal cot(100000000000)' => [$e, '0.3993219809', NumberBase::Ten, 10],
            'IDecimal cot(1000000000000000000000000000000)' => [$f, '11.0515471934', NumberBase::Ten, 10],
            'IDecimal cot(0.0000000001)' => [$g, 'INF', NumberBase::Ten, 10],
            'IDecimal cot(Pi) scale 10' => [$h, '-INF', NumberBase::Ten, 10],
            'IDecimal cot(Pi) scale 15' => [$i, '-INF', NumberBase::Ten, 15],
            'IDecimal cot(2Pi) scale 10' => [$j, 'INF', NumberBase::Ten, 10],
            'IDecimal cot(2Pi) scale 15' => [$k, 'INF', NumberBase::Ten, 15],
            'IDecimal cot(0)' => [$l, 'INF', NumberBase::Ten, 10],
            'IDecimal cot(Pi/2) scale 10' => [$m, '0', NumberBase::Ten, 10],
            'IDecimal cot(Pi/2) scale 15' => [$n, '0', NumberBase::Ten, 15],
            'IDecimal cot(3Pi/2) scale 10' => [$o, '0', NumberBase::Ten, 10],
            'IDecimal cot(3Pi/2) scale 15' => [$p, '0', NumberBase::Ten, 15],
        ];
    }

    public function cotMutableProvider(): array
    {
        $a = new MutableDecimal(1);
        $b = new MutableDecimal(2);
        $c = new MutableDecimal(3);
        $d = new MutableDecimal(10);
        $e = new MutableDecimal(100000000000);
        $f = new MutableDecimal('1000000000000000000000000000000');
        $g = new MutableDecimal('0.0000000001');
        $h = new MutableDecimal(Numbers::makePi(10), 10);
        $i = new MutableDecimal(Numbers::makePi(15), 15);
        $j = new MutableDecimal(Numbers::makeTau(10), 10);
        $k = new MutableDecimal(Numbers::makeTau(15), 15);
        $l = new MutableDecimal(0);
        $m = new MutableDecimal(Numbers::makePi(12)->divide(2)->truncateToScale(10), 10);
        $n = new MutableDecimal(Numbers::makePi(17)->divide(2)->truncateToScale(15), 15);
        $o = new ImmutableDecimal(Numbers::makePi(12)->multiply(3)->divide(2)->truncateToScale(10), 10);
        $p = new ImmutableDecimal(Numbers::makePi(17)->multiply(3)->divide(2)->truncateToScale(15), 15);

        return [
            'MDecimal cot(1)' => [$a, '0.6420926159', NumberBase::Ten, 10],
            'MDecimal cot(2)' => [$b, '-0.4576575544', NumberBase::Ten, 10],
            'MDecimal cot(3)' => [$c, '-7.0152525514', NumberBase::Ten, 10],
            'MDecimal cot(10)' => [$d, '1.5423510454', NumberBase::Ten, 10],
            'MDecimal cot(100000000000)' => [$e, '0.3993219809', NumberBase::Ten, 10],
            'MDecimal cot(1000000000000000000000000000000)' => [$f, '11.0515471934', NumberBase::Ten, 10],
            'MDecimal cot(0.0000000001)' => [$g, 'INF', NumberBase::Ten, 10],
            'MDecimal cot(Pi) scale 10' => [$h, '-INF', NumberBase::Ten, 10],
            'MDecimal cot(Pi) scale 15' => [$i, '-INF', NumberBase::Ten, 15],
            'MDecimal cot(2Pi) scale 10' => [$j, 'INF', NumberBase::Ten, 10],
            'MDecimal cot(2Pi) scale 15' => [$k, 'INF', NumberBase::Ten, 15],
            'MDecimal cot(0)' => [$l, 'INF', NumberBase::Ten, 10],
            'MDecimal cot(Pi/2) scale 10' => [$m, '0', NumberBase::Ten, 10],
            'MDecimal cot(Pi/2) scale 15' => [$n, '0', NumberBase::Ten, 15],
            'MDecimal cot(3Pi/2) scale 10' => [$o, '0', NumberBase::Ten, 10],
            'MDecimal cot(3Pi/2) scale 15' => [$p, '0', NumberBase::Ten, 15],
        ];
    }

    /**
     * @dataProvider cotImmutableProvider
     * @dataProvider cotMutableProvider
     */
    public function testCot(Decimal $num, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $num->cot();
        } else {
            $answer = $num->cot();
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

}