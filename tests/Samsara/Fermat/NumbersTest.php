<?php

namespace Samsara\Fermat;

use PHPUnit\Framework\TestCase;

class NumbersTest extends TestCase
{

    public function testMakeFromInteger()
    {

        $one = Numbers::make(Numbers::IMMUTABLE, 1);
        $max = Numbers::make(Numbers::IMMUTABLE, PHP_INT_MAX);

        $this->assertEquals('1', $one->getValue());
        $this->assertEquals((string) PHP_INT_MAX, $max->getValue());

        $one = Numbers::make(Numbers::MUTABLE, 1);
        $max = Numbers::make(Numbers::MUTABLE, PHP_INT_MAX);

        $this->assertEquals('1', $one->getValue());
        $this->assertEquals((string) PHP_INT_MAX, $max->getValue());

        $one = Numbers::make(Numbers::IMMUTABLE_FRACTION, 1);
        $max = Numbers::make(Numbers::IMMUTABLE_FRACTION, PHP_INT_MAX);

        $this->assertEquals('1/1', $one->getValue());
        $this->assertEquals(PHP_INT_MAX.'/1', $max->getValue());

        $one = Numbers::make(Numbers::MUTABLE_FRACTION, 1);
        $max = Numbers::make(Numbers::MUTABLE_FRACTION, PHP_INT_MAX);

        $this->assertEquals('1/1', $one->getValue());
        $this->assertEquals(PHP_INT_MAX.'/1', $max->getValue());

    }

}