<?php

namespace Samsara\Fermat\Provider;

use PHPUnit\Framework\TestCase;

class ConstantProviderTest extends TestCase
{

    /**
     * @small
     */
    public function testMakePi()
    {

        $value = ConstantProvider::makePi(5);
        $this->assertEquals('3.14159', $value);

        $value = ConstantProvider::makePi(20);
        $this->assertEquals('3.14159265358979323846', $value);


    }

    /**
     * @small
     */
    public function testMakeE()
    {

        $value = ConstantProvider::makeE(5);
        $this->assertEquals('2.71828', $value);

    }

    /**
     * @small
     */
    public function testMakeLn10()
    {
        $value = ConstantProvider::makeLn10(5);
        $this->assertEquals('2.30258', $value);
    }

    /**
     * @small
     */
    public function testMakeLn2()
    {
        $value = ConstantProvider::makeLn2(50);
        $this->assertEquals('0.69314718055994530941723212145817656807550013436025', $value);
    }

    /**
     * @small
     */
    public function testMakeLn1p1()
    {
        $value = ConstantProvider::makeLn1p1(50);
        $this->assertEquals('0.09531017980432486004395212328076509222060536530864', $value);
    }
}
