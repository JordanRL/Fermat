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

}