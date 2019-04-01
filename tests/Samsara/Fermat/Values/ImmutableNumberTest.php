<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\NumberCollection;
use Samsara\Fermat\Types\Traits\ArithmeticTrait;

class DummyNumber {
    use ArithmeticTrait;
}

class ImmutableNumberTest extends TestCase
{

    public function testGetters()
    {

        $five = new ImmutableNumber(5);

        $this->assertEquals('5', $five->getValue());
        $this->assertEquals(10, $five->getPrecision());
        $this->assertEquals(10, $five->getBase());

        /** @var ImmutableNumber $five */
        $five = $five->convertToBase(16);

        $this->assertEquals('5', $five->getValue());
        $this->assertEquals(10, $five->getPrecision());
        $this->assertEquals(16, $five->getBase());

        /** @var ImmutableNumber $five */
        $five = $five->convertToBase(5);

        $this->assertEquals('10', $five->getValue());
        $this->assertEquals(10, $five->getPrecision());
        $this->assertEquals(5, $five->getBase());

    }

    public function testAdd()
    {

        $five = new ImmutableNumber(5);
        $ten = new ImmutableNumber(10);
        $oneQuarter = new ImmutableFraction(new ImmutableNumber(1), new ImmutableNumber(4));


        $this->assertEquals('10', $five->add(5)->getValue());
        $this->assertEquals(5, $five->asInt());

        $this->assertEquals('15', $five->add($ten)->getValue());

        $this->assertEquals('5.25', $five->add($oneQuarter)->getValue());

        $sixTenths = new ImmutableNumber('0.6');
        $fourTenths = new ImmutableNumber('0.4');

        $this->assertEquals('1', $sixTenths->add($fourTenths)->getValue());

        $oneTenth = new ImmutableNumber('0.1');
        $twoTenths = new ImmutableNumber('0.2');

        $this->assertEquals('0.3', $oneTenth->add($twoTenths)->getValue());

        $tenPrecision = new ImmutableNumber('0.0000000001');
        $elevenPrecision = new ImmutableNumber('0.00000000001');

        $this->assertEquals('0.1000000001', $oneTenth->add($tenPrecision)->getValue());
        $this->assertEquals('0.10000000001', $oneTenth->add($elevenPrecision)->getValue());

    }

    public function testSubtract()
    {

        $five = new ImmutableNumber(5);
        $six = new ImmutableNumber(6);

        $this->assertEquals('1', $six->subtract($five)->getValue());
        $this->assertEquals('-1', $five->subtract($six)->getValue());
        $this->assertEquals('3', $five->subtract(2)->getValue());
        $this->assertEquals('3', $five->subtract('2')->getValue());

        $oneQuarter = new ImmutableFraction(new ImmutableNumber(1), new ImmutableNumber(4));

        $this->assertEquals('4.75', $five->subtract($oneQuarter)->getValue());

        $sixTenths = new ImmutableNumber('0.6');
        $fourTenths = new ImmutableNumber('0.4');

        $this->assertEquals('0.2', $sixTenths->subtract($fourTenths)->getValue());

        $oneTenth = new ImmutableNumber('0.1');
        $twoTenths = new ImmutableNumber('0.2');

        $this->assertEquals('0.1', $twoTenths->subtract($oneTenth)->getValue());

        $tenPrecision = new ImmutableNumber('0.0000000001');
        $elevenPrecision = new ImmutableNumber('0.00000000001');

        $this->assertEquals('0.0999999999', $oneTenth->subtract($tenPrecision)->getValue());
        $this->assertEquals('0.09999999999', $oneTenth->subtract($elevenPrecision)->getValue());

    }

    public function testMultiply()
    {

        $five = new ImmutableNumber(5);
        $six = new ImmutableNumber(6);

        $this->assertEquals('30', $six->multiply($five)->getValue());
        $this->assertEquals('30', $five->multiply($six)->getValue());
        $this->assertEquals('-5', $five->multiply(-1)->getValue());
        $this->assertEquals(-5, $five->multiply(-1)->asInt());

        $oneQuarter = new ImmutableFraction(new ImmutableNumber(1), new ImmutableNumber(4));

        $this->assertEquals('1.5', $six->multiply($oneQuarter)->getValue());

        $sixTenths = new ImmutableNumber('0.6');

        $this->assertEquals('0.15', $sixTenths->multiply($oneQuarter)->getValue());

        $oneTenth = new ImmutableNumber('0.1');
        $twoTenths = new ImmutableNumber('0.2');

        $this->assertEquals('0.02', $twoTenths->multiply($oneTenth)->getValue());

    }

    public function testDivide()
    {

        $five = new ImmutableNumber(5);
        $ten = new ImmutableNumber(10);

        $this->assertEquals('2', $ten->divide($five)->getValue());
        $this->assertEquals('0.5', $five->divide($ten)->getValue());

        $oneQuarter = new ImmutableFraction(new ImmutableNumber(1), new ImmutableNumber(4));

        $this->assertEquals('40', $ten->divide($oneQuarter)->getValue());

    }

    public function testFactorial()
    {

        $three = new ImmutableNumber(3);

        $this->assertEquals('6', $three->factorial()->getValue());

        $five = new ImmutableNumber(5);

        $this->assertEquals('120', $five->factorial()->getValue());

        $three->setExtensions(false);
        $five->setExtensions(false);

        $this->assertEquals('6', $three->factorial()->getValue());
        $this->assertEquals('120', $five->factorial()->getValue());

        $negativeOne = new ImmutableNumber(-1);

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Cannot make a factorial with a number less than 1 (other than zero)');

        $negativeOne->factorial();

        $oneTenth = new ImmutableNumber('1.1');

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Can only perform a factorial on a whole number');

        $oneTenth->factorial();

    }

    public function testDoubleFactorial()
    {

        $five = new ImmutableNumber(5);

        $this->assertEquals('15', $five->doubleFactorial()->getValue());
        $this->assertEquals('15', $five->semiFactorial()->getValue());

        $negativeOne = new ImmutableNumber(-1);

        $this->assertEquals('1', $negativeOne->doubleFactorial()->getValue());

        $oneTenth = new ImmutableNumber('0.1');

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Can only perform a double factorial on a whole number');

        $oneTenth->doubleFactorial();

    }

    public function testPow()
    {

        $five = new ImmutableNumber(5);
        $two = new ImmutableNumber(2);

        $this->assertEquals('25', $five->pow($two)->getValue());

        $fourPointTwo = new ImmutableNumber('4.2');
        $three = new ImmutableNumber(3);

        $this->assertEquals('74.088', $fourPointTwo->pow($three)->getValue());

        $fortyTwoTenths = new ImmutableFraction(new ImmutableNumber(42), new ImmutableNumber(10));

        $this->assertEquals('100.9042061088', $three->pow($fortyTwoTenths)->getValue());

        $e = Numbers::makeE();

        $this->assertEquals('485165195.40979', $e->pow(20)->round(5)->getValue());

    }

    public function testExp()
    {

        $one = new ImmutableNumber(1);
        $e = Numbers::makeE();

        $this->assertTrue($one->exp()->truncate(5)->isEqual($e->truncate(5)));

    }

    public function testLn()
    {

        $five = new ImmutableNumber(5);

        $this->assertEquals('1.6094379124341', $five->ln()->getValue());

        $this->assertEquals('1.60943791243', $five->ln(11)->getValue());

        $fifteen = new ImmutableNumber(15);

        $this->assertContains('2.7080502011', $fifteen->ln(11)->getValue());

        $oneFifty = new ImmutableNumber(150);

        $this->assertEquals('5.010635294096', $oneFifty->ln(12)->getValue());

        $largeInt = new ImmutableNumber('1000000000000000000000000000');

        $this->assertEquals('62.16979751', $largeInt->ln(8)->getValue());

        $this->assertEquals('62.16979', $largeInt->ln(5, false)->getValue());

    }

    public function testLog10()
    {

        $five = new ImmutableNumber(5);

        $this->assertEquals('0.6989700043', $five->log10()->getValue());

        $this->assertEquals('0.69897000434', $five->log10(11)->getValue());

        $fifteen = new ImmutableNumber(15);

        $this->assertEquals('1.17609125906', $fifteen->log10(11)->getValue());

        $oneFifty = new ImmutableNumber(150);

        $this->assertEquals('2.176091259056', $oneFifty->log10(12)->getValue());

        $largeInt = new ImmutableNumber('1000000000000000000000000000');

        $this->assertEquals('27', $largeInt->log10(8)->getValue());

        $this->assertEquals('27', $largeInt->log10(5, false)->getValue());

    }

    public function testSqrt()
    {

        $four = new ImmutableNumber(4);

        $this->assertEquals('2', $four->sqrt()->getValue());

        $two = new ImmutableNumber(2, 10);

        $this->assertEquals('1.4142135623', $two->sqrt()->getValue());

        $largeInt = new ImmutableNumber('1000000000000000000000000000');

        $this->assertEquals('31622776601683.7933199889', $largeInt->sqrt(10)->getValue());

    }

    public function testSin()
    {
        /** @var ImmutableNumber $pi */
        $pi = Numbers::makePi(10);
        $zero = new ImmutableNumber(0);

        $this->assertEquals('0', $pi->sin()->getValue());
        $this->assertEquals('0', $zero->sin()->getValue());

        $four = new ImmutableNumber(4);

        $this->assertEquals('-0.7568024953', $four->sin()->getValue());

        $largeInt = new ImmutableNumber('1000000000000000000000000000');

        $this->assertEquals('0.718063496139118', $largeInt->sin(15)->getValue());
        $this->assertEquals('0.71806349613912', $largeInt->sin(14)->getValue());
        $this->assertEquals('0.71806349613911', $largeInt->sin(14, false)->getValue());

    }

    public function testCos()
    {
        /** @var ImmutableNumber $pi */
        $pi = Numbers::makePi();

        $this->assertEquals('-1', $pi->cos()->getValue());

        $four = new ImmutableNumber(4);

        $this->assertEquals('-0.6536436209', $four->cos()->getValue());

        $largeInt = new ImmutableNumber('1000000000000000000000000000');

        $this->assertEquals('-0.695977596990354', $largeInt->cos(15)->getValue());
        $this->assertEquals('-0.69597759699035', $largeInt->cos(14)->getValue());
        $this->assertEquals('-0.695977596990353', $largeInt->cos(15, false)->getValue());

    }

    public function testTan()
    {

        $twoPiDivThree = Numbers::make2Pi()->divide(3);

        $this->assertEquals('-1.73205080756888', $twoPiDivThree->tan(14)->getValue());
        $this->assertEquals('-1.73205080756887', $twoPiDivThree->tan(14, false)->getValue());

        $piDivTwo = Numbers::makePi()->divide(2);

        $this->assertEquals('INF', $piDivTwo->tan()->getValue());

    }

    public function testCot()
    {

        $five = new ImmutableNumber(5);

        $this->assertEquals('-0.295812916', $five->cot(9)->getValue());
        $this->assertEquals('-0.295812915', $five->cot(9, false)->getValue());

        $test = new ImmutableNumber('-0.7853981633');

        $this->assertEquals('1', $test->cot(2)->getValue());

    }

    public function testSec()
    {

        $five = new ImmutableNumber(5);

        $this->assertEquals('3.525320086', $five->sec(9)->getValue());
        $this->assertEquals('3.525320085', $five->sec(9, false)->getValue());

    }

    public function testCsc()
    {

        $five = new ImmutableNumber(5);

        $this->assertEquals('-1.042835213', $five->csc(9)->getValue());
        $this->assertEquals('-1.042835212', $five->csc(9, false)->getValue());

    }

    public function testArctan()
    {

        $five = new ImmutableNumber(5);

        $this->assertEquals('1.373400767', $five->arctan(9)->getValue());
        $this->assertEquals('1.373400766', $five->arctan(9, false)->getValue());

        $oneHalf = new ImmutableNumber('0.5');

        $this->assertEquals('0.463647609', $oneHalf->arctan(9, false)->getValue());

        $oneHalf = new ImmutableNumber('-0.5');

        $this->assertEquals('-0.463647609', $oneHalf->arctan(9, false)->getValue());

    }

    public function testArcsin()
    {

        $one = new ImmutableNumber(1);

        $this->assertEquals('1.5707963267', $one->arcsin(10, false)->getValue());
        $this->assertEquals('1.5707963268', $one->arcsin(10)->getValue());

        $zero = new ImmutableNumber(0);

        $this->assertEquals('0', $zero->arcsin(10, false)->getValue());
        $this->assertEquals('0', $zero->arcsin(10)->getValue());

        $negOne = new ImmutableNumber(-1);

        $this->assertEquals('-1.5707963267', $negOne->arcsin(10, false)->getValue());
        $this->assertEquals('-1.5707963268', $negOne->arcsin(10)->getValue());

        $oneHalf = new ImmutableNumber('0.5');

        $this->assertEquals('0.5235987755', $oneHalf->arcsin(10, false)->getValue());
        $this->assertEquals('0.5235987756', $oneHalf->arcsin(10)->getValue());

        $onePointFive = new ImmutableNumber('1.5');

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The arcsin function only has real values for inputs which have an absolute value of 1 or smaller');

        $onePointFive->arcsin();

    }

    public function testArccos()
    {

        $one = new ImmutableNumber(1);

        $this->assertEquals('0', $one->arccos()->getValue());

        $zero = new ImmutableNumber(0);

        $this->assertEquals('1.5707963267', $zero->arccos(10, false)->getValue());
        $this->assertEquals('1.5707963268', $zero->arccos(10)->getValue());

        $negOne = new ImmutableNumber(-1);

        $this->assertEquals('3.1415926535', $negOne->arccos(10, false)->getValue());
        $this->assertEquals('3.1415926536', $negOne->arccos(10)->getValue());

        $oneHalf = new ImmutableNumber('0.5');

        $this->assertEquals('1.0471975511', $oneHalf->arccos(10, false)->getValue());
        $this->assertEquals('1.0471975512', $oneHalf->arccos(10)->getValue());

        $onePointFive = new ImmutableNumber('1.5');

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The arccos function only has real values for inputs which have an absolute value of 1 or smaller');

        $onePointFive->arccos();

    }

    public function testArccot()
    {
        $one = new ImmutableNumber(1);

        $this->assertEquals('0.7853981633', $one->arccot(10, false)->getValue());
        $this->assertEquals('0.7853981634', $one->arccot(10)->getValue());

        $zero = new ImmutableNumber(0);

        $this->assertEquals('1.5707963267', $zero->arccot(10, false)->getValue());
        $this->assertEquals('1.5707963268', $zero->arccot(10)->getValue());

        $negOne = new ImmutableNumber(-1);

        $this->assertEquals('2.3561944901', $negOne->arccot(10, false)->getValue());
        $this->assertEquals('2.3561944902', $negOne->arccot(10)->getValue());

        $oneHalf = new ImmutableNumber('0.5');

        $this->assertEquals('1.1071487177', $oneHalf->arccot(10, false)->getValue());
        $this->assertEquals('1.1071487178', $oneHalf->arccot(10)->getValue());

        $negOneHalf = new ImmutableNumber('-0.5');

        $this->assertEquals('2.0344439357', $negOneHalf->arccot(10, false)->getValue());
        $this->assertEquals('2.0344439358', $negOneHalf->arccot(10)->getValue());

        $negPointNine = new ImmutableNumber('-0.9');

        $this->assertEquals('2.3036114285', $negPointNine->arccot(10, false)->getValue());
        $this->assertEquals('2.3036114286', $negPointNine->arccot(10)->getValue());

        $negOnePointOne = new ImmutableNumber('-1.1');

        $this->assertEquals('2.4037775934', $negOnePointOne->arccot(10, false)->getValue());
        $this->assertEquals('2.4037775935', $negOnePointOne->arccot(10)->getValue());

    }

    public function testArcsec()
    {

        $one = new ImmutableNumber(1);

        $this->assertEquals('0', $one->arcsec(10, false)->getValue());

        $negOne = new ImmutableNumber(-1);

        $this->assertEquals('3.1415926535', $negOne->arcsec(10, false)->getValue());
        $this->assertEquals('3.1415926536', $negOne->arcsec(10)->getValue());

        $oneHalf = new ImmutableNumber('1.5');

        $this->assertEquals('0.8410686705', $oneHalf->arcsec(10, false)->getValue());
        $this->assertEquals('0.8410686706', $oneHalf->arcsec(10)->getValue());

        $oneHalf = new ImmutableNumber('0.5');

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The arcsec function only has real values for inputs which have an absolute value greater than 1');

        $oneHalf->arcsec();

    }

    public function testArccsc()
    {

        $one = new ImmutableNumber(1);

        $this->assertEquals('1.5707963267', $one->arccsc(10, false)->getValue());
        $this->assertEquals('1.5707963268', $one->arccsc(10)->getValue());

        $negOne = new ImmutableNumber(-1);

        $this->assertEquals('-1.5707963267', $negOne->arccsc(10, false)->getValue());
        $this->assertEquals('-1.5707963268', $negOne->arccsc(10)->getValue());


        $oneAndAHalf = new ImmutableNumber('1.5');

        $this->assertEquals('0.7297276562', $oneAndAHalf->arccsc(10, false)->getValue());
        $this->assertEquals('0.7297276562', $oneAndAHalf->arccsc(10)->getValue());

        $oneHalf = new ImmutableNumber('0.5');

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The arccsc function only has real values for inputs which have an absolute value greater than 1');

        $oneHalf->arccsc();

    }

    public function testGetLeastCommonMultiple()
    {

        $three = new ImmutableNumber(3);
        $four = new ImmutableNumber(4);
        $six = new ImmutableNumber(6);

        $this->assertEquals('6', $three->getLeastCommonMultiple($six)->getValue());
        $this->assertEquals('12', $three->getLeastCommonMultiple($four)->getValue());

        $oneHalf = new ImmutableNumber('0.5');

        $this->expectException(IntegrityConstraint::class);

        $three->getLeastCommonMultiple($oneHalf);

    }

    public function testGetGreatestCommonDivisor()
    {

        $three = new ImmutableNumber(3);
        $six = new ImmutableNumber(6);

        $this->assertEquals('3', $three->getGreatestCommonDivisor($six)->getValue());
        $this->assertEquals('3', $six->getGreatestCommonDivisor($three)->getValue());

        $three->setExtensions(false);
        $six->setExtensions(false);

        $this->assertEquals('3', $three->getGreatestCommonDivisor($six)->getValue());
        $this->assertEquals('3', $six->getGreatestCommonDivisor($three)->getValue());

    }

    public function testConverts()
    {

        $five = new ImmutableNumber(5);

        $five = $five->convertToBase(5);

        $this->assertEquals('10', $five->getValue());

        $four = new ImmutableNumber(4);

        $this->assertEquals('14', $five->add($four)->getValue());
        $this->assertEquals('20', $five->add($five)->getValue());

    }

    public function testAbsMethods()
    {

        $negFive = new ImmutableNumber(-5);

        $this->assertEquals('5', $negFive->abs()->getValue());
        $this->assertEquals('5', $negFive->absValue());

        $five = new ImmutableNumber(5);

        $this->assertEquals('5', $five->abs()->getValue());
        $this->assertEquals('5', $five->absValue());

    }

    public function testNumberState()
    {

        $negFive = new ImmutableNumber(-5);
        $five = new ImmutableNumber(5);
        $zero = Numbers::makeZero();

        $oneHalf = new ImmutableNumber('0.5');

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

    public function testIsPrime()
    {

        $two = new ImmutableNumber(2);
        $three = new ImmutableNumber(3);
        $six = new ImmutableNumber(6);
        $twentySeven = new ImmutableNumber(27);
        $thirtyOne = new ImmutableNumber(31);
        $fortyFive = new ImmutableNumber(45);
        $ninetyOne = new ImmutableNumber(91);
        $tenThousandSeven = new ImmutableNumber(10007);
        //$largeNonPrime = new ImmutableNumber('99799811');
        $oneHalf = new ImmutableNumber('0.5');

        $this->assertTrue($two->isPrime());
        $this->assertTrue($three->isPrime());
        $this->assertTrue($thirtyOne->isPrime());
        $this->assertFalse($six->isPrime());
        $this->assertFalse($twentySeven->isPrime());
        $this->assertFalse($fortyFive->isPrime());
        $this->assertFalse($ninetyOne->isPrime());
        $this->assertTrue($tenThousandSeven->isPrime());
        //$this->assertFalse($largeNonPrime->isPrime());
        $this->assertFalse($oneHalf->isPrime());

    }

    public function testCeilFloor()
    {

        $oneHalf = new ImmutableNumber('0.5');

        $this->assertEquals('1', $oneHalf->ceil()->getValue());
        $this->assertEquals('0', $oneHalf->floor()->getValue());

    }

    public function testRoundAndTruncate()
    {

        $pointFive = new ImmutableNumber('0.5');

        $this->assertEquals('1', $pointFive->round()->getValue());

        $pointOneFive = new ImmutableNumber('0.15');

        $this->assertEquals('0.2', $pointOneFive->round(1)->getValue());

        $testNum = new ImmutableNumber('62.169797510839');

        $this->assertEquals('62.1697975108', $testNum->round(10)->getValue());
        $this->assertEquals('62', $testNum->truncate()->getValue());

        $closeToOne = new ImmutableNumber('0.999999999999999');

        $this->assertEquals('1', $closeToOne->round()->getValue());
        $this->assertEquals('1', $closeToOne->round(4)->getValue());

    }

    public function testNumberOfLeadingZeros()
    {

        $num = new ImmutableNumber('0.00000000001');

        $this->assertEquals(10, $num->numberOfLeadingZeros());

    }

    public function testOverflow()
    {

        $intMax = new ImmutableNumber(PHP_INT_MAX);

        $this->assertEquals((string)PHP_INT_MAX, $intMax->add(1)->subtract(1)->getValue());

        $largeInt = new ImmutableNumber('99999999999999999999999999999');

        $this->assertEquals('100000000000000000000000000000', $largeInt->add(1)->getValue());

    }

    public function testPrecisionLimit()
    {

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('Precision of any number cannot be calculated beyond 2147483646 digits');

        $precisionLimit = new ImmutableNumber(1, 2147483647);

    }

    public function testModulo()
    {

        $four = new ImmutableNumber(4);

        $this->assertEquals('0', $four->modulo(2)->getValue());

        $five = new ImmutableNumber(5);

        $this->assertEquals('1', $five->modulo(2)->getValue());

    }

    public function testContinuousModulo()
    {

        $pi = new ImmutableNumber(Numbers::PI);

        $this->assertEquals('0', $pi->continuousModulo(Numbers::PI)->getValue());

        $twoPi = new ImmutableNumber(Numbers::TAU);

        $twoPiPlusTwo = $twoPi->add(2);

        $this->assertEquals('2', $twoPiPlusTwo->continuousModulo(Numbers::PI)->getValue());

    }

    public function testAsInt()
    {

        $num2 = new ImmutableNumber('15');

        $this->assertEquals(15, $num2->asInt());

        $this->assertEquals(15, $num2->add('0.2')->asInt());

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Cannot export number as integer because it is out of range');

        $num = new ImmutableNumber('1000000000000000000000000000000000000000000000000000000');
        $num->asInt();

    }

    public function testDigitCounts()
    {

        $num1 = new ImmutableNumber('15.242');

        $this->assertEquals(5, $num1->numberOfTotalDigits());
        $this->assertEquals(2, $num1->numberOfIntDigits());
        $this->assertEquals(3, $num1->numberOfDecimalDigits());
        $this->assertEquals(3, $num1->numberOfSigDecimalDigits());

        $num2 = new ImmutableNumber('0.0000242');

        $this->assertEquals(3, $num2->numberOfSigDecimalDigits());
        $this->assertEquals(4, $num2->numberOfLeadingZeros());

    }

    public function testObjectEquality()
    {

        $in1 = new ImmutableNumber(12);
        $in2 = new ImmutableNumber('12');
        $in3 = new ImmutableNumber(16);

        $this->assertEquals('Samsara\\Fermat\\Values\\ImmutableNumber12', $in1->hash());
        $this->assertEquals('Samsara\\Fermat\\Values\\ImmutableNumber12', $in2->hash());
        $this->assertEquals('Samsara\\Fermat\\Values\\ImmutableNumber16', $in3->hash());

        $mn1 = new MutableNumber(12);
        $mn2 = new MutableNumber(16);

        $this->assertTrue($in1->equals($mn1));
        $this->assertTrue($in1->equals($in2));
        $this->assertFalse($in1->equals($in3));
        $this->assertFalse($in1->equals($mn2));

        $nc = new NumberCollection([12,14,16]);

        $this->assertFalse($in1->equals('blahblah'));
        $this->assertFalse($in1->equals($nc));

    }

    public function testArithmeticTraitCheck()
    {

        $dummy = new DummyNumber();

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('You cannot use the ArithmeticTrait without implementing either the DecimalInterface or FractionInterface');

        $dummy->add(1);

    }

}
