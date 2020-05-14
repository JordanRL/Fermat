<?php

namespace Samsara\Fermat;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
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

        $one = Numbers::make(Numbers::IMMUTABLE_FRACTION, 1.1);

        $this->assertEquals('1/1', $one->getValue());

        $one = Numbers::make(Numbers::MUTABLE_FRACTION, 1.1);

        $this->assertEquals('1/1', $one->getValue());

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

        $one = Numbers::make(Numbers::IMMUTABLE_FRACTION, '1.3');

        $this->assertEquals('1/1', $one->getValue());

        $one = Numbers::make(Numbers::MUTABLE_FRACTION, '1.3');

        $this->assertEquals('1/1', $one->getValue());

    }
    /**
     * @medium
     */
    public function testMakeCoordinate()
    {
        $coords = [1, 5];

        $coordObj = Numbers::make(Numbers::CARTESIAN_COORDINATE, $coords);

        $this->assertEquals('5', $coordObj->getAxis('y'));
    }
    /**
     * @medium
     */
    public function testMakeFromBase10()
    {

        $five = Numbers::makeFromBase10(Numbers::IMMUTABLE, 5, null, 5);

        $this->assertEquals('10', $five->getValue());

    }
    /**
     * @medium
     */
    public function testMakePi()
    {

        $pi1 = Numbers::makePi();

        $this->assertEquals(Numbers::PI, $pi1->getValue());
        $this->assertEquals(100, $pi1->getPrecision());

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
        $this->assertEquals(100, $tau1->getPrecision());

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
        $this->assertEquals(100, $e1->getPrecision());

        $e2 = Numbers::makeE(5);

        $this->assertEquals('2.71828', $e2->getValue());

        $e3 = Numbers::makeE(103);

        $this->assertEquals(
            '2.7182818284590452353602874713526624977572470936999595749669676277240766303535475945713821785251664274274',
            $e3->getValue()
        );

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The E constant cannot have a precision less than 1');

        Numbers::makeE(-1);

    }
    /**
     * @medium
     */
    public function testMakeGoldenRatio()
    {

        $gr1 = Numbers::makeGoldenRatio();

        $this->assertEquals(Numbers::GOLDEN_RATIO, $gr1->getValue());
        $this->assertEquals(100, $gr1->getPrecision());

        $gr2 = Numbers::makeGoldenRatio(5);

        $this->assertEquals('1.61803', $gr2->getValue());

        $this->expectException(IntegrityConstraint::class);

        Numbers::makeGoldenRatio(200);

    }
    /**
     * @medium
     */
    public function testMakeLn10()
    {

        $ln10_1 = Numbers::makeNaturalLog10();

        $this->assertEquals(Numbers::LN_10, $ln10_1->getValue());
        $this->assertEquals(100, $ln10_1->getPrecision());

        $ln10_2 = Numbers::makeNaturalLog10(5);

        $this->assertEquals('2.30258', $ln10_2->getValue());

        $this->expectException(IntegrityConstraint::class);

        Numbers::makeNaturalLog10(200);

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