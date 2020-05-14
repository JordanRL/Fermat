<?php

namespace Samsara\Fermat\Provider;

use PHPUnit\Framework\TestCase;

class ConstantProviderTest extends TestCase
{

    public function testMakePi()
    {

        $value = ConstantProvider::makePi(5);
        $this->assertEquals('3.14159', $value);

        $value = ConstantProvider::makePi(20);
        $this->assertEquals('3.14159265358979323846', $value);


    }

    public function testMakeE()
    {

        $value = ConstantProvider::makeE(5);
        $this->assertEquals('2.71828', $value);

    }
}
