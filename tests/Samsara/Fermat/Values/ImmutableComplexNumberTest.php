<?php

declare(strict_types=1);

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ImmutableComplexNumberTest extends TestCase
{

    /**
     * @medium
     */
    public function testConstruct()
    {

        $one = new ImmutableDecimal('1');
        $twoI = new ImmutableDecimal('2i');
        $negTwoI = new ImmutableDecimal('-2i');

        $complex = new ImmutableComplexNumber($one, $twoI);

        $this->assertEquals('1+2i', $complex->getValue());

        $complex = new ImmutableComplexNumber($one, $negTwoI);

        $this->assertEquals('1-2i', $complex->getValue());

    }

    /**
     * @group complex
     * @medium
     */
    public function testAbsValue()
    {

        $three = new ImmutableDecimal('3');
        $fourI = new ImmutableDecimal('4i');

        $complex = new ImmutableComplexNumber($three, $fourI);

        $this->assertEquals('5', $complex->absValue());
        $this->assertEquals('5', $complex->abs()->getValue());

        $one = new ImmutableDecimal('1');
        $twoI = new ImmutableDecimal('2i');

        $complex = new ImmutableComplexNumber($one, $twoI);

        $this->assertEquals('2.2360679774', $complex->absValue());
        $this->assertEquals('2.2360679774', $complex->abs()->getValue());

    }

    /**
     * @group testing
     * @group arithmetic
     * @group complex
     * @medium
     */
    public function testAdd()
    {

        $one = new ImmutableDecimal('1');
        $twoI = new ImmutableDecimal('2i');

        $negOne = new ImmutableDecimal('-1');
        $negTwoI = new ImmutableDecimal('-2i');

        $complex = new ImmutableComplexNumber($one, $twoI);

        $this->assertEquals('2+2i', $complex->add($one)->getValue());
        $this->assertEquals('1+4i', $complex->add($twoI)->getValue());

        $this->assertEquals('2+2i', $one->add($complex)->getValue());
        $this->assertEquals('1+4i', $twoI->add($complex)->getValue());

        $negComplex = new ImmutableComplexNumber($negOne, $negTwoI);

        $this->assertEquals('2i', $complex->add($negOne)->getValue());
        $this->assertEquals('1', $complex->add($negTwoI)->getValue());
        $this->assertEquals('2i', $negOne->add($complex)->getValue());
        $this->assertEquals('1', $negTwoI->add($complex)->getValue());

        $this->assertEquals('0', $negComplex->add($complex)->getValue());
        $this->assertEquals('0', $complex->add($negComplex)->getValue());

    }

    /**
     * @group arithmetic
     * @group complex
     * @group testing
     * @medium
     */
    public function testSubtract()
    {

        $one = new ImmutableDecimal('1');
        $two = new ImmutableDecimal('2');
        $oneI = new ImmutableDecimal('1i');
        $twoI = new ImmutableDecimal('2i');

        $complex = new ImmutableComplexNumber($two, $twoI);

        $this->assertEquals('1+2i', $complex->subtract($one)->getValue());
        $this->assertEquals('2+1i', $complex->subtract($oneI)->getValue());
        $this->assertEquals('2i', $complex->subtract($two)->getValue());
        $this->assertEquals('2', $complex->subtract($twoI)->getValue());

        $this->assertEquals('-1-2i', $one->subtract($complex)->getValue());
        $this->assertEquals('-2-1i', $oneI->subtract($complex)->getValue());

    }

    /**
     * @group arithmetic
     * @group complex
     * @group testing
     * @medium
     */
    public function testMultiplySimple()
    {

        $one = new ImmutableDecimal('1');
        $two = new ImmutableDecimal('2');
        $oneI = new ImmutableDecimal('1i');
        $twoI = new ImmutableDecimal('2i');

        $complex = new ImmutableComplexNumber($two, $twoI);
        $complexSmall = new ImmutableComplexNumber($one, $oneI);

        $this->assertEquals('2+2i', $complexSmall->multiply($two)->getValue());
        $this->assertEquals('-2+2i', $complexSmall->multiply($twoI)->getValue());

    }

    /**
     * @group arithmetic
     * @group complex
     * @group testing
     * @medium
     */
    public function testMultiplyNegativeSimple()
    {

        $one = new ImmutableDecimal('1');
        $two = new ImmutableDecimal('-2');
        $oneI = new ImmutableDecimal('1i');
        $twoI = new ImmutableDecimal('-2i');

        $complex = new ImmutableComplexNumber($two, $twoI);
        $complexSmall = new ImmutableComplexNumber($one, $oneI);

        $this->assertEquals('-2-2i', $complexSmall->multiply($two)->getValue());
        $this->assertEquals('2-2i', $complexSmall->multiply($twoI)->getValue());

    }

    /**
     * @group arithmetic
     * @group complex
     * @group testing
     * @group failing
     * @medium
     */
    public function testMultiplyComplexFoil()
    {

        $one = new ImmutableDecimal('1');
        $two = new ImmutableDecimal('2');
        $oneI = new ImmutableDecimal('1i');
        $twoI = new ImmutableDecimal('2i');

        $complex = new ImmutableComplexNumber($two, $twoI);
        $complexSmall = new ImmutableComplexNumber($one, $oneI);

        $this->assertEquals('4i', $complexSmall->multiply($complex)->getValue());

    }

}