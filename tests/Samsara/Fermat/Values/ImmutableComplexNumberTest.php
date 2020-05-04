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
     * @medium
     */
    public function testAdd()
    {

        $one = new ImmutableDecimal('1');
        $twoI = new ImmutableDecimal('2i');

        $complex = new ImmutableComplexNumber($one, $twoI);

        $this->assertEquals('2+2i', $complex->add($one)->getValue());
        $this->assertEquals('1+4i', $complex->add($twoI)->getValue());

        $this->assertEquals('2+2i', $one->add($complex)->getValue());
        $this->assertEquals('1+4i', $twoI->add($complex)->getValue());

    }

}