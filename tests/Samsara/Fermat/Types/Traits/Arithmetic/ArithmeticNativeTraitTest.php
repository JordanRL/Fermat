<?php

namespace Samsara\Fermat\Types\Traits\Arithmetic;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Enums\CalcMode;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 * @group Types
 */
class ArithmeticNativeTraitTest extends TestCase
{

    public function testAdd()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(CalcMode::Native);
        $three->setMode(CalcMode::Native);

        $this->assertEquals('4', $one->add(3)->getValue());

    }

    public function testSubtract()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(CalcMode::Native);
        $three->setMode(CalcMode::Native);

        $this->assertEquals('-2', $one->subtract(3)->getValue());

    }

    public function testMultiply()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(CalcMode::Native);
        $three->setMode(CalcMode::Native);

        $this->assertEquals('9', $three->multiply(3)->getValue());

    }

    public function testDivide()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(CalcMode::Native);
        $three->setMode(CalcMode::Native);

        $this->assertEquals('0.3333333333', $one->divide(3)->getValue());

    }

    public function testPow()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(CalcMode::Native);
        $three->setMode(CalcMode::Native);

        $this->assertEquals('27', $three->pow(3)->getValue());

    }

    public function testSqrt()
    {

        $three = new ImmutableDecimal('3');
        $three->setMode(CalcMode::Native);

        $this->assertEquals('1.7320508076', $three->sqrt()->getValue());

    }

}
