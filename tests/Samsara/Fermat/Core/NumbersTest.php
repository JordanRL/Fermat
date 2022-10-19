<?php

namespace Samsara\Fermat\Core;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;

/**
 * @group DecimalLegacy
 */
class NumbersTest extends TestCase
{
    /**
     * @medium
     */
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
    /**
     * @medium
     */
    public function testMakeFromFloat()
    {

        $one = Numbers::make(Numbers::IMMUTABLE, 1.1);

        $this->assertEquals('1.1', $one->getValue());

        $one = Numbers::make(Numbers::MUTABLE, 1.1);

        $this->assertEquals('1.1', $one->getValue());

    }
    /**
     * @medium
     */
    public function testMakeFromFloatExceptions1()
    {

        $this->expectException(IntegrityConstraint::class);

        Numbers::make(Numbers::MUTABLE_FRACTION, 1.1);

    }/**
 * @medium
 */
    public function testMakeFromFloatExceptions2()
    {

        $this->expectException(IntegrityConstraint::class);

        Numbers::make(Numbers::IMMUTABLE_FRACTION, 1.1);

    }
    /**
     * @medium
     */
    public function testMakeFromString()
    {

        $one = Numbers::make(Numbers::IMMUTABLE, '1.3');

        $this->assertEquals('1.3', $one->getValue());

        $one = Numbers::make(Numbers::MUTABLE, '1.3');

        $this->assertEquals('1.3', $one->getValue());

    }
    /**
     * @medium
     */
    public function testMakeFromStringExceptions1()
    {

        $this->expectException(IntegrityConstraint::class);

        Numbers::make(Numbers::MUTABLE_FRACTION, '1.1');

    }/**
 * @medium
 */
    public function testMakeFromStringExceptions2()
    {

        $this->expectException(IntegrityConstraint::class);

        Numbers::make(Numbers::IMMUTABLE_FRACTION, '1.1');

    }
    /**
     * @medium
     */
    public function testMakeFromBase10()
    {

        $five = Numbers::makeFromBase10(Numbers::IMMUTABLE, 5, null, NumberBase::Five);

        $this->assertEquals('10', $five->getValue());

    }
    /**
     * @medium
     */
    public function testMakePi()
    {

        $pi1 = Numbers::makePi();

        $this->assertEquals(Numbers::PI, $pi1->getValue());
        $this->assertEquals(100, $pi1->getScale());

        $pi2 = Numbers::makePi(5);

        $this->assertEquals('3.14159', $pi2->getValue());

        $pi3 = Numbers::makePi(103);

        $this->assertEquals(
            '3.1415926535897932384626433832795028841971693993751058209749445923078164062862089986280348253421170679821',
            $pi3->getValue()
        );

        $this->expectException(IntegrityConstraint::class);

        Numbers::makePi(-1);

    }
    /**
     * @medium
     */
    public function testMakeTau()
    {

        $tau1 = Numbers::makeTau();

        $this->assertEquals(Numbers::TAU, $tau1->getValue());
        $this->assertEquals(100, $tau1->getScale());

        $tau2 = Numbers::make2Pi(5);

        $this->assertEquals('6.28318', $tau2->getValue());

        $tau3 = Numbers::makeTau(103);

        $this->assertEquals(
            '6.2831853071795864769252867665590057683943387987502116419498891846156328125724179972560696506842341359642',
            $tau3->getValue()
        );

        $this->expectException(IntegrityConstraint::class);

        Numbers::makeTau(-1);

    }
    /**
     * @large
     */
    public function testMakeE()
    {

        $e1 = Numbers::makeE();

        $this->assertEquals(Numbers::E, $e1->getValue());
        $this->assertEquals(100, $e1->getScale());

        $e2 = Numbers::makeE(5);

        $this->assertEquals('2.71828', $e2->getValue());

        $e3 = Numbers::makeE(103);

        $this->assertEquals(
            '2.7182818284590452353602874713526624977572470936999595749669676277240766303535475945713821785251664274274',
            $e3->getValue()
        );

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The E constant cannot have a scale less than 1');

        Numbers::makeE(-1);

    }
    /**
     * @medium
     */
    public function testMakeGoldenRatio()
    {

        $gr1 = Numbers::makeGoldenRatio();

        $this->assertEquals(Numbers::GOLDEN_RATIO, $gr1->getValue());
        $this->assertEquals(100, $gr1->getScale());

        $gr2 = Numbers::makeGoldenRatio(5);

        $this->assertEquals('1.61803', $gr2->getValue());

        $gr3 = Numbers::makeGoldenRatio(105);
        $this->assertEquals(
            '1.618033988749894848204586834365638117720309179805762862135448622705260462818902449707207204189391137484754',
            $gr3->getValue()
        );

        $this->expectException(IntegrityConstraint::class);

        Numbers::makeGoldenRatio(-1);

    }
    /**
     * @medium
     */
    public function testMakeLn10()
    {

        $ln10_1 = Numbers::makeNaturalLog10();

        $this->assertEquals(Numbers::LN_10, $ln10_1->getValue());
        $this->assertEquals(100, $ln10_1->getScale());

        $ln10_2 = Numbers::makeNaturalLog10(5);

        $this->assertEquals('2.30258', $ln10_2->getValue());

        $ln10_3 = Numbers::makeNaturalLog10(105);
        $this->assertEquals(
            '2.302585092994045684017991454684364207601101488628772976033327900967572609677352480235997205089598298341967',
            $ln10_3->getValue()
        );

        $this->expectException(IntegrityConstraint::class);

        Numbers::makeNaturalLog10(-1);

    }
    /**
     * @medium
     */
    public function testMakeLn2()
    {

        $ln2_1 = Numbers::makeNaturalLog2();

        $this->assertEquals(Numbers::LN_2, $ln2_1->getValue());
        $this->assertEquals(100, $ln2_1->getScale());

        $ln2_2 = Numbers::makeNaturalLog2(5);

        $this->assertEquals('0.69314', $ln2_2->getValue());

        $ln2_3 = Numbers::makeNaturalLog2(105);
        $this->assertEquals(
            '0.693147180559945309417232121458176568075500134360255254120680009493393621969694715605863326996418687542001',
            $ln2_3->getValue()
        );

        $this->expectException(IntegrityConstraint::class);

        Numbers::makeNaturalLog2(-1);

    }
    /**
     * @medium
     */
    public function testMakeOne()
    {
        $one = Numbers::makeOne();

        $this->assertEquals('1', $one->getValue());
    }
    /**
     * @medium
     */
    public function testMakeZero()
    {
        $zero = Numbers::makeZero();

        $this->assertEquals('0', $zero->getValue());
    }

    /**
     * @medium
     */
    public function testMakeImaginary()
    {

        $oneI = Numbers::make(Numbers::IMMUTABLE, '1i');

        $this->assertEquals('1i', $oneI->getValue());

        $twoI = Numbers::makeOrDont(Numbers::IMMUTABLE, '2i');

        $this->assertEquals('2i', $twoI->getValue());

    }

}