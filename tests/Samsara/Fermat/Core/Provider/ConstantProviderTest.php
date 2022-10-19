<?php

namespace Samsara\Fermat\Core\Provider;

use PHPUnit\Framework\TestCase;

/**
 * @group Providers
 */
class ConstantProviderTest extends TestCase
{

    /**
     * @small
     */
    public function testMakePi()
    {
        $value = ConstantProvider::makePi(50);
        $this->assertEquals('3.1415926535897932384626433832795028841971693993751', $value);
    }

    /**
     * @small
     */
    public function testMakeE()
    {
        $value = ConstantProvider::makeE(50);
        $this->assertEquals('2.71828182845904523536028747135266249775724709369995', $value);

    }

    public function testMakePhi()
    {
        $value = ConstantProvider::makeGoldenRatio(50);
        $this->assertEquals('1.61803398874989484820458683436563811772030917980576', $value);
    }

    /**
     * @medium
     */
    public function testMakeIPowI()
    {
        $value = ConstantProvider::makeIPowI(50);
        $this->assertEquals('0.20787957635076190854695561983497877003387784163176', $value);
    }

    /**
     * @small
     */
    public function testMakeLn10()
    {
        $value = ConstantProvider::makeLn10(50);
        $this->assertEquals('2.30258509299404568401799145468436420760110148862877', $value);
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
