<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Decimal;
use Samsara\Fermat\Types\NumberCollection;

/**
 * @group DecimalLegacy
 */
class ImmutableDecimalTest extends TestCase
{
    /**
     * @medium
     */
    public function testGetters()
    {

        $five = new ImmutableDecimal(5);

        $this->assertEquals('5', $five->getValue());
        $this->assertEquals(10, $five->getScale());
        $this->assertEquals(NumberBase::Ten, $five->getBase());

        /** @var ImmutableDecimal $five */
        $five = $five->setBase(NumberBase::Sixteen);

        $this->assertEquals('5', $five->getValue());
        $this->assertEquals(10, $five->getScale());
        $this->assertEquals(NumberBase::Sixteen, $five->getBase());

        /** @var ImmutableDecimal $five */
        $five = $five->setBase(NumberBase::Five);

        $this->assertEquals('10', $five->getValue());
        $this->assertEquals(10, $five->getScale());
        $this->assertEquals(NumberBase::Five, $five->getBase());

    }
    /**
     * @medium
     */
    public function testFactorial()
    {

        $three = new ImmutableDecimal(3);

        $this->assertEquals('6', $three->factorial()->getValue());

        $five = new ImmutableDecimal(5);

        $this->assertEquals('120', $five->factorial()->getValue());
    }

    /**
     * @small
     */
    public function testFactorialExceptionsOne()
    {
        $negativeOne = new ImmutableDecimal(-1);

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Can only perform a factorial on a non-negative number.');

        $negativeOne->factorial();
    }

    /**
     * @small
     */
    public function testFactorialExceptionsTwo()
    {
        $oneTenth = new ImmutableDecimal('1.1');

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Can only perform a factorial on a whole number');

        $oneTenth->factorial();
    }

    /**
     * @medium
     */
    public function testSubFactorial()
    {
        $num = new ImmutableDecimal('4');

        $this->assertEquals('9', $num->subFactorial()->getValue());

        $num2 = new ImmutableDecimal('7');

        $this->assertEquals('1854', $num2->subFactorial()->getValue());

        $num3 = new ImmutableDecimal(0);

        $this->assertEquals('1', $num3->subFactorial()->getValue());
    }

    /**
     * @small
     */
    public function testSubFactorialExceptionsOne()
    {
        $negativeOne = new ImmutableDecimal(-1);

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Can only perform a sub-factorial on a non-negative number.');

        $negativeOne->subFactorial();
    }

    /**
     * @small
     */
    public function testSubFactorialExceptionsTwo()
    {
        $oneTenth = new ImmutableDecimal('1.1');

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Can only perform a sub-factorial on a whole number');

        $oneTenth->subFactorial();
    }

    /**
     * @medium
     */
    public function testDoubleFactorial()
    {

        $five = new ImmutableDecimal(5);

        $this->assertEquals('15', $five->doubleFactorial()->getValue());
        $this->assertEquals('15', $five->semiFactorial()->getValue());

        $negativeOne = new ImmutableDecimal(-1);

        $this->assertEquals('1', $negativeOne->doubleFactorial()->getValue());

        $oneTenth = new ImmutableDecimal('0.1');

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Can only perform a double factorial on a whole number');

        $oneTenth->doubleFactorial();

    }
    /**
     * @group arithmetic
     * @medium
     */
    public function testSqrt()
    {

        $four = new ImmutableDecimal(4);

        $this->assertEquals('2', $four->sqrt()->getValue());

        $two = new ImmutableDecimal(2, 10);

        $this->assertEquals('1.4142135624', $two->sqrt()->getValue());

        $largeInt = new ImmutableDecimal('1000000000000000000000000000');

        $this->assertEquals('31622776601683.7933199889', $largeInt->sqrt(10)->getValue());

    }

    /**
     * @medium
     */
    public function testGetLeastCommonMultiple()
    {

        $three = new ImmutableDecimal(3);
        $four = new ImmutableDecimal(4);
        $six = new ImmutableDecimal(6);

        $this->assertEquals('6', $three->getLeastCommonMultiple($six)->getValue());
        $this->assertEquals('12', $three->getLeastCommonMultiple($four)->getValue());

        $oneHalf = new ImmutableDecimal('0.5');

        $this->expectException(IntegrityConstraint::class);

        $three->getLeastCommonMultiple($oneHalf);

    }
    /**
     * @medium
     */
    public function testGetGreatestCommonDivisor()
    {

        $three = new ImmutableDecimal(3);
        $six = new ImmutableDecimal(6);

        $this->assertEquals('3', $three->getGreatestCommonDivisor($six)->getValue());
        $this->assertEquals('3', $six->getGreatestCommonDivisor($three)->getValue());

    }
    /**
     * @medium
     */
    public function testConverts()
    {

        $five = new ImmutableDecimal(5);

        $five = $five->setBase(NumberBase::Five);

        $this->assertEquals('10', $five->getValue());

        $four = new ImmutableDecimal(4);

        $this->assertEquals('14', $five->add($four)->getValue());
        $this->assertEquals('20', $five->add($five)->getValue());

        $five = new ImmutableDecimal(10, null, NumberBase::Five, false);

        $this->assertEquals('10', $five->getValue());
        $this->assertEquals('5', $five->getValue(NumberBase::Ten));
        $this->assertEquals('5', $five->setBase(NumberBase::Ten)->getValue());

        $five = new ImmutableDecimal(5, null, NumberBase::Five);

        $this->assertEquals('10', $five->getValue());
        $this->assertEquals('5', $five->getValue(NumberBase::Ten));
        $this->assertEquals('5', $five->setBase(NumberBase::Ten)->getValue());

        $negFive = new ImmutableDecimal(-10, null, NumberBase::Five, false);

        $this->assertEquals('-10', $negFive->getValue());
        $this->assertEquals('-5', $negFive->getValue(NumberBase::Ten));
        $this->assertEquals('-5', $negFive->setBase(NumberBase::Ten)->getValue());

        $negFive = new ImmutableDecimal(-5, null, NumberBase::Five);

        $this->assertEquals('-10', $negFive->getValue());
        $this->assertEquals('-5', $negFive->getValue(NumberBase::Ten));
        $this->assertEquals('-5', $negFive->setBase(NumberBase::Ten)->getValue());

    }
    /**
     * @medium
     */
    public function testAbsMethods()
    {

        $negFive = new ImmutableDecimal(-5);

        $this->assertEquals('5', $negFive->abs()->getValue());
        $this->assertEquals('5', $negFive->absValue());
        $this->assertEquals('-5', $negFive->getValue());

        $five = new ImmutableDecimal(5);

        $this->assertEquals('5', $five->abs()->getValue());
        $this->assertEquals('5', $five->absValue());
        $this->assertEquals('5', $five->getValue());

    }
    /**
     * @medium
     */
    public function testNumberState()
    {

        $negFive = new ImmutableDecimal(-5);
        $five = new ImmutableDecimal(5);
        $zero = Numbers::makeZero();

        $oneHalf = new ImmutableDecimal('0.5');

        $this->assertTrue($negFive->isNegative());
        $this->assertFalse($negFive->isPositive());

        $this->assertTrue($five->isPositive());
        $this->assertFalse($five->isNegative());

        $this->assertTrue($five->isInt());
        $this->assertTrue($five->isNatural());
        $this->assertTrue($five->isWhole());

        $this->assertTrue($negFive->isInt());
        $this->assertTrue($negFive->isNatural());
        $this->assertTrue($negFive->isWhole());

        $this->assertTrue($oneHalf->isPositive());
        $this->assertFalse($oneHalf->isNegative());

        $this->assertFalse($oneHalf->isInt());
        $this->assertFalse($oneHalf->isNatural());
        $this->assertFalse($oneHalf->isWhole());

        $this->assertFalse($zero->isPositive());
        $this->assertFalse($zero->isNegative());

    }
    /**
     * @medium
     */
    public function testIsPrime()
    {

        $two = new ImmutableDecimal(2);
        $three = new ImmutableDecimal(3);
        $six = new ImmutableDecimal(6);
        $twentySeven = new ImmutableDecimal(27);
        $thirtyOne = new ImmutableDecimal(31);
        $fortyFive = new ImmutableDecimal(45);
        $ninetyOne = new ImmutableDecimal(91);
        $tenThousandSeven = new ImmutableDecimal(10007);
        $largeNonPrime = new ImmutableDecimal('5915587277');
        $oneHalf = new ImmutableDecimal('0.5');

        $this->assertTrue($two->isPrime());
        $this->assertTrue($three->isPrime());
        $this->assertTrue($thirtyOne->isPrime());
        $this->assertFalse($six->isPrime());
        $this->assertFalse($twentySeven->isPrime());
        $this->assertFalse($fortyFive->isPrime());
        $this->assertFalse($ninetyOne->isPrime());
        $this->assertTrue($tenThousandSeven->isPrime());
        $this->assertTrue($largeNonPrime->isPrime());
        $this->assertFalse($oneHalf->isPrime());

    }
    /**
     * @medium
     */
    public function testCeilFloor()
    {

        $oneHalf = new ImmutableDecimal('0.5');

        $this->assertEquals('1', $oneHalf->ceil()->getValue());
        $this->assertEquals('0', $oneHalf->floor()->getValue());

    }
    /**
     * @medium
     */
    public function testRoundAndTruncate()
    {

        $pointFive = new ImmutableDecimal('0.5');

        $this->assertEquals('0', $pointFive->round()->getValue());

        $pointOneFive = new ImmutableDecimal('0.15');

        $this->assertEquals('0.2', $pointOneFive->round(1)->getValue());

        $testNum = new ImmutableDecimal('62.169797510839');

        $this->assertEquals('62.1697975108', $testNum->round(10)->getValue());
        $this->assertEquals('62', $testNum->truncate()->getValue());

        $closeToOne = new ImmutableDecimal('0.999999999999999');

        $this->assertEquals('1', $closeToOne->round()->getValue());
        $this->assertEquals('1', $closeToOne->round(4)->getValue());

    }
    /**
     * @medium
     */
    public function testNumberOfLeadingZeros()
    {

        $num = new ImmutableDecimal('0.00000000001');

        $this->assertEquals(10, $num->numberOfLeadingZeros());

    }
    /**
     * @medium
     */
    public function testOverflow()
    {

        $intMax = new ImmutableDecimal(PHP_INT_MAX);

        $this->assertEquals((string)PHP_INT_MAX, $intMax->add(1)->subtract(1)->getValue());

        $largeInt = new ImmutableDecimal('99999999999999999999999999999');

        $this->assertEquals('100000000000000000000000000000', $largeInt->add(1)->getValue());

    }
    /**
     * @medium
     */
    public function testScaleLimit()
    {

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('Scale of any number cannot be calculated beyond 2147483646 digits');

        new ImmutableDecimal(1, 2147483647);

    }
    /**
     * @medium
     */
    public function testModulo()
    {

        $four = new ImmutableDecimal(4);

        $this->assertEquals('0', $four->modulo(2)->getValue());

        $five = new ImmutableDecimal(5);

        $this->assertEquals('1', $five->modulo(2)->getValue());

    }
    /**
     * @medium
     */
    public function testContinuousModulo()
    {

        $pi = Numbers::makePi(100);

        $this->assertEquals('0', $pi->continuousModulo(Numbers::makePi(100))->getValue());

        $twoPi = Numbers::makeTau(100);

        $twoPiPlusTwo = $twoPi->add(2);

        $this->assertEquals('2', $twoPiPlusTwo->continuousModulo(Numbers::makePi(100))->getValue());

        $twoPi = Numbers::makeTau();

        $this->assertEquals('0', $twoPi->continuousModulo(Numbers::makePi())->getValue());

        $four = Numbers::make(Numbers::IMMUTABLE, 4, 100);

        $this->assertEquals(
            '0.858407346410206761537356616720497115802830600624894179025055407692183593713791001371965174657882932',
            $four->continuousModulo(Numbers::makePi(100))->getValue()
        );

    }
    /**
     * @medium
     */
    public function testAsInt()
    {

        $num2 = new ImmutableDecimal('15');

        $this->assertEquals(15, $num2->asInt());

        $this->assertEquals(15, $num2->add('0.2')->asInt());

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Cannot cast to integer when outside of integer range');

        $num = new ImmutableDecimal('1000000000000000000000000000000000000000000000000000000');
        $num->asInt();

    }
    /**
     * @medium
     */
    public function testDigitCounts()
    {

        $num1 = new ImmutableDecimal('15.242');

        $this->assertEquals(5, $num1->numberOfTotalDigits());
        $this->assertEquals(2, $num1->numberOfIntDigits());
        $this->assertEquals(3, $num1->numberOfDecimalDigits());
        $this->assertEquals(3, $num1->numberOfSigDecimalDigits());

        $num2 = new ImmutableDecimal('0.0000242');

        $this->assertEquals(3, $num2->numberOfSigDecimalDigits());
        $this->assertEquals(4, $num2->numberOfLeadingZeros());

    }
    /**
     * @medium
     */
    public function testObjectEquality()
    {

        $in1 = new ImmutableDecimal(12);
        $in2 = new ImmutableDecimal('12');
        $in3 = new ImmutableDecimal(16);

        $this->assertEquals('Samsara\\Fermat\\Values\\ImmutableDecimal12', $in1->hash());
        $this->assertEquals('Samsara\\Fermat\\Values\\ImmutableDecimal12', $in2->hash());
        $this->assertEquals('Samsara\\Fermat\\Values\\ImmutableDecimal16', $in3->hash());

        $mn1 = new MutableDecimal(12);
        $mn2 = new MutableDecimal(16);

        $this->assertTrue($in1->equals($mn1));
        $this->assertTrue($in1->equals($in2));
        $this->assertFalse($in1->equals($in3));
        $this->assertFalse($in1->equals($mn2));

        $nc = new NumberCollection([12,14,16]);

        $this->assertFalse($in1->equals('blahblah'));
        $this->assertFalse($in1->equals($nc));

    }

}
