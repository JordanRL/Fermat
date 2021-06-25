<?php

namespace Samsara\Fermat\Types\Traits\Arithmetic;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Numbers;

class ArithmeticSelectionTraitTest extends TestCase
{

    public function testTranslateToParts()
    {

        $one = Numbers::makeOne();

        $this->assertEquals('1.5', $one->add('1/2')->getValue());
        $this->assertEquals('2+1i', $one->add('1+1i')->getValue());

    }

}
