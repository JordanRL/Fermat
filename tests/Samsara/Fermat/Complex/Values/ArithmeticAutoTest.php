<?php

namespace Samsara\Fermat\Complex\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

class ArithmeticAutoTest extends TestCase
{

    /*
     * add()
     */

    public function additionImmutableComplexProvider(): array
    {
        $five = new ImmutableDecimal('5');
        $fiveI = new ImmutableDecimal('5i');
        $negFive = new ImmutableDecimal('-5');
        $negFiveI = new ImmutableDecimal('-5i');
        $zero = new ImmutableDecimal('0');
        $zeroI = new ImmutableDecimal('0i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenPowThirty = new ImmutableDecimal('1000000000000000000000000000000');
        $tenPowThirtyI = new ImmutableDecimal('1000000000000000000000000000000i');
        $tenScale = new ImmutableDecimal('0.0000000001');
        $tenScaleI = new ImmutableDecimal('0.0000000001i');
        $twelveScale = new ImmutableDecimal('0.000000000001');
        $twelveScaleI = new ImmutableDecimal('0.000000000001i');

        $a = new ImmutableComplexNumber($five, $fiveI);
        $b = new ImmutableComplexNumber($negFive, $negFiveI);
        $c = new ImmutableComplexNumber($five, $negFiveI);
        $d = new ImmutableComplexNumber($negFive, $fiveI);
        $e = new ImmutableComplexNumber($one, $tenPowThirtyI);
        $f = new ImmutableComplexNumber($tenPowThirty, $oneI);
        $g = new ImmutableComplexNumber($one, $tenScaleI);
        $h = new ImmutableComplexNumber($tenScale, $oneI);
        $i = new ImmutableComplexNumber($one, $twelveScaleI);
        $j = new ImmutableComplexNumber($twelveScale, $oneI);
        $k = new ImmutableComplexNumber($one, $zeroI);
        $l = new ImmutableComplexNumber($zero, $oneI);

        return [
            'IComplex (5+5i)+(5+5i)' => [$a, $a, '10+10i', ImmutableComplexNumber::class],
            'IComplex (5+5i)+(-5-5i)' => [$a, $b, '0', ImmutableDecimal::class],
            'IComplex (5+5i)+(5-5i)' => [$a, $c, '10', ImmutableDecimal::class],
            'IComplex (5+5i)+(-5+5i)' => [$a, $d, '10i', ImmutableDecimal::class],
            'IComplex (5+5i)+(5i)' => [$a, $fiveI, '5+10i', ImmutableComplexNumber::class],
            'IComplex (5i)+(5+5i)' => [$fiveI, $a, '5+10i', ImmutableComplexNumber::class],
            'IComplex (1+1000000000000000000000000000000i)+(5+5i)' => [$e, $a, '6+1000000000000000000000000000005i', ImmutableComplexNumber::class],
            'IComplex (1000000000000000000000000000000+1i)+(5+5i)' => [$f, $a, '1000000000000000000000000000005+6i', ImmutableComplexNumber::class],
            'IComplex (1+0.0000000001i)+(5+5i)' => [$g, $a, '6+5.0000000001i', ImmutableComplexNumber::class],
            'IComplex (0.0000000001+1i)+(5+5i)' => [$h, $a, '5.0000000001+6i', ImmutableComplexNumber::class],
            'IComplex (1+0.000000000001i)+(5+5i)' => [$i, $a, '6+5.000000000001i', ImmutableComplexNumber::class],
            'IComplex (0.000000000001+1i)+(5+5i)' => [$j, $a, '5.000000000001+6i', ImmutableComplexNumber::class],
            'IComplex (1+0i)+(5+5i)' => [$k, $a, '6+5i', ImmutableComplexNumber::class],
            'IComplex (0+1i)+(5+5i)' => [$l, $a, '5+6i', ImmutableComplexNumber::class],
        ];
    }

    public function additionMutableComplexProvider(): array
    {
        $five = new ImmutableDecimal('5');
        $fiveI = new ImmutableDecimal('5i');
        $negFive = new ImmutableDecimal('-5');
        $negFiveI = new ImmutableDecimal('-5i');
        $zero = new ImmutableDecimal('0');
        $zeroI = new ImmutableDecimal('0i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenPowThirty = new ImmutableDecimal('1000000000000000000000000000000');
        $tenPowThirtyI = new ImmutableDecimal('1000000000000000000000000000000i');
        $tenScale = new ImmutableDecimal('0.0000000001');
        $tenScaleI = new ImmutableDecimal('0.0000000001i');
        $twelveScale = new ImmutableDecimal('0.000000000001');
        $twelveScaleI = new ImmutableDecimal('0.000000000001i');

        $a = new MutableComplexNumber($five, $fiveI);
        $b = new MutableComplexNumber($negFive, $negFiveI);
        $c = new MutableComplexNumber($five, $negFiveI);
        $d = new MutableComplexNumber($negFive, $fiveI);
        $e = new MutableComplexNumber($one, $tenPowThirtyI);
        $f = new MutableComplexNumber($tenPowThirty, $oneI);
        $g = new MutableComplexNumber($one, $tenScaleI);
        $h = new MutableComplexNumber($tenScale, $oneI);
        $i = new MutableComplexNumber($one, $twelveScaleI);
        $j = new MutableComplexNumber($twelveScale, $oneI);
        $k = new MutableComplexNumber($one, $zeroI);
        $l = new MutableComplexNumber($zero, $oneI);

        return [
            'MComplex (5+5i)+(5+5i)' => [$a, $a, '10+10i', MutableComplexNumber::class],
            'MComplex (5+5i)+(-5-5i)' => [$a, $b, '5+5i', MutableComplexNumber::class],
            'MComplex (5+5i)+(5-5i)' => [$a, $c, '10', ImmutableDecimal::class],
            'MComplex (5+5i)+(-5+5i)' => [$a, $d, '10i', ImmutableDecimal::class],
            'MComplex (5+5i)+(5i)' => [$a, $fiveI, '5+10i', MutableComplexNumber::class],
            'MComplex (5i)+(5+5i)' => [$fiveI, $a, '5+15i', ImmutableComplexNumber::class],
            'MComplex (1+1000000000000000000000000000000i)+(5+5i)' => [$e, $a, '6+1000000000000000000000000000010i', MutableComplexNumber::class],
            'MComplex (1000000000000000000000000000000+1i)+(5+5i)' => [$f, $a, '1000000000000000000000000000005+11i', MutableComplexNumber::class],
            'MComplex (1+0.0000000001i)+(5+5i)' => [$g, $a, '6+10.0000000001i', MutableComplexNumber::class],
            'MComplex (0.0000000001+1i)+(5+5i)' => [$h, $a, '5.0000000001+11i', MutableComplexNumber::class],
            'MComplex (1+0.000000000001i)+(5+5i)' => [$i, $a, '6+10.000000000001i', MutableComplexNumber::class],
            'MComplex (0.000000000001+1i)+(5+5i)' => [$j, $a, '5.000000000001+11i', MutableComplexNumber::class],
            'MComplex (1+0i)+(5+5i)' => [$k, $a, '6+10i', MutableComplexNumber::class],
            'MComplex (0+1i)+(5+5i)' => [$l, $a, '5+11i', MutableComplexNumber::class],
        ];
    }

    /**
     * @dataProvider additionImmutableComplexProvider
     * @dataProvider additionMutableComplexProvider
     */
    public function testAdd(ComplexNumber|Decimal $a, ComplexNumber|Decimal $b, string $expected, ?string $resultClass = null)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->add($b);
        } else {
            $answer = $a->add($b);
            $this->assertEquals($expected, $answer->getValue());
            if (!is_null($resultClass)) {
                $this->assertEquals($resultClass, get_class($answer));
            }
        }
    }

    /*
     * subtract()
     */

    public function subtractionImmutableComplexProvider(): array
    {
        $five = new ImmutableDecimal('5');
        $fiveI = new ImmutableDecimal('5i');
        $negFive = new ImmutableDecimal('-5');
        $negFiveI = new ImmutableDecimal('-5i');
        $zero = new ImmutableDecimal('0');
        $zeroI = new ImmutableDecimal('0i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenPowThirty = new ImmutableDecimal('1000000000000000000000000000000');
        $tenPowThirtyI = new ImmutableDecimal('1000000000000000000000000000000i');
        $tenScale = new ImmutableDecimal('0.0000000001');
        $tenScaleI = new ImmutableDecimal('0.0000000001i');
        $twelveScale = new ImmutableDecimal('0.000000000001');
        $twelveScaleI = new ImmutableDecimal('0.000000000001i');

        $a = new ImmutableComplexNumber($five, $fiveI);
        $b = new ImmutableComplexNumber($negFive, $negFiveI);
        $c = new ImmutableComplexNumber($five, $negFiveI);
        $d = new ImmutableComplexNumber($negFive, $fiveI);
        $e = new ImmutableComplexNumber($one, $tenPowThirtyI);
        $f = new ImmutableComplexNumber($tenPowThirty, $oneI);
        $g = new ImmutableComplexNumber($one, $tenScaleI);
        $h = new ImmutableComplexNumber($tenScale, $oneI);
        $i = new ImmutableComplexNumber($one, $twelveScaleI);
        $j = new ImmutableComplexNumber($twelveScale, $oneI);
        $k = new ImmutableComplexNumber($one, $zeroI);
        $l = new ImmutableComplexNumber($zero, $oneI);

        return [
            'IComplex (5+5i)-(5+5i)' => [$a, $a, '0', ImmutableDecimal::class],
            'IComplex (5+5i)-(-5-5i)' => [$a, $b, '10+10i', ImmutableComplexNumber::class],
            'IComplex (5+5i)-(5-5i)' => [$a, $c, '10i', ImmutableDecimal::class],
            'IComplex (5+5i)-(-5+5i)' => [$a, $d, '10', ImmutableDecimal::class],
            'IComplex (5+5i)-(5i)' => [$a, $fiveI, '5', ImmutableDecimal::class],
            'IComplex (5i)-(5+5i)' => [$fiveI, $a, '-5', ImmutableDecimal::class],
            'IComplex (1+1000000000000000000000000000000i)-(5+5i)' => [$e, $a, '-4+999999999999999999999999999995i', ImmutableComplexNumber::class],
            'IComplex (1000000000000000000000000000000+1i)-(5+5i)' => [$f, $a, '999999999999999999999999999995-4i', ImmutableComplexNumber::class],
            'IComplex (1+0.0000000001i)-(5+5i)' => [$g, $a, '-4-4.9999999999i', ImmutableComplexNumber::class],
            'IComplex (0.0000000001+1i)-(5+5i)' => [$h, $a, '-4.9999999999-4i', ImmutableComplexNumber::class],
            'IComplex (1+0.000000000001i)-(5+5i)' => [$i, $a, '-4-4.999999999999i', ImmutableComplexNumber::class],
            'IComplex (0.000000000001+1i)-(5+5i)' => [$j, $a, '-4.999999999999-4i', ImmutableComplexNumber::class],
            'IComplex (1+0i)-(5+5i)' => [$k, $a, '-4-5i', ImmutableComplexNumber::class],
            'IComplex (0+1i)-(5+5i)' => [$l, $a, '-5-4i', ImmutableComplexNumber::class],
        ];
    }

    public function subtractionMutableComplexProvider(): array
    {
        $five = new ImmutableDecimal('5');
        $fiveI = new ImmutableDecimal('5i');
        $negFive = new ImmutableDecimal('-5');
        $negFiveI = new ImmutableDecimal('-5i');
        $zero = new ImmutableDecimal('0');
        $zeroI = new ImmutableDecimal('0i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenPowThirty = new ImmutableDecimal('1000000000000000000000000000000');
        $tenPowThirtyI = new ImmutableDecimal('1000000000000000000000000000000i');
        $tenScale = new ImmutableDecimal('0.0000000001');
        $tenScaleI = new ImmutableDecimal('0.0000000001i');
        $twelveScale = new ImmutableDecimal('0.000000000001');
        $twelveScaleI = new ImmutableDecimal('0.000000000001i');

        $a = new MutableComplexNumber($five, $fiveI);
        $b = new MutableComplexNumber($negFive, $negFiveI);
        $c = new MutableComplexNumber($five, $negFiveI);
        $d = new MutableComplexNumber($negFive, $fiveI);
        $e = new MutableComplexNumber($one, $tenPowThirtyI);
        $f = new MutableComplexNumber($tenPowThirty, $oneI);
        $g = new MutableComplexNumber($one, $tenScaleI);
        $h = new MutableComplexNumber($tenScale, $oneI);
        $i = new MutableComplexNumber($one, $twelveScaleI);
        $j = new MutableComplexNumber($twelveScale, $oneI);
        $k = new MutableComplexNumber($one, $zeroI);
        $l = new MutableComplexNumber($zero, $oneI);

        return [
            'MComplex (5+5i)-(5+5i)' => [$a, $a, '0', ImmutableDecimal::class],
            'MComplex (5+5i)-(-5-5i)' => [$a, $b, '10+10i', MutableComplexNumber::class],
            'MComplex (5+5i)-(5-5i)' => [$a, $c, '5+15i', MutableComplexNumber::class],
            'MComplex (5+5i)-(-5+5i)' => [$a, $d, '10+10i', MutableComplexNumber::class],
            'MComplex (5+5i)-(5i)' => [$a, $fiveI, '10+5i', MutableComplexNumber::class],
            'MComplex (5i)-(5+5i)' => [$fiveI, $a, '-10', ImmutableDecimal::class],
            'MComplex (1+1000000000000000000000000000000i)-(5+5i)' => [$e, $a, '-9+999999999999999999999999999995i', MutableComplexNumber::class],
            'MComplex (1000000000000000000000000000000+1i)-(5+5i)' => [$f, $a, '999999999999999999999999999990-4i', MutableComplexNumber::class],
            'MComplex (1+0.0000000001i)-(5+5i)' => [$g, $a, '-9-4.9999999999i', MutableComplexNumber::class],
            'MComplex (0.0000000001+1i)-(5+5i)' => [$h, $a, '-9.9999999999-4i', MutableComplexNumber::class],
            'MComplex (1+0.000000000001i)-(5+5i)' => [$i, $a, '-9-4.999999999999i', MutableComplexNumber::class],
            'MComplex (0.000000000001+1i)-(5+5i)' => [$j, $a, '-9.999999999999-4i', MutableComplexNumber::class],
            'MComplex (1+0i)-(5+5i)' => [$k, $a, '-9-5i', MutableComplexNumber::class],
            'MComplex (0+1i)-(5+5i)' => [$l, $a, '-10-4i', MutableComplexNumber::class],
        ];
    }

    /**
     * @dataProvider subtractionImmutableComplexProvider
     * @dataProvider subtractionMutableComplexProvider
     */
    public function testSubtract(ComplexNumber|Decimal $a, ComplexNumber|Decimal $b, string $expected, ?string $resultClass = null)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->subtract($b);
        } else {
            $answer = $a->subtract($b);
            $this->assertEquals($expected, $answer->getValue());
            if (!is_null($resultClass)) {
                $this->assertEquals($resultClass, get_class($answer));
            }
        }
    }

    /*
     * multiply()
     */

    public function multiplicationImmutableComplexProvider(): array
    {
        $five = new ImmutableDecimal('5');
        $fiveI = new ImmutableDecimal('5i');
        $negFive = new ImmutableDecimal('-5');
        $negFiveI = new ImmutableDecimal('-5i');
        $zero = new ImmutableDecimal('0');
        $zeroI = new ImmutableDecimal('0i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenPowThirty = new ImmutableDecimal('1000000000000000000000000000000');
        $tenPowThirtyI = new ImmutableDecimal('1000000000000000000000000000000i');
        $tenScale = new ImmutableDecimal('0.0000000001');
        $tenScaleI = new ImmutableDecimal('0.0000000001i');
        $twelveScale = new ImmutableDecimal('0.000000000001');
        $twelveScaleI = new ImmutableDecimal('0.000000000001i');

        $a = new ImmutableComplexNumber($five, $fiveI);
        $b = new ImmutableComplexNumber($negFive, $negFiveI);
        $c = new ImmutableComplexNumber($five, $negFiveI);
        $d = new ImmutableComplexNumber($negFive, $fiveI);
        $e = new ImmutableComplexNumber($one, $tenPowThirtyI);
        $f = new ImmutableComplexNumber($tenPowThirty, $oneI);
        $g = new ImmutableComplexNumber($one, $tenScaleI);
        $h = new ImmutableComplexNumber($tenScale, $oneI);
        $i = new ImmutableComplexNumber($one, $twelveScaleI);
        $j = new ImmutableComplexNumber($twelveScale, $oneI);
        $k = new ImmutableComplexNumber($one, $zeroI);
        $l = new ImmutableComplexNumber($zero, $oneI);

        return [
            'IComplex (5+5i)*(5+5i)' => [$a, $a, '50i', ImmutableDecimal::class],
            'IComplex (5+5i)*(-5-5i)' => [$a, $b, '-50i', ImmutableDecimal::class],
            'IComplex (5+5i)*(5-5i)' => [$a, $c, '50', ImmutableDecimal::class],
            'IComplex (5+5i)*(-5+5i)' => [$a, $d, '-50', ImmutableDecimal::class],
            'IComplex (5+5i)*(0)' => [$a, $zero, '0', ImmutableDecimal::class],
            'IComplex (5+5i)*(5i)' => [$a, $fiveI, '-25+25i', ImmutableComplexNumber::class],
            'IComplex (5i)*(5+5i)' => [$fiveI, $a, '-25+25i', ImmutableComplexNumber::class],
            'IComplex (1+1000000000000000000000000000000i)*(5+5i)' => [$e, $a, '-4999999999999999999999999999995+5000000000000000000000000000005i', ImmutableComplexNumber::class],
            'IComplex (1000000000000000000000000000000+1i)*(5+5i)' => [$f, $a, '4999999999999999999999999999995+5000000000000000000000000000005i', ImmutableComplexNumber::class],
            'IComplex (1+0.0000000001i)*(5+5i)' => [$g, $a, '4.9999999995+5.0000000005i', ImmutableComplexNumber::class],
            'IComplex (0.0000000001+1i)*(5+5i)' => [$h, $a, '-4.9999999995+5.0000000005i', ImmutableComplexNumber::class],
            'IComplex (1+0.000000000001i)*(5+5i)' => [$i, $a, '5+5i', ImmutableComplexNumber::class],
            'IComplex (0.000000000001+1i)*(5+5i)' => [$j, $a, '-5+5i', ImmutableComplexNumber::class],
            'IComplex (1+0i)*(5+5i)' => [$k, $a, '5+5i', ImmutableComplexNumber::class],
            'IComplex (0+1i)*(5+5i)' => [$l, $a, '-5+5i', ImmutableComplexNumber::class],
        ];
    }

    public function multiplicationMutableComplexProvider(): array
    {
        $five = new ImmutableDecimal('5');
        $fiveI = new ImmutableDecimal('5i');
        $negFive = new ImmutableDecimal('-5');
        $negFiveI = new ImmutableDecimal('-5i');
        $zero = new ImmutableDecimal('0');
        $zeroI = new ImmutableDecimal('0i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenPowThirty = new ImmutableDecimal('1000000000000000000000000000000');
        $tenPowThirtyI = new ImmutableDecimal('1000000000000000000000000000000i');
        $tenScale = new ImmutableDecimal('0.0000000001');
        $tenScaleI = new ImmutableDecimal('0.0000000001i');
        $twelveScale = new ImmutableDecimal('0.000000000001');
        $twelveScaleI = new ImmutableDecimal('0.000000000001i');

        $a = new MutableComplexNumber($five, $fiveI);
        $b = new MutableComplexNumber($negFive, $negFiveI);
        $c = new MutableComplexNumber($five, $negFiveI);
        $d = new MutableComplexNumber($negFive, $fiveI);
        $e = new MutableComplexNumber($one, $tenPowThirtyI);
        $f = new MutableComplexNumber($tenPowThirty, $oneI);
        $g = new MutableComplexNumber($one, $tenScaleI);
        $h = new MutableComplexNumber($tenScale, $oneI);
        $i = new MutableComplexNumber($one, $twelveScaleI);
        $j = new MutableComplexNumber($twelveScale, $oneI);
        $k = new MutableComplexNumber($one, $zeroI);
        $l = new MutableComplexNumber($zero, $oneI);

        return [
            'MComplex (5+5i)*(5+5i)' => [$a, $a, '50i', ImmutableDecimal::class],
            'MComplex (5+5i)*(-5-5i)' => [$a, $b, '-50i', ImmutableDecimal::class],
            'MComplex (5+5i)*(5-5i)' => [$a, $c, '50', ImmutableDecimal::class],
            'MComplex (5+5i)*(-5+5i)' => [$a, $d, '-50', ImmutableDecimal::class],
            'MComplex (5+5i)*(0)' => [$a, $zero, '0', ImmutableDecimal::class],
            'MComplex (5+5i)*(5i)' => [$a, $fiveI, '-25+25i', MutableComplexNumber::class],
            'MComplex (5i)*(5+5i)' => [$fiveI, $a, '-125-125i', ImmutableComplexNumber::class],
            'MComplex (1+1000000000000000000000000000000i)*(5+5i)' => [$e, $a, '-25000000000000000000000000000025-24999999999999999999999999999975i', MutableComplexNumber::class],
            'MComplex (1000000000000000000000000000000+1i)*(5+5i)' => [$f, $a, '-25000000000000000000000000000025+24999999999999999999999999999975i', MutableComplexNumber::class],
            'MComplex (1+0.0000000001i)*(5+5i)' => [$g, $a, '-25.0000000025+24.9999999975i', MutableComplexNumber::class],
            'MComplex (0.0000000001+1i)*(5+5i)' => [$h, $a, '-25.0000000025-24.9999999975i', MutableComplexNumber::class],
            'MComplex (1+0.000000000001i)*(5+5i)' => [$i, $a, '-25+25i', MutableComplexNumber::class],
            'MComplex (0.000000000001+1i)*(5+5i)' => [$j, $a, '-25-25i', MutableComplexNumber::class],
            'MComplex (1+0i)*(5+5i)' => [$k, $a, '-25+25i', MutableComplexNumber::class],
            'MComplex (0+1i)*(5+5i)' => [$l, $a, '-25-25i', MutableComplexNumber::class],
        ];
    }

    /**
     * @medium
     * @dataProvider multiplicationImmutableComplexProvider
     * @dataProvider multiplicationMutableComplexProvider
     */
    public function testMultiply(ComplexNumber|Decimal $a, ComplexNumber|Decimal $b, string $expected, ?string $resultClass = null)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->multiply($b);
        } else {
            $answer = $a->multiply($b);
            $this->assertEquals($expected, $answer->getValue());
            if (!is_null($resultClass)) {
                $this->assertEquals($resultClass, get_class($answer));
            }
        }
    }

    /*
     * divide()
     */

    public function divisionImmutableComplexProvider(): array
    {
        $five = new ImmutableDecimal('5');
        $fiveI = new ImmutableDecimal('5i');
        $negFive = new ImmutableDecimal('-5');
        $negFiveI = new ImmutableDecimal('-5i');
        $zero = new ImmutableDecimal('0');
        $zeroI = new ImmutableDecimal('0i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenPowThirty = new ImmutableDecimal('1000000000000000000000000000000');
        $tenPowThirtyI = new ImmutableDecimal('1000000000000000000000000000000i');
        $tenScale = new ImmutableDecimal('0.0000000001');
        $tenScaleI = new ImmutableDecimal('0.0000000001i');
        $twelveScale = new ImmutableDecimal('0.000000000001');
        $twelveScaleI = new ImmutableDecimal('0.000000000001i');

        $a = new ImmutableComplexNumber($five, $fiveI);
        $b = new ImmutableComplexNumber($negFive, $negFiveI);
        $c = new ImmutableComplexNumber($five, $negFiveI);
        $d = new ImmutableComplexNumber($negFive, $fiveI);
        $e = new ImmutableComplexNumber($one, $tenPowThirtyI);
        $f = new ImmutableComplexNumber($tenPowThirty, $oneI);
        $g = new ImmutableComplexNumber($one, $tenScaleI);
        $h = new ImmutableComplexNumber($tenScale, $oneI);
        $i = new ImmutableComplexNumber($one, $twelveScaleI);
        $j = new ImmutableComplexNumber($twelveScale, $oneI);
        $k = new ImmutableComplexNumber($one, $zeroI);
        $l = new ImmutableComplexNumber($zero, $oneI);

        return [
            'IComplex (5+5i)/(5+5i)' => [$a, $a, '1', ImmutableDecimal::class],
            'IComplex (5+5i)/(-5-5i)' => [$a, $b, '-1', ImmutableDecimal::class],
            'IComplex (5+5i)/(5-5i)' => [$a, $c, '1i', ImmutableDecimal::class],
            'IComplex (5+5i)/(-5+5i)' => [$a, $d, '-1i', ImmutableDecimal::class],
            'IComplex (5+5i)/(0)' => [$a, $zero, IntegrityConstraint::class, null],
            'IComplex (5+5i)/(5i)' => [$a, $fiveI, '1-1i', ImmutableComplexNumber::class],
            'IComplex (5i)/(5+5i)' => [$fiveI, $a, '0.5+0.5i', ImmutableComplexNumber::class],
            'IComplex (1+1000000000000000000000000000000i)/(5+5i)' => [$e, $a, '100000000000000000000000000000.1+99999999999999999999999999999.9i', ImmutableComplexNumber::class],
            'IComplex (1000000000000000000000000000000+1i)/(5+5i)' => [$f, $a, '100000000000000000000000000000.1-99999999999999999999999999999.9i', ImmutableComplexNumber::class],
            'IComplex (1+0.0000000001i)/(5+5i)' => [$g, $a, '0.1-0.1i', ImmutableComplexNumber::class],
            'IComplex (0.0000000001+1i)/(5+5i)' => [$h, $a, '0.1+0.1i', ImmutableComplexNumber::class],
            'IComplex (1+0.000000000001i)/(5+5i)' => [$i, $a, '0.1-0.1i', ImmutableComplexNumber::class],
            'IComplex (0.000000000001+1i)/(5+5i)' => [$j, $a, '0.1+0.1i', ImmutableComplexNumber::class],
            'IComplex (1+0i)/(5+5i)' => [$k, $a, '0.1-0.1i', ImmutableComplexNumber::class],
            'IComplex (0+1i)/(5+5i)' => [$l, $a, '0.1+0.1i', ImmutableComplexNumber::class],
        ];
    }

    public function divisionMutableComplexProvider(): array
    {
        $five = new ImmutableDecimal('5');
        $fiveI = new ImmutableDecimal('5i');
        $negFive = new ImmutableDecimal('-5');
        $negFiveI = new ImmutableDecimal('-5i');
        $zero = new ImmutableDecimal('0');
        $zeroI = new ImmutableDecimal('0i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenPowThirty = new ImmutableDecimal('1000000000000000000000000000000');
        $tenPowThirtyI = new ImmutableDecimal('1000000000000000000000000000000i');
        $tenScale = new ImmutableDecimal('0.0000000001');
        $tenScaleI = new ImmutableDecimal('0.0000000001i');
        $twelveScale = new ImmutableDecimal('0.000000000001');
        $twelveScaleI = new ImmutableDecimal('0.000000000001i');

        $a = new MutableComplexNumber($five, $fiveI);
        $b = new MutableComplexNumber($negFive, $negFiveI);
        $c = new MutableComplexNumber($five, $negFiveI);
        $d = new MutableComplexNumber($negFive, $fiveI);
        $e = new MutableComplexNumber($one, $tenPowThirtyI);
        $f = new MutableComplexNumber($tenPowThirty, $oneI);
        $g = new MutableComplexNumber($one, $tenScaleI);
        $h = new MutableComplexNumber($tenScale, $oneI);
        $i = new MutableComplexNumber($one, $twelveScaleI);
        $j = new MutableComplexNumber($twelveScale, $oneI);
        $k = new MutableComplexNumber($one, $zeroI);
        $l = new MutableComplexNumber($zero, $oneI);

        return [
            'MComplex (5+5i)/(5+5i)' => [$a, $a, '1', ImmutableDecimal::class],
            'MComplex (5+5i)/(-5-5i)' => [$a, $b, '-1', ImmutableDecimal::class],
            'MComplex (5+5i)/(5-5i)' => [$a, $c, '1i', ImmutableDecimal::class],
            'MComplex (5+5i)/(-5+5i)' => [$a, $d, '-1i', ImmutableDecimal::class],
            'MComplex (5+5i)/(0)' => [$a, $zero, IntegrityConstraint::class, null],
            'MComplex (5+5i)/(5i)' => [$a, $fiveI, '1-1i', MutableComplexNumber::class],
            'MComplex (5i)/(5+5i)' => [$fiveI, $a, '-2.5+2.5i', ImmutableComplexNumber::class],
            'MComplex (1+1000000000000000000000000000000i)/(5+5i)' => [$e, $a, '-499999999999999999999999999999.5+500000000000000000000000000000.5i', MutableComplexNumber::class],
            'MComplex (1000000000000000000000000000000+1i)/(5+5i)' => [$f, $a, '499999999999999999999999999999.5+500000000000000000000000000000.5i', MutableComplexNumber::class],
            'MComplex (1+0.0000000001i)/(5+5i)' => [$g, $a, '0.5+0.5i', MutableComplexNumber::class],
            'MComplex (0.0000000001+1i)/(5+5i)' => [$h, $a, '-0.5+0.5i', MutableComplexNumber::class],
            'MComplex (1+0.000000000001i)/(5+5i)' => [$i, $a, '0.5+0.5i', MutableComplexNumber::class],
            'MComplex (0.000000000001+1i)/(5+5i)' => [$j, $a, '-0.5+0.5i', MutableComplexNumber::class],
            'MComplex (1+0i)/(5+5i)' => [$k, $a, '0.5+0.5i', MutableComplexNumber::class],
            'MComplex (0+1i)/(5+5i)' => [$l, $a, '-0.5+0.5i', MutableComplexNumber::class],
        ];
    }

    /**
     * @medium
     * @dataProvider divisionImmutableComplexProvider
     * @dataProvider divisionMutableComplexProvider
     */
    public function testDivide(ComplexNumber|Decimal $a, ComplexNumber|Decimal $b, string $expected, ?string $resultClass = null)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->divide($b);
        } else {
            $answer = $a->divide($b);
            $this->assertEquals($expected, $answer->getValue());
            if (!is_null($resultClass)) {
                $this->assertEquals($resultClass, get_class($answer));
            }
        }
    }

    /*
     * pow()
     */

    public function powerImmutableComplexMediumProvider(): array
    {
        $three = new ImmutableDecimal('3');
        $threeI = new ImmutableDecimal('3i');
        $negThree = new ImmutableDecimal('-3');
        $negThreeI = new ImmutableDecimal('-3i');
        $zero = new ImmutableDecimal('0');
        $zeroI = new ImmutableDecimal('0i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenScale = new ImmutableDecimal('0.0000000001');
        $tenScaleI = new ImmutableDecimal('0.0000000001i');
        $twelveScale = new ImmutableDecimal('0.000000000001');
        $twelveScaleI = new ImmutableDecimal('0.000000000001i');

        $a = new ImmutableComplexNumber($three, $threeI);
        $b = new ImmutableComplexNumber($negThree, $negThreeI);
        $c = new ImmutableComplexNumber($three, $negThreeI);
        $d = new ImmutableComplexNumber($negThree, $threeI);
        $g = new ImmutableComplexNumber($one, $tenScaleI);
        $h = new ImmutableComplexNumber($tenScale, $oneI);
        $i = new ImmutableComplexNumber($one, $twelveScaleI);
        $j = new ImmutableComplexNumber($twelveScale, $oneI);
        $k = new ImmutableComplexNumber($one, $zeroI);
        $l = new ImmutableComplexNumber($zero, $oneI);

        return [
            'IComplex (3+3i)^(3+3i)' => [$a, $a, '6.6423696469+2.8756701322i', ImmutableComplexNumber::class],
            'IComplex (3+3i)^(-3-3i)' => [$a, $b, '0.1267856367-0.0548890965i', ImmutableComplexNumber::class],
            'IComplex (3+3i)^(3-3i)' => [$a, $c, '-320.1132107899-739.4138329992i', ImmutableComplexNumber::class],
            'IComplex (3+3i)^(-3+3i)' => [$a, $d, '-0.0004930847+0.0011389523i', ImmutableComplexNumber::class],
            'IComplex (3+3i)^(0)' => [$a, $zero, '1', ImmutableDecimal::class],
            'IComplex (3+3i)^(3i)' => [$a, $threeI, '-0.0348768474-0.088129998i', ImmutableComplexNumber::class],
            'IComplex (3i)^(3+3i)' => [$threeI, $a, '-0.0372635883+0.2396692999i', ImmutableComplexNumber::class],
            'IComplex (1+0.0000000001i)^(3+3i)' => [$g, $a, '0.9999999997+0.0000000003i', ImmutableComplexNumber::class],
            'IComplex (0.0000000001+1i)^(3+3i)' => [$h, $a, '-0.008983291i', ImmutableDecimal::class],
            'IComplex (1+0.000000000001i)^(3+3i)' => [$i, $a, '0.999999999997+0.000000000003i', ImmutableComplexNumber::class],
            'IComplex (0.000000000001+1i)^(3+3i)' => [$j, $a, '-0.008983291021i', ImmutableDecimal::class],
            'IComplex (1+0i)^(3+3i)' => [$k, $a, '1', ImmutableDecimal::class],
            'IComplex (0+1i)^(3+3i)' => [$l, $a, '-0.008983291i', ImmutableDecimal::class],
        ];
    }

    public function powerImmutableComplexLargeProvider(): array
    {
        $three = new ImmutableDecimal('3');
        $threeI = new ImmutableDecimal('3i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenPowThirty = new ImmutableDecimal('1000000000000000000000000000000');
        $tenPowThirtyI = new ImmutableDecimal('1000000000000000000000000000000i');

        $a = new ImmutableComplexNumber($three, $threeI);
        $e = new ImmutableComplexNumber($one, $tenPowThirtyI);
        $f = new ImmutableComplexNumber($tenPowThirty, $oneI);

        return [
            'IComplex (1+1000000000000000000000000000000i)^(3+3i)' => [$e, $a, '-1008103895072608669621656647656731987966636777577845657842294500190951444855708178470827.8046672167-8926547154809861533401090781765730860411184241176202875421054807180827544305044641700789.1254349717i', ImmutableComplexNumber::class],
            'IComplex (1000000000000000000000000000000+1i)^(3+3i)' => [$f, $a, '993683398858380499091676249928037050056205700638839289239875498467755979897070841985132647.8378656662-112219886086453915249045522210107306891034790097457935133859983576948619911473186801529969.5691040598i', ImmutableComplexNumber::class],
        ];
    }

    /**
     * @medium
     * @dataProvider powerImmutableComplexMediumProvider
     */
    public function testPow(ComplexNumber|Decimal $a, ComplexNumber|Decimal $b, string $expected, ?string $resultClass = null)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->pow($b);
        } else {
            $answer = $a->pow($b);
            $this->assertEquals($expected, $answer->getValue());
            if (!is_null($resultClass)) {
                $this->assertEquals($resultClass, get_class($answer));
            }
        }
    }

    /**
     * @large
     * @dataProvider powerImmutableComplexLargeProvider
     */
    public function testPowLarge(ComplexNumber|Decimal $a, ComplexNumber|Decimal $b, string $expected, ?string $resultClass = null)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->pow($b);
        } else {
            $answer = $a->pow($b);
            $this->assertEquals($expected, $answer->getValue());
            if (!is_null($resultClass)) {
                $this->assertEquals($resultClass, get_class($answer));
            }
        }
    }

    /*
     * sqrt()
     */

    public function squareRootImmutableComplexMediumProvider(): array
    {
        $three = new ImmutableDecimal('3');
        $threeI = new ImmutableDecimal('3i');
        $negThree = new ImmutableDecimal('-3');
        $negThreeI = new ImmutableDecimal('-3i');
        $zero = new ImmutableDecimal('0');
        $zeroI = new ImmutableDecimal('0i');
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenScale = new ImmutableDecimal('0.0000000001');
        $tenScaleI = new ImmutableDecimal('0.0000000001i');
        $twelveScale = new ImmutableDecimal('0.000000000001');
        $twelveScaleI = new ImmutableDecimal('0.000000000001i');

        $a = new ImmutableComplexNumber($three, $threeI);
        $b = new ImmutableComplexNumber($negThree, $negThreeI);
        $c = new ImmutableComplexNumber($three, $negThreeI);
        $d = new ImmutableComplexNumber($negThree, $threeI);
        $g = new ImmutableComplexNumber($one, $tenScaleI);
        $h = new ImmutableComplexNumber($tenScale, $oneI);
        $i = new ImmutableComplexNumber($one, $twelveScaleI);
        $j = new ImmutableComplexNumber($twelveScale, $oneI);
        $k = new ImmutableComplexNumber($one, $zeroI);
        $l = new ImmutableComplexNumber($zero, $oneI);

        return [
            'IComplex sqrt(3+3i)' => [$a, '1.902976706+0.7882387605i', ImmutableComplexNumber::class],
            'IComplex sqrt(-3-3i)' => [$b, '0.7882387605-1.902976706i', ImmutableComplexNumber::class],
            'IComplex sqrt(3-3i)' => [$c, '1.902976706-0.7882387605i', ImmutableComplexNumber::class],
            'IComplex sqrt(-3+3i)' => [$d, '0.7882387605+1.902976706i', ImmutableComplexNumber::class],
            'IComplex sqrt(1+0.0000000001i)' => [$g, '1', ImmutableDecimal::class],
            'IComplex sqrt(0.0000000001+1i)' => [$h, '0.7071067812+0.7071067812i', ImmutableComplexNumber::class],
            'IComplex sqrt(1+0.000000000001i)' => [$i, '1', ImmutableDecimal::class],
            'IComplex sqrt(0.000000000001+1i)' => [$j, '0.707106781187+0.707106781186i', ImmutableComplexNumber::class],
            'IComplex sqrt(1+0i)' => [$k, '1', ImmutableDecimal::class],
            'IComplex sqrt(0+1i)' => [$l, '0.7071067812+0.7071067812i', ImmutableComplexNumber::class],
        ];
    }

    public function squareRootImmutableComplexLargeProvider(): array
    {
        $one = new ImmutableDecimal('1');
        $oneI = new ImmutableDecimal('1i');
        $tenPowThirty = new ImmutableDecimal('1000000000000000000000000000000');
        $tenPowThirtyI = new ImmutableDecimal('1000000000000000000000000000000i');

        $e = new ImmutableComplexNumber($one, $tenPowThirtyI);
        $f = new ImmutableComplexNumber($tenPowThirty, $oneI);

        return [
            'IComplex sqrt(1+1000000000000000000000000000000i)' => [$e, '707106781186547.5244008444+707106781186547.5244008444i', ImmutableComplexNumber::class],
            'IComplex sqrt(1000000000000000000000000000000+1i)' => [$f, '1000000000000000', ImmutableDecimal::class],
        ];
    }

    /**
     * @medium
     * @dataProvider squareRootImmutableComplexMediumProvider
     */
    public function testSqrt(ComplexNumber|Decimal $a, string $expected, ?string $resultClass = null)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->sqrt();
        } else {
            $answer = $a->sqrt();
            $this->assertEquals($expected, $answer->getValue());
            if (!is_null($resultClass)) {
                $this->assertEquals($resultClass, get_class($answer));
            }
        }
    }

    /**
     * @large
     * @dataProvider squareRootImmutableComplexLargeProvider
     */
    public function testSqrtLarge(ComplexNumber|Decimal $a, string $expected, ?string $resultClass = null)
    {
        if (str_contains($expected, 'Exception')) {
            $this->expectException($expected);
            $a->sqrt();
        } else {
            $answer = $a->sqrt();
            $this->assertEquals($expected, $answer->getValue());
            if (!is_null($resultClass)) {
                $this->assertEquals($resultClass, get_class($answer));
            }
        }
    }

}