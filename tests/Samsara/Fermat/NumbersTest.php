<?php

namespace Samsara\Fermat;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;

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

    public function testMakeCoordinate()
    {
        $coords = [1, 5];

        $coordObj = Numbers::make(Numbers::CARTESIAN_COORDINATE, $coords);

        $this->assertEquals('5', $coordObj->getAxis('y'));
    }

    public function testMakeFromBase10()
    {

        $five = Numbers::makeFromBase10(Numbers::IMMUTABLE, 5, null, 5);

        $this->assertEquals('10', $five->getValue());

    }

    public function testMakePi()
    {

        $pi1 = Numbers::makePi();

        $this->assertEquals(Numbers::PI, $pi1->getValue());
        $this->assertEquals(100, $pi1->getPrecision());

        $pi2 = Numbers::makePi(5);

        $this->assertEquals('3.14159', $pi2->getValue());

        $this->expectException(IntegrityConstraint::class);

        Numbers::makePi(200);

    }

    public function testMakeTau()
    {

        $tau1 = Numbers::makeTau();

        $this->assertEquals(Numbers::TAU, $tau1->getValue());
        $this->assertEquals(100, $tau1->getPrecision());

        $tau2 = Numbers::make2Pi(5);

        $this->assertEquals('6.28318', $tau2->getValue());

        $this->expectException(IntegrityConstraint::class);

        Numbers::makeTau(200);

    }

    public function testMakeE()
    {

        $e1 = Numbers::makeE();

        $this->assertEquals(Numbers::E, $e1->getValue());
        $this->assertEquals(100, $e1->getPrecision());

        $e2 = Numbers::makeE(5);

        $this->assertEquals('2.71828', $e2->getValue());

        $this->expectException(IntegrityConstraint::class);

        Numbers::makeE(200);

    }

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

    public function testMakeOne()
    {
        $one = Numbers::makeOne();

        $this->assertEquals('1', $one->getValue());
    }

    public function testMakeZero()
    {
        $zero = Numbers::makeZero();

        $this->assertEquals('0', $zero->getValue());
    }

}