<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ImmutableDecimal2Test extends TestCase
{


    /**
     * @group arithmetic
     * @group complex
     * @medium
     */
    public function testImaginaryAdd()
    {

        $zero = new ImmutableDecimal(0);
        $one = new ImmutableDecimal(1);
        $oneI = new ImmutableDecimal('1i');

        $this->assertEquals('1+1i', $one->add($oneI)->getValue());
        $this->assertEquals('1+1i', $oneI->add($one)->getValue());
        $this->assertEquals('1i', $oneI->add($zero)->getValue());
        $this->assertEquals('1i', $zero->add($oneI)->getValue());

    }

    /**
     * @group arithmetic
     * @group complex
     * @medium
     */
    public function testImaginarySubtract()
    {

        $zero = new ImmutableDecimal(0);
        $one = new ImmutableDecimal(1);
        $oneI = new ImmutableDecimal('1i');

        $this->assertEquals('1-1i', $one->subtract($oneI)->getValue());
        $this->assertEquals('-1+1i', $oneI->subtract($one)->getValue());
        $this->assertEquals('1i', $oneI->subtract($zero)->getValue());
        $this->assertEquals('-1i', $zero->subtract($oneI)->getValue());

    }
}