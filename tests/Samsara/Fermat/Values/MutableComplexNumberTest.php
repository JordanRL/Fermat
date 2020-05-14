<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;

class MutableComplexNumberTest extends TestCase
{

    public function testAdd()
    {

        $one = new ImmutableDecimal('1');
        $twoI = new ImmutableDecimal('2i');

        $negOne = new ImmutableDecimal('-1');
        $negTwoI = new ImmutableDecimal('-2i');

        $complex = new MutableComplexNumber($one, $twoI);

        $this->assertEquals('2+2i', $complex->add($one)->getValue());
        $this->assertEquals('2+4i', $complex->add($twoI)->getValue());

        $this->assertEquals('3+4i', $one->add($complex)->getValue());
        $this->assertEquals('3+6i', $twoI->add($complex)->getValue());

        $negComplex = new MutableComplexNumber($negOne, $negTwoI);

        $this->assertEquals('2+6i', $complex->add($negOne)->getValue());
        $this->assertEquals('2+4i', $complex->add($negTwoI)->getValue());
        $this->assertEquals('1+4i', $negOne->add($complex)->getValue());
        $this->assertEquals('1+2i', $negTwoI->add($complex)->getValue());

        $this->assertEquals('0', $negComplex->add($complex)->getValue());
        $this->assertEquals('0', $complex->add($negComplex)->getValue());

    }

}
