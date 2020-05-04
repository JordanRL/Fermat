<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ImmutableComplexNumberTest extends TestCase
{

    /**
     * @small
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
     * @small
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
     * @medium
     */
    public function testAdd()
    {

        $one = new ImmutableDecimal('1');
        $twoI = new ImmutableDecimal('2i');

        $complex = new ImmutableComplexNumber($one, $twoI);

        $this->assertEquals('2+2i', $complex->add($one)->getValue());
        $this->assertEquals('1+4i', $complex->add($twoI)->getValue());

    }

}