<?php

namespace Samsara\Fermat\Core\Values;


use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Enums\CalcMode;

/**
 * @group Arithmetic
 */
class ArithmeticNativeTest extends TestCase
{

    /*
     * ADDITION
     */

    public function additionImmutableDecimalProvider(): array
    {

        $five = (new ImmutableDecimal(5))->setMode(CalcMode::Native);
        $fiveBaseFive = (new ImmutableDecimal(5, null, NumberBase::Five))->setMode(CalcMode::Native);
        $ten = (new ImmutableDecimal(10))->setMode(CalcMode::Native);
        $oneQuarter = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $sixTenths = (new ImmutableDecimal('0.6'))->setMode(CalcMode::Native);
        $fourTenths = (new ImmutableDecimal('0.4'))->setMode(CalcMode::Native);
        $oneTenth = (new ImmutableDecimal('0.1'))->setMode(CalcMode::Native);
        $twoTenths = (new ImmutableDecimal('0.2'))->setMode(CalcMode::Native);
        $tenScale = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $elevenScale = (new ImmutableDecimal('0.00000000001'))->setMode(CalcMode::Native);
        $tenPowThirty = (new ImmutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $negFour = (new ImmutableDecimal('-4'))->setMode(CalcMode::Native);
        $fiveI = (new ImmutableDecimal('5i'))->setMode(CalcMode::Native);
        $tenI = (new ImmutableDecimal('10i'))->setMode(CalcMode::Native);

        return [
            'IDecimal 5+10' => [$five, $ten, '15', NumberBase::Ten, 10],
            'IDecimal 5+10 base 5' => [$fiveBaseFive, $ten, '30', NumberBase::Five, 10],
            'IDecimal 5+1/4' => [$five, $oneQuarter, '5.25', NumberBase::Ten, 10],
            'IDecimal 1/4+5' => [$oneQuarter, $five, '21/4', NumberBase::Ten, 10],
            'IDecimal 0.6+0.4' => [$sixTenths, $fourTenths, '1', NumberBase::Ten, 10],
            'IDecimal 0.1+0.2' => [$oneTenth, $twoTenths, '0.3', NumberBase::Ten, 10],
            'IDecimal 0.1+0.0000000001' => [$oneTenth, $tenScale, '0.1000000001', NumberBase::Ten, 10],
            'IDecimal 0.1+0.00000000001' => [$oneTenth, $elevenScale, '0.1', NumberBase::Ten, 10],
            'IDecimal 1000000000000000000000000000000+5' => [$tenPowThirty, $five, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal 1000000000000000000000000000000+0.00000000001' => [$tenPowThirty, $elevenScale, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal 0.00000000001+1000000000000000000000000000000' => [$elevenScale, $tenPowThirty, IncompatibleObjectState::class, NumberBase::Ten, 11],
            'IDecimal -4+0.1' => [$negFour, $oneTenth, '-3.9', NumberBase::Ten, 10],
            'IDecimal 5+5i' => [$five, $fiveI, '5+5i', NumberBase::Ten, 10],
            'IDecimal 5i+10i' => [$fiveI, $tenI, '15i', NumberBase::Ten, 10],
        ];

    }

    public function additionMutableDecimalProvider(): array
    {

        $five = (new MutableDecimal(5))->setMode(CalcMode::Native);
        $fiveBaseFive = (new MutableDecimal(5, null, NumberBase::Five))->setMode(CalcMode::Native);
        $ten = (new MutableDecimal(10))->setMode(CalcMode::Native);
        $oneQuarter = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $sixTenths = (new MutableDecimal('0.6'))->setMode(CalcMode::Native);
        $fourTenths = (new MutableDecimal('0.4'))->setMode(CalcMode::Native);
        $oneTenth = (new MutableDecimal('0.1'))->setMode(CalcMode::Native);
        $twoTenths = (new MutableDecimal('0.2'))->setMode(CalcMode::Native);
        $tenScale = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $elevenScale = (new MutableDecimal('0.00000000001'))->setMode(CalcMode::Native);
        $tenPowThirty = (new MutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $negFour = (new MutableDecimal('-4'))->setMode(CalcMode::Native);
        $fiveI = (new MutableDecimal('5i'))->setMode(CalcMode::Native);
        $tenI = (new MutableDecimal('10i'))->setMode(CalcMode::Native);

        return [
            'MDecimal 5+10' => [$five, $ten, '15', NumberBase::Ten, 10],
            'MDecimal 5+10 base 5' => [$fiveBaseFive, $ten, '30', NumberBase::Five, 10],
            'MDecimal 5+1/4' => [$five, $oneQuarter, '15.25', NumberBase::Ten, 10],
            'MDecimal 1/4+5' => [$oneQuarter, $five, '31/2', NumberBase::Ten, 10],
            'MDecimal 0.6+0.4' => [$sixTenths, $fourTenths, '1', NumberBase::Ten, 10],
            'MDecimal 0.1+0.2' => [$oneTenth, $twoTenths, '0.3', NumberBase::Ten, 10],
            'MDecimal 0.1+0.0000000001' => [$oneTenth, $tenScale, '0.3000000001', NumberBase::Ten, 10],
            'MDecimal 0.1+0.00000000001' => [$oneTenth, $elevenScale, '0.3000000001', NumberBase::Ten, 10],
            'MDecimal 1000000000000000000000000000000+5' => [$tenPowThirty, $five, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal 1000000000000000000000000000000+0.00000000001' => [$tenPowThirty, $elevenScale, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal 0.00000000001+1000000000000000000000000000000' => [$elevenScale, $tenPowThirty, IncompatibleObjectState::class, NumberBase::Ten, 11],
            'MDecimal -4+0.1' => [$negFour, $oneTenth, '-3.6999999999', NumberBase::Ten, 10],
            'MDecimal 5+5i' => [$five, $fiveI, '15.25+5i', NumberBase::Ten, 10],
            'MDecimal 5i+10i' => [$fiveI, $tenI, '15i', NumberBase::Ten, 10],
        ];

    }

    public function additionImmutableFractionProvider(): array
    {
        $a = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $b = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(5))->setMode(CalcMode::Native));
        $c = new ImmutableFraction((new ImmutableDecimal(3))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $d = new ImmutableFraction((new ImmutableDecimal(4))->setMode(CalcMode::Native), (new ImmutableDecimal(5))->setMode(CalcMode::Native));
        $e = new ImmutableFraction((new ImmutableDecimal(4))->setMode(CalcMode::Native), (new ImmutableDecimal(8))->setMode(CalcMode::Native));
        $f = new ImmutableFraction((new ImmutableDecimal(3))->setMode(CalcMode::Native), (new ImmutableDecimal('10000000000000000000000000'))->setMode(CalcMode::Native));

        return [
            'IFraction 1/4+1/5' => [$a, $b, '9/20', NumberBase::Ten, 10],
            'IFraction 1/4+3/4' => [$a, $c, '1/1', NumberBase::Ten, 10],
            'IFraction 1/5+4/5' => [$b, $d, '1/1', NumberBase::Ten, 10],
            'IFraction 1/5+3/4' => [$b, $c, '19/20', NumberBase::Ten, 10],
            'IFraction 1/4+4/5' => [$a, $d, '21/20', NumberBase::Ten, 10],
            'IFraction 1/4+4/8' => [$a, $e, '3/4', NumberBase::Ten, 10],
            'IFraction 4/8+1/4' => [$e, $a, '3/4', NumberBase::Ten, 10],
            'IFraction 1/4+3/10000000000000000000000000' => [$a, $f, IncompatibleObjectState::class, NumberBase::Ten, 10]
        ];
    }

    /**
     * @dataProvider additionImmutableDecimalProvider
     * @dataProvider additionMutableDecimalProvider
     * @dataProvider additionImmutableFractionProvider
     */
    public function testAddition(Number $a, Number $b, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->add($b);
        } else {
            $answer = $a->add($b);
            $this->assertEquals($expected, $answer->getValue());
            if ($answer instanceof SimpleNumberInterface) {
                $this->assertEquals($base, $answer->getBase());
                $this->assertEquals($scale, $answer->getScale());
            }
        }
    }

    /*
     * SUBTRACTION
     */

    public function subtractionMutableDecimalProvider(): array
    {

        $five = (new MutableDecimal(5))->setMode(CalcMode::Native);
        $fiveBaseFive = (new MutableDecimal(5, null, NumberBase::Five))->setMode(CalcMode::Native);
        $ten = (new MutableDecimal(10))->setMode(CalcMode::Native);
        $oneQuarter = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $sixTenths = (new MutableDecimal('0.6'))->setMode(CalcMode::Native);
        $fourTenths = (new MutableDecimal('0.4'))->setMode(CalcMode::Native);
        $oneTenth = (new MutableDecimal('0.1'))->setMode(CalcMode::Native);
        $twoTenths = (new MutableDecimal('0.2'))->setMode(CalcMode::Native);
        $tenScale = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $elevenScale = (new MutableDecimal('0.00000000001'))->setMode(CalcMode::Native);
        $tenPowThirty = (new MutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $negFour = (new MutableDecimal('-4'))->setMode(CalcMode::Native);
        $fiveI = (new MutableDecimal('5i'))->setMode(CalcMode::Native);
        $tenI = (new MutableDecimal('10i'))->setMode(CalcMode::Native);

        return [
            'MDecimal 5-10' => [$five, $ten, '-5', NumberBase::Ten, 10],
            'MDecimal 5-10 base 5' => [$fiveBaseFive, $ten, '-10', NumberBase::Five, 10],
            'MDecimal 5-1/4' => [$five, $oneQuarter, '-5.25', NumberBase::Ten, 10],
            'MDecimal 1/4-5' => [$oneQuarter, $five, '11/2', NumberBase::Ten, 10],
            'MDecimal 0.6-0.4' => [$sixTenths, $fourTenths, '0.2', NumberBase::Ten, 10],
            'MDecimal 0.1-0.2' => [$oneTenth, $twoTenths, '-0.1', NumberBase::Ten, 10],
            'MDecimal 0.1-0.0000000001' => [$oneTenth, $tenScale, '-0.1000000001', NumberBase::Ten, 10],
            'MDecimal 0.1-0.00000000001' => [$oneTenth, $elevenScale, '-0.1000000001', NumberBase::Ten, 10],
            'MDecimal 1000000000000000000000000000000-5' => [$tenPowThirty, $five, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal 1000000000000000000000000000000-0.00000000001' => [$tenPowThirty, $elevenScale, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal 0.00000000001-1000000000000000000000000000000' => [$elevenScale, $tenPowThirty, IncompatibleObjectState::class, NumberBase::Ten, 11],
            'MDecimal -4-0.1' => [$negFour, $oneTenth, '-3.8999999999', NumberBase::Ten, 10],
            'MDecimal 5-5i' => [$five, $fiveI, '-5.25-5i', NumberBase::Ten, 10],
            'MDecimal 5i-10i' => [$fiveI, $tenI, '-5i', NumberBase::Ten, 10],
        ];

    }

    public function subtractionImmutableDecimalProvider(): array
    {

        $five = (new ImmutableDecimal(5))->setMode(CalcMode::Native);
        $fiveBaseFive = (new ImmutableDecimal(5, null, NumberBase::Five))->setMode(CalcMode::Native);
        $ten = (new ImmutableDecimal(10))->setMode(CalcMode::Native);
        $oneQuarter = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $sixTenths = (new ImmutableDecimal('0.6'))->setMode(CalcMode::Native);
        $fourTenths = (new ImmutableDecimal('0.4'))->setMode(CalcMode::Native);
        $oneTenth = (new ImmutableDecimal('0.1'))->setMode(CalcMode::Native);
        $twoTenths = (new ImmutableDecimal('0.2'))->setMode(CalcMode::Native);
        $tenScale = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $elevenScale = (new ImmutableDecimal('0.00000000001'))->setMode(CalcMode::Native);
        $tenPowThirty = (new ImmutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $negFour = (new ImmutableDecimal('-4'))->setMode(CalcMode::Native);
        $fiveI = (new ImmutableDecimal('5i'))->setMode(CalcMode::Native);
        $tenI = (new ImmutableDecimal('10i'))->setMode(CalcMode::Native);

        return [
            'IDecimal 5-10' => [$five, $ten, '-5', NumberBase::Ten, 10],
            'IDecimal 5-10 base 5' => [$fiveBaseFive, $ten, '-10', NumberBase::Five, 10],
            'IDecimal 5-1/4' => [$five, $oneQuarter, '4.75', NumberBase::Ten, 10],
            'IDecimal 1/4-5' => [$oneQuarter, $five, '-19/4', NumberBase::Ten, 10],
            'IDecimal 0.6-0.4' => [$sixTenths, $fourTenths, '0.2', NumberBase::Ten, 10],
            'IDecimal 0.1-0.2' => [$oneTenth, $twoTenths, '-0.1', NumberBase::Ten, 10],
            'IDecimal 0.1-0.0000000001' => [$oneTenth, $tenScale, '0.0999999999', NumberBase::Ten, 10],
            'IDecimal 0.1-0.00000000001' => [$oneTenth, $elevenScale, '0.1', NumberBase::Ten, 10],
            'IDecimal 1000000000000000000000000000000-5' => [$tenPowThirty, $five, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal 1000000000000000000000000000000-0.00000000001' => [$tenPowThirty, $elevenScale, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal 0.00000000001-1000000000000000000000000000000' => [$elevenScale, $tenPowThirty, IncompatibleObjectState::class, NumberBase::Ten, 11],
            'IDecimal -4-0.1' => [$negFour, $oneTenth, '-4.1', NumberBase::Ten, 10],
            'IDecimal 5-5i' => [$five, $fiveI, '5-5i', NumberBase::Ten, 10],
            'IDecimal 5i-10i' => [$fiveI, $tenI, '-5i', NumberBase::Ten, 10],
        ];

    }

    public function subtractionImmutableFractionProvider(): array
    {
        $a = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $b = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(5))->setMode(CalcMode::Native));
        $c = new ImmutableFraction((new ImmutableDecimal(3))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $d = new ImmutableFraction((new ImmutableDecimal(4))->setMode(CalcMode::Native), (new ImmutableDecimal(5))->setMode(CalcMode::Native));
        $e = new ImmutableFraction((new ImmutableDecimal(4))->setMode(CalcMode::Native), (new ImmutableDecimal(8))->setMode(CalcMode::Native));
        $f = new ImmutableFraction((new ImmutableDecimal(3))->setMode(CalcMode::Native), (new ImmutableDecimal('10000000000000000000000000'))->setMode(CalcMode::Native));

        return [
            'IFraction 1/4-1/5' =>[$a, $b, '1/20', NumberBase::Ten, 10],
            'IFraction 1/4-3/4' =>[$a, $c, '-1/2', NumberBase::Ten, 10],
            'IFraction 1/5-4/5' =>[$b, $d, '-3/5', NumberBase::Ten, 10],
            'IFraction 1/5-3/4' =>[$b, $c, '-11/20', NumberBase::Ten, 10],
            'IFraction 1/4-4/5' =>[$a, $d, '-11/20', NumberBase::Ten, 10],
            'IFraction 1/4-4/8' =>[$a, $e, '-1/4', NumberBase::Ten, 10],
            'IFraction 4/8-1/4' =>[$e, $a, '1/4', NumberBase::Ten, 10],
            'IFraction 1/4-3/10000000000000000000000000' =>[$a, $f, IncompatibleObjectState::class, NumberBase::Ten, 10]
        ];
    }

    /**
     * @dataProvider subtractionImmutableDecimalProvider
     * @dataProvider subtractionMutableDecimalProvider
     * @dataProvider subtractionImmutableFractionProvider
     */
    public function testSubtraction(Number $a, Number $b, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->subtract($b);
        } else {
            $answer = $a->subtract($b);
            $this->assertEquals($expected, $answer->getValue());
            if ($answer instanceof SimpleNumberInterface) {
                $this->assertEquals($base, $answer->getBase());
                $this->assertEquals($scale, $answer->getScale());
            }
        }
    }

    /*
     * MULTIPLICATION
     */

    public function multiplicationImmutableDecimalProvider(): array
    {
        $a = (new ImmutableDecimal('2'))->setMode(CalcMode::Native);
        $a2 = (new ImmutableDecimal('2', null, NumberBase::Five))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal('3'))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal('-2'))->setMode(CalcMode::Native);
        $d = (new ImmutableDecimal('-3'))->setMode(CalcMode::Native);
        $e = (new ImmutableDecimal('0.5'))->setMode(CalcMode::Native);
        $f = (new ImmutableDecimal('-0.5'))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $h = (new ImmutableDecimal('0.00000000001'))->setMode(CalcMode::Native);
        $i = (new ImmutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $j = (new ImmutableDecimal('2i'))->setMode(CalcMode::Native);
        $k = (new ImmutableDecimal('3i'))->setMode(CalcMode::Native);

        return [
            'IDecimal 2*3' => [$a, $b, '6', NumberBase::Ten, 10],
            'IDecimal 2*3 base 5' => [$a2, $b, '11', NumberBase::Five, 10],
            'IDecimal 2*-2' => [$a, $c, '-4', NumberBase::Ten, 10],
            'IDecimal 3*-2' => [$b, $c, '-6', NumberBase::Ten, 10],
            'IDecimal -2*-3' => [$c, $d, '6', NumberBase::Ten, 10],
            'IDecimal 2*0.5' => [$a, $e, '1', NumberBase::Ten, 10],
            'IDecimal 3*0.5' => [$b, $e, '1.5', NumberBase::Ten, 10],
            'IDecimal 2*-0.5' => [$a, $f, '-1', NumberBase::Ten, 10],
            'IDecimal 3*-0.5' => [$b, $f, '-1.5', NumberBase::Ten, 10],
            'IDecimal 2*0.0000000001' => [$a, $g, '0.0000000002', NumberBase::Ten, 10],
            'IDecimal 2*0.00000000001' => [$a, $h, '0', NumberBase::Ten, 10],
            'IDecimal 2*1000000000000000000000000000000' => [$a, $i, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal 0.0000000001*1000000000000000000000000000000' => [$g, $i, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal 2*2i' => [$a, $j, '4i', NumberBase::Ten, 10],
            'IDecimal 2i*3i' => [$j, $k, '-6', NumberBase::Ten, 10],
        ];
    }

    public function multiplicationMutableDecimalProvider(): array
    {
        $a = (new MutableDecimal('2'))->setMode(CalcMode::Native);
        $a2 = (new MutableDecimal('2', null, NumberBase::Five))->setMode(CalcMode::Native);
        $b = (new MutableDecimal('3'))->setMode(CalcMode::Native);
        $c = (new MutableDecimal('-2'))->setMode(CalcMode::Native);
        $d = (new MutableDecimal('-3'))->setMode(CalcMode::Native);
        $e = (new MutableDecimal('0.5'))->setMode(CalcMode::Native);
        $f = (new MutableDecimal('-0.5'))->setMode(CalcMode::Native);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $h = (new MutableDecimal('0.00000000001'))->setMode(CalcMode::Native);
        $i = (new MutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $j = (new MutableDecimal('2i'))->setMode(CalcMode::Native);
        $k = (new MutableDecimal('3i'))->setMode(CalcMode::Native);

        return [
            'MDecimal 2*3' => [$a, $b, '6', NumberBase::Ten, 10],
            'MDecimal 2*3 base 5' => [$a2, $b, '11', NumberBase::Five, 10],
            'MDecimal 2*-2' => [$a, $c, '-12', NumberBase::Ten, 10],
            'MDecimal 3*-2' => [$b, $c, '-6', NumberBase::Ten, 10],
            'MDecimal -2*-3' => [$c, $d, '6', NumberBase::Ten, 10],
            'MDecimal 2*0.5' => [$a, $e, '-6', NumberBase::Ten, 10],
            'MDecimal 3*0.5' => [$b, $e, '-3', NumberBase::Ten, 10],
            'MDecimal 2*-0.5' => [$a, $f, '3', NumberBase::Ten, 10],
            'MDecimal 3*-0.5' => [$b, $f, '1.5', NumberBase::Ten, 10],
            'MDecimal 2*0.0000000001' => [$a, $g, '0.0000000003', NumberBase::Ten, 10],
            'MDecimal 2*0.00000000001' => [$a, $h, '0', NumberBase::Ten, 10],
            'MDecimal 2*1000000000000000000000000000000' => [$a, $i, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal 0.0000000001*1000000000000000000000000000000' => [$g, $i, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal 2*2i' => [$a, $j, '0i', NumberBase::Ten, 10],
            'MDecimal 2i*3i' => [$j, $k, '-6', NumberBase::Ten, 10],
        ];
    }

    public function multiplicationImmutableFractionProvider(): array
    {
        $a = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $b = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(5))->setMode(CalcMode::Native));
        $c = new ImmutableFraction((new ImmutableDecimal(3))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $d = new ImmutableFraction((new ImmutableDecimal(4))->setMode(CalcMode::Native), (new ImmutableDecimal(5))->setMode(CalcMode::Native));
        $e = new ImmutableFraction((new ImmutableDecimal(4))->setMode(CalcMode::Native), (new ImmutableDecimal(8))->setMode(CalcMode::Native));
        $f = new ImmutableFraction((new ImmutableDecimal(3))->setMode(CalcMode::Native), (new ImmutableDecimal('10000000000000000000000000'))->setMode(CalcMode::Native));

        return [
            'IFraction 1/4*1/5' => [$a, $b, '1/20', NumberBase::Ten, 10],
            'IFraction 1/4*3/4' => [$a, $c, '3/16', NumberBase::Ten, 10],
            'IFraction 1/5*4/5' => [$b, $d, '4/25', NumberBase::Ten, 10],
            'IFraction 1/5*3/4' => [$b, $c, '3/20', NumberBase::Ten, 10],
            'IFraction 1/4*4/5' => [$a, $d, '1/5', NumberBase::Ten, 10],
            'IFraction 1/4*4/8' => [$a, $e, '1/8', NumberBase::Ten, 10],
            'IFraction 4/8*1/4' => [$e, $a, '1/8', NumberBase::Ten, 10],
            'IFraction 1/4*3/10000000000000000000000000' => [$a, $f, IncompatibleObjectState::class, NumberBase::Ten, 10]
        ];
    }

    /**
     * @dataProvider multiplicationImmutableDecimalProvider
     * @dataProvider multiplicationMutableDecimalProvider
     * @dataProvider multiplicationImmutableFractionProvider
     */
    public function testMultiplication(Number $a, Number $b, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->multiply($b);
        } else {
            $answer = $a->multiply($b);
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * DIVISION
     */

    public function divisionImmutableDecimalProvider(): array
    {
        $a = (new ImmutableDecimal('2'))->setMode(CalcMode::Native);
        $a2 = (new ImmutableDecimal('2', null, NumberBase::Five))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal('3'))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal('-2'))->setMode(CalcMode::Native);
        $d = (new ImmutableDecimal('-3'))->setMode(CalcMode::Native);
        $e = (new ImmutableDecimal('0.5'))->setMode(CalcMode::Native);
        $f = (new ImmutableDecimal('-0.5'))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $h = (new ImmutableDecimal('0.00000000001'))->setMode(CalcMode::Native);
        $i = (new ImmutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $j = (new ImmutableDecimal('5i'))->setMode(CalcMode::Native);
        $k = (new ImmutableDecimal('10i'))->setMode(CalcMode::Native);
        $l = (new ImmutableDecimal('0'))->setMode(CalcMode::Native);

        return [
            'IDecimal 2 / 3' => [$a, $b, '0.6666666667', NumberBase::Ten, 10],
            'IDecimal 2 / 3 base 5' => [$a2, $b, '0.131313131002111', NumberBase::Five, 10],
            'IDecimal 2 / -2' => [$a, $c, '-1', NumberBase::Ten, 10],
            'IDecimal 3 / -2' => [$b, $c, '-1.5', NumberBase::Ten, 10],
            'IDecimal -2 / -3' => [$c, $d, '0.6666666667', NumberBase::Ten, 10],
            'IDecimal 2 / 0.5' => [$a, $e, '4', NumberBase::Ten, 10],
            'IDecimal 3 / 0.5' => [$b, $e, '6', NumberBase::Ten, 10],
            'IDecimal 2 / -0.5' => [$a, $f, '-4', NumberBase::Ten, 10],
            'IDecimal 3 / -0.5' => [$b, $f, '-6', NumberBase::Ten, 10],
            'IDecimal 2 / 0.0000000001' => [$a, $g, '20000000000', NumberBase::Ten, 10],
            'IDecimal 2 / 0.00000000001' => [$a, $h, '200000000000', NumberBase::Ten, 10],
            'IDecimal 2 / 1000000000000000000000000000000' => [$a, $i, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal 1000000000000000000000000000000 / 2' => [$i, $a, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal 0.0000000001 / 1000000000000000000000000000000' => [$g, $i, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'IDecimal 2 / 5i' => [$a, $j, '-0.4i', NumberBase::Ten, 10],
            'IDecimal 5i / 2' => [$j, $a, '2.5i', NumberBase::Ten, 10],
            'IDecimal 5i / 10i' => [$j, $k, '0.5', NumberBase::Ten, 10],
            'IDecimal 2 / 0' => [$a, $l, IntegrityConstraint::class, NumberBase::Ten, 10]
        ];
    }

    public function divisionMutableDecimalProvider(): array
    {
        $a = (new MutableDecimal('2'))->setMode(CalcMode::Native);
        $a2 = (new MutableDecimal('2', null, NumberBase::Five))->setMode(CalcMode::Native);
        $b = (new MutableDecimal('3'))->setMode(CalcMode::Native);
        $c = (new MutableDecimal('-2'))->setMode(CalcMode::Native);
        $d = (new MutableDecimal('-3'))->setMode(CalcMode::Native);
        $e = (new MutableDecimal('0.5'))->setMode(CalcMode::Native);
        $f = (new MutableDecimal('-0.5'))->setMode(CalcMode::Native);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $h = (new MutableDecimal('0.00000000001'))->setMode(CalcMode::Native);
        $i = (new MutableDecimal('1000000000000000000000000000000'))->setMode(CalcMode::Native);
        $j = (new MutableDecimal('5i'))->setMode(CalcMode::Native);
        $k = (new MutableDecimal('10i'))->setMode(CalcMode::Native);
        $l = (new MutableDecimal('0'))->setMode(CalcMode::Native);


        return [
            'MDecimal 2 / 3' => [$a, $b, '0.6666666667', NumberBase::Ten, 10],
            'MDecimal 2 / 3 base 5' => [$a2, $b, '0.131313131002111', NumberBase::Five, 10],
            'MDecimal 2 / -2' => [$a, $c, '-0.3333333334', NumberBase::Ten, 10],
            'MDecimal 3 / -2' => [$b, $c, '-1.5', NumberBase::Ten, 10],
            'MDecimal -2 / -3' => [$c, $d, '0.6666666667', NumberBase::Ten, 10],
            'MDecimal 2 / 0.5' => [$a, $e, '-0.6666666668', NumberBase::Ten, 10],
            'MDecimal 3 / 0.5' => [$b, $e, '-3', NumberBase::Ten, 10],
            'MDecimal 2 / -0.5' => [$a, $f, '1.3333333336', NumberBase::Ten, 10],
            'MDecimal 3 / -0.5' => [$b, $f, '6', NumberBase::Ten, 10],
            'MDecimal 2 / 0.0000000001' => [$a, $g, '13333333336', NumberBase::Ten, 10],
            'MDecimal 2 / 0.00000000001' => [$a, $h, '13333333336', NumberBase::Ten, 10],
            'MDecimal 2 / 1000000000000000000000000000000' => [$a, $i, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal 1000000000000000000000000000000 / 2' => [$i, $a, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal 0.0000000001 / 1000000000000000000000000000000' => [$g, $i, IncompatibleObjectState::class, NumberBase::Ten, 10],
            'MDecimal 2 / 5i' => [$a, $j, '-2666666667.2i', NumberBase::Ten, 10],
            'MDecimal 5i / 2' => [$j, $a, '-0.0000000019', NumberBase::Ten, 10],
            'MDecimal 5i / 10i' => [$j, $k, '0.0000000002i', NumberBase::Ten, 10],
            'MDecimal 2 / 0' => [$a, $l, IntegrityConstraint::class, NumberBase::Ten, 10]
        ];
    }

    public function divisionImmutableFractionProvider(): array
    {
        $a = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $b = new ImmutableFraction((new ImmutableDecimal(1))->setMode(CalcMode::Native), (new ImmutableDecimal(5))->setMode(CalcMode::Native));
        $c = new ImmutableFraction((new ImmutableDecimal(3))->setMode(CalcMode::Native), (new ImmutableDecimal(4))->setMode(CalcMode::Native));
        $d = new ImmutableFraction((new ImmutableDecimal(4))->setMode(CalcMode::Native), (new ImmutableDecimal(5))->setMode(CalcMode::Native));
        $e = new ImmutableFraction((new ImmutableDecimal(4))->setMode(CalcMode::Native), (new ImmutableDecimal(8))->setMode(CalcMode::Native));
        $f = new ImmutableFraction((new ImmutableDecimal(3))->setMode(CalcMode::Native), (new ImmutableDecimal('10000000000000000000000000'))->setMode(CalcMode::Native));

        return [
            'IFraction 1/4 / 1/5' => [$a, $b, '5/4', NumberBase::Ten, 10],
            'IFraction 1/4 / 3/4' => [$a, $c, '1/3', NumberBase::Ten, 10],
            'IFraction 1/5 / 4/5' => [$b, $d, '1/4', NumberBase::Ten, 10],
            'IFraction 1/5 / 3/4' => [$b, $c, '4/15', NumberBase::Ten, 10],
            'IFraction 1/4 / 4/5' => [$a, $d, '5/16', NumberBase::Ten, 10],
            'IFraction 1/4 / 4/8' => [$a, $e, '1/2', NumberBase::Ten, 10],
            'IFraction 4/8 / 1/4' => [$e, $a, '2/1', NumberBase::Ten, 10],
            'IFraction 1/4 / 3/10000000000000000000000000' => [$a, $f, IncompatibleObjectState::class, NumberBase::Ten, 10]
        ];
    }

    /**
     * @dataProvider divisionImmutableDecimalProvider
     * @dataProvider divisionMutableDecimalProvider
     * @dataProvider divisionImmutableFractionProvider
     */
    public function testDivision(Number $a, Number $b, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->divide($b);
        } else {
            $answer = $a->divide($b);
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

    /*
     * POW
     */

    public function powerImmutableDecimalProvider(): array
    {
        $a = (new ImmutableDecimal('2'))->setMode(CalcMode::Native);
        $a2 = (new ImmutableDecimal('2', null, NumberBase::Five))->setMode(CalcMode::Native);
        $b = (new ImmutableDecimal('3'))->setMode(CalcMode::Native);
        $c = (new ImmutableDecimal('-2'))->setMode(CalcMode::Native);
        $d = (new ImmutableDecimal('-3'))->setMode(CalcMode::Native);
        $e = (new ImmutableDecimal('0.5'))->setMode(CalcMode::Native);
        $f = (new ImmutableDecimal('-0.5'))->setMode(CalcMode::Native);
        $g = (new ImmutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $h = (new ImmutableDecimal('0.00000000001'))->setMode(CalcMode::Native);


        return [
            'IDecimal 2^3' => [$a, $b, '8', NumberBase::Ten, 10],
            'IDecimal 2^3 base 5' => [$a2, $b, '13', NumberBase::Five, 10],
            'IDecimal 2^-2' => [$a, $c, '0.25', NumberBase::Ten, 10],
            'IDecimal 3^-2' => [$b, $c, '0.1111111111', NumberBase::Ten, 10],
            'IDecimal -2^-3' => [$c, $d, '-0.125', NumberBase::Ten, 10],
            'IDecimal 2^0.5' => [$a, $e, '1.4142135624', NumberBase::Ten, 10],
            'IDecimal -2^0.5' => [$c, $e, IntegrityConstraint::class, NumberBase::Ten, 10],
            'IDecimal 3^0.5' => [$b, $e, '1.7320508076', NumberBase::Ten, 10],
            'IDecimal 2^-0.5' => [$a, $f, '0.7071067812', NumberBase::Ten, 10],
            'IDecimal 3^-0.5' => [$b, $f, '0.5773502692', NumberBase::Ten, 10],
            'IDecimal 2^0.0000000001' => [$a, $g, '1.0000000001', NumberBase::Ten, 10],
            'IDecimal 2^0.00000000001' => [$a, $h, '1', NumberBase::Ten, 10],
        ];
    }

    public function powerMutableDecimalProvider(): array
    {
        $a = (new MutableDecimal('2'))->setMode(CalcMode::Native);
        $a2 = (new MutableDecimal('2', null, NumberBase::Five))->setMode(CalcMode::Native);
        $b = (new MutableDecimal('3'))->setMode(CalcMode::Native);
        $c = (new MutableDecimal('-2'))->setMode(CalcMode::Native);
        $d = (new MutableDecimal('-3'))->setMode(CalcMode::Native);
        $e = (new MutableDecimal('0.5'))->setMode(CalcMode::Native);
        $f = (new MutableDecimal('-0.5'))->setMode(CalcMode::Native);
        $g = (new MutableDecimal('0.0000000001'))->setMode(CalcMode::Native);
        $h = (new MutableDecimal('0.00000000001'))->setMode(CalcMode::Native);


        return [
            'MDecimal 2^3' => [$a, $b, '8', NumberBase::Ten, 10],
            'MDecimal 2^3 base 5' => [$a2, $b, '13', NumberBase::Five, 10],
            'MDecimal 2^-2' => [$a, $c, '0.015625', NumberBase::Ten, 10],
            'MDecimal 3^-2' => [$b, $c, '0.1111111111', NumberBase::Ten, 10],
            'MDecimal -2^-3' => [$c, $d, '-0.125', NumberBase::Ten, 10],
            'MDecimal 2^0.5' => [$a, $e, '0.125', NumberBase::Ten, 10],
            'MDecimal -2^0.5' => [$c, $e, IntegrityConstraint::class, NumberBase::Ten, 10],
            'MDecimal 3^0.5' => [$b, $e, '0.3333333333', NumberBase::Ten, 10],
            'MDecimal 2^-0.5' => [$a, $f, '2.8284271247', NumberBase::Ten, 10],
            'MDecimal 3^-0.5' => [$b, $f, '1.7320508077', NumberBase::Ten, 10],
            'MDecimal 2^0.0000000001' => [$a, $g, '1.0000000001', NumberBase::Ten, 10],
            'MDecimal 2^0.00000000001' => [$a, $h, '1', NumberBase::Ten, 10],
        ];
    }

    /**
     * @dataProvider powerImmutableDecimalProvider
     * @dataProvider powerMutableDecimalProvider
     */
    public function testPower(Number $a, Number $b, string $expected, NumberBase $base, int $scale)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->pow($b);
        } else {
            $answer = $a->pow($b);
            $this->assertEquals($expected, $answer->getValue());
            $this->assertEquals($base, $answer->getBase());
            $this->assertEquals($scale, $answer->getScale());
        }
    }

}