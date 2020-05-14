<?php

namespace Samsara\Fermat\Types\Traits\Arithmetic;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Types\Base\Selectable;
use Samsara\Fermat\Values\ImmutableDecimal;

class ArithmeticNativeTraitTest extends TestCase
{

    public function testAdd()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(Selectable::CALC_MODE_NATIVE);
        $three->setMode(Selectable::CALC_MODE_NATIVE);

        $this->assertEquals('4', $one->add(3)->getValue());

    }

    public function testSubtract()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(Selectable::CALC_MODE_NATIVE);
        $three->setMode(Selectable::CALC_MODE_NATIVE);

        $this->assertEquals('-2', $one->subtract(3)->getValue());

    }

    public function testMultiply()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(Selectable::CALC_MODE_NATIVE);
        $three->setMode(Selectable::CALC_MODE_NATIVE);

        $this->assertEquals('9', $three->multiply(3)->getValue());

    }

    public function testDivide()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(Selectable::CALC_MODE_NATIVE);
        $three->setMode(Selectable::CALC_MODE_NATIVE);

        $this->assertEquals('0.33333333333333', $one->divide(3)->getValue());

    }

    public function testPow()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(Selectable::CALC_MODE_NATIVE);
        $three->setMode(Selectable::CALC_MODE_NATIVE);

        $this->assertEquals('27', $three->pow(3)->getValue());

    }

    public function testSqrt()
    {

        $one = new ImmutableDecimal('1');
        $three = new ImmutableDecimal('3');
        $one->setMode(Selectable::CALC_MODE_NATIVE);
        $three->setMode(Selectable::CALC_MODE_NATIVE);

        $this->assertEquals('1.7320508075689', $three->sqrt()->getValue());

    }

}
