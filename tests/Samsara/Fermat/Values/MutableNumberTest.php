<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;

class MutableNumberTest extends TestCase
{

    public function testGetters()
    {

        $five = new MutableNumber(5);

        $this->assertEquals('5', $five->getValue());
        $this->assertEquals(10, $five->getPrecision());
        $this->assertEquals(10, $five->getBase());

        /** @var MutableNumber $five */
        $five = $five->convertToBase(16);

        $this->assertEquals('5', $five->getValue());
        $this->assertEquals(10, $five->getPrecision());
        $this->assertEquals(16, $five->getBase());

        /** @var MutableNumber $five */
        $five = $five->convertToBase(5);

        $this->assertEquals('10', $five->getValue());
        $this->assertEquals(10, $five->getPrecision());
        $this->assertEquals(5, $five->getBase());

    }

    public function testAdd()
    {

        $five = new MutableNumber(5);
        $ten = new MutableNumber(10);
        $oneQuarter = new ImmutableFraction(new MutableNumber(1), new MutableNumber(4));


        $this->assertEquals('10', $five->add(5)->getValue());
        $this->assertEquals(5, $five->asInt());

        $this->assertEquals('15', $five->add($ten)->getValue());

        $this->assertEquals('5.25', $five->add($oneQuarter)->getValue());

        $sixTenths = new MutableNumber('0.6');
        $fourTenths = new MutableNumber('0.4');

        $this->assertEquals('1', $sixTenths->add($fourTenths)->getValue());

        $oneTenth = new MutableNumber('0.1');
        $twoTenths = new MutableNumber('0.2');

        $this->assertEquals('0.3', $oneTenth->add($twoTenths)->getValue());

        $tenPrecision = new MutableNumber('0.0000000001');
        $elevenPrecision = new MutableNumber('0.00000000001');

        $this->assertEquals('0.1000000001', $oneTenth->add($tenPrecision)->getValue());
        $this->assertEquals('0.10000000001', $oneTenth->add($elevenPrecision)->getValue());

    }

    public function testSubtract()
    {

        $five = new MutableNumber(5);
        $six = new MutableNumber(6);

        $this->assertEquals('1', $six->subtract($five)->getValue());
        $this->assertEquals('-1', $five->subtract($six)->getValue());
        $this->assertEquals('3', $five->subtract(2)->getValue());
        $this->assertEquals('3', $five->subtract('2')->getValue());

        $oneQuarter = new ImmutableFraction(new MutableNumber(1), new MutableNumber(4));

        $this->assertEquals('4.75', $five->subtract($oneQuarter)->getValue());

        $sixTenths = new MutableNumber('0.6');
        $fourTenths = new MutableNumber('0.4');

        $this->assertEquals('0.2', $sixTenths->subtract($fourTenths)->getValue());

        $oneTenth = new MutableNumber('0.1');
        $twoTenths = new MutableNumber('0.2');

        $this->assertEquals('0.1', $twoTenths->subtract($oneTenth)->getValue());

        $tenPrecision = new MutableNumber('0.0000000001');
        $elevenPrecision = new MutableNumber('0.00000000001');

        $this->assertEquals('0.0999999999', $oneTenth->subtract($tenPrecision)->getValue());
        $this->assertEquals('0.09999999999', $oneTenth->subtract($elevenPrecision)->getValue());

    }

    public function testMultiply()
    {

        $five = new MutableNumber(5);
        $six = new MutableNumber(6);

        $this->assertEquals('30', $six->multiply($five)->getValue());
        $this->assertEquals('30', $five->multiply($six)->getValue());
        $this->assertEquals('-5', $five->multiply(-1)->getValue());
        $this->assertEquals(-5, $five->multiply(-1)->asInt());

        $oneQuarter = new ImmutableFraction(new MutableNumber(1), new MutableNumber(4));

        $this->assertEquals('1.5', $six->multiply($oneQuarter)->getValue());

        $sixTenths = new MutableNumber('0.6');

        $this->assertEquals('0.15', $sixTenths->multiply($oneQuarter)->getValue());

        $oneTenth = new MutableNumber('0.1');
        $twoTenths = new MutableNumber('0.2');

        $this->assertEquals('0.02', $twoTenths->multiply($oneTenth)->getValue());

    }

    public function testDivide()
    {

        $five = new MutableNumber(5);
        $ten = new MutableNumber(10);

        $this->assertEquals('2', $ten->divide($five)->getValue());
        $this->assertEquals('0.5', $five->divide($ten)->getValue());

        $oneQuarter = new ImmutableFraction(new MutableNumber(1), new MutableNumber(4));

        $this->assertEquals('40', $ten->divide($oneQuarter)->getValue());

    }

    public function testFactorial()
    {

        $three = new MutableNumber(3);

        $this->assertEquals('6', $three->factorial()->getValue());

        $five = new MutableNumber(5);

        $this->assertEquals('120', $five->factorial()->getValue());

        $three->setExtensions(false);
        $five->setExtensions(false);

        $this->assertEquals('6', $three->factorial()->getValue());
        $this->assertEquals('120', $five->factorial()->getValue());

        $negativeOne = new MutableNumber(-1);

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Cannot make a factorial with a number less than 1 (other than zero)');

        $negativeOne->factorial();

        $oneTenth = new MutableNumber('1.1');

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Can only perform a factorial on a whole number');

        $oneTenth->factorial();

    }

    public function testDoubleFactorial()
    {

        $five = new MutableNumber(5);

        $this->assertEquals('15', $five->doubleFactorial()->getValue());
        $this->assertEquals('15', $five->semiFactorial()->getValue());

        $negativeOne = new MutableNumber(-1);

        $this->assertEquals('1', $negativeOne->doubleFactorial()->getValue());

        $oneTenth = new MutableNumber('0.1');

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Can only perform a double factorial on a whole number');

        $oneTenth->doubleFactorial();

    }

    public function testPow()
    {

        $five = new MutableNumber(5);
        $two = new MutableNumber(2);

        $this->assertEquals('25', $five->pow($two)->getValue());

        $fourPointTwo = new MutableNumber('4.2');
        $three = new MutableNumber(3);

        $this->assertEquals('74.088', $fourPointTwo->pow($three)->getValue());

        $fortyTwoTenths = new ImmutableFraction(new MutableNumber(42), new MutableNumber(10));

        $this->assertEquals('100.9042061088', $three->pow($fortyTwoTenths)->getValue());

        $e = Numbers::makeE();

        $this->assertEquals('485165195.40979', $e->pow(20)->round(5)->getValue());

    }

    public function testExp()
    {

        $one = new MutableNumber(1);
        $e = Numbers::makeE();

        $this->assertTrue($one->exp()->truncate(5)->isEqual($e->truncate(5)));

    }

    public function testLn()
    {

        $five = new MutableNumber(5);

        $this->assertEquals('1.6094379124341', $five->ln()->getValue());

        $this->assertEquals('1.60943791243', $five->ln(11)->getValue());

        $fifteen = new MutableNumber(15);

        $this->assertEquals('2.70805020110', $fifteen->ln(11)->getValue());

        $oneFifty = new MutableNumber(150);

        $this->assertEquals('5.010635294096', $oneFifty->ln(12)->getValue());

        $largeInt = new MutableNumber('1000000000000000000000000000');

        $this->assertEquals('62.16979751', $largeInt->ln(8)->getValue());

        $this->assertEquals('62.16979', $largeInt->ln(5, false)->getValue());

    }

    public function testLog10()
    {

        $five = new MutableNumber(5);

        $this->assertEquals('0.6989700043', $five->log10()->getValue());

        $this->assertEquals('0.69897000434', $five->log10(11)->getValue());

        $fifteen = new MutableNumber(15);

        $this->assertEquals('1.17609125906', $fifteen->log10(11)->getValue());

        $oneFifty = new MutableNumber(150);

        $this->assertEquals('2.176091259056', $oneFifty->log10(12)->getValue());

        $largeInt = new MutableNumber('1000000000000000000000000000');

        $this->assertEquals('27', $largeInt->log10(8)->getValue());

        $this->assertEquals('27', $largeInt->log10(5, false)->getValue());

    }

    public function testSqrt()
    {

        $four = new MutableNumber(4);

        $this->assertEquals('2', $four->sqrt()->getValue());

        $two = new MutableNumber(2, 10);

        $this->assertEquals('1.4142135623', $two->sqrt()->getValue());

        $largeInt = new MutableNumber('1000000000000000000000000000');

        $this->assertEquals('31622776601683.7933199889', $largeInt->sqrt()->getValue());

    }

    public function testSin()
    {
        /** @var MutableNumber $pi */
        $pi = Numbers::makePi();

        $this->assertEquals('0', $pi->sin()->getValue());

        $four = new MutableNumber(4);

        $this->assertEquals('-0.7568024953', $four->sin()->getValue());

        $largeInt = new MutableNumber('1000000000000000000000000000');

        $this->assertEquals('0.718063496139118', $largeInt->sin(15)->getValue());
        $this->assertEquals('0.71806349613912', $largeInt->sin(14)->getValue());
        $this->assertEquals('0.71806349613911', $largeInt->sin(14, false)->getValue());

    }

    public function testCos()
    {
        /** @var MutableNumber $pi */
        $pi = Numbers::makePi();

        $this->assertEquals('-1', $pi->cos()->getValue());

        $four = new MutableNumber(4);

        $this->assertEquals('-0.6536436209', $four->cos()->getValue());

        $largeInt = new MutableNumber('1000000000000000000000000000');

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

        $five = new MutableNumber(5);

        $this->assertEquals('-0.295812916', $five->cot(9)->getValue());
        $this->assertEquals('-0.295812915', $five->cot(9, false)->getValue());

    }

    public function testSec()
    {

        $five = new MutableNumber(5);

        $this->assertEquals('3.525320086', $five->sec(9)->getValue());
        $this->assertEquals('3.525320085', $five->sec(9, false)->getValue());

    }

    public function testCsc()
    {

        $five = new MutableNumber(5);

        $this->assertEquals('-1.042835213', $five->csc(9)->getValue());
        $this->assertEquals('-1.042835212', $five->csc(9, false)->getValue());

    }

    public function testArctan()
    {

        $five = new MutableNumber(5);

        $this->assertEquals('1.373400767', $five->arctan(9)->getValue());
        $this->assertEquals('1.373400766', $five->arctan(9, false)->getValue());

    }

    public function testGetLeastCommonMultiple()
    {

        $three = new MutableNumber(3);
        $four = new MutableNumber(4);
        $six = new MutableNumber(6);

        $this->assertEquals('6', $three->getLeastCommonMultiple($six)->getValue());
        $this->assertEquals('12', $three->getLeastCommonMultiple($four)->getValue());

        $oneHalf = new MutableNumber('0.5');

        $this->expectException(IntegrityConstraint::class);

        $three->getLeastCommonMultiple($oneHalf);

    }

    public function testGetGreatestCommonDivisor()
    {

        $three = new MutableNumber(3);
        $six = new MutableNumber(6);

        $this->assertEquals('3', $three->getGreatestCommonDivisor($six)->getValue());
        $this->assertEquals('3', $six->getGreatestCommonDivisor($three)->getValue());

        $three->setExtensions(false);
        $six->setExtensions(false);

        $this->assertEquals('3', $three->getGreatestCommonDivisor($six)->getValue());
        $this->assertEquals('3', $six->getGreatestCommonDivisor($three)->getValue());

    }

    public function testConverts()
    {

        $five = new MutableNumber(5);

        $five = $five->convertToBase(5);

        $this->assertEquals('10', $five->getValue());

        $four = new MutableNumber(4);

        $this->assertEquals('14', $five->add($four)->getValue());
        $this->assertEquals('20', $five->add($five)->getValue());

    }

    public function testAbsMethods()
    {

        $negFive = new MutableNumber(-5);

        $this->assertEquals('5', $negFive->abs()->getValue());
        $this->assertEquals('5', $negFive->absValue());

        $five = new MutableNumber(5);

        $this->assertEquals('5', $five->abs()->getValue());
        $this->assertEquals('5', $five->absValue());

    }

    public function testNumberState()
    {

        $negFive = new MutableNumber(-5);
        $five = new MutableNumber(5);
        $zero = Numbers::makeZero();

        $oneHalf = new MutableNumber('0.5');

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

        $two = new MutableNumber(2);
        $three = new MutableNumber(3);
        $six = new MutableNumber(6);
        $twentySeven = new MutableNumber(27);
        $thirtyOne = new MutableNumber(31);
        $fortyFive = new MutableNumber(45);
        $ninetyOne = new MutableNumber(91);
        $tenThousandSeven = new MutableNumber(10007);
        //$largeNonPrime = new MutableNumber('99799811');
        $oneHalf = new MutableNumber('0.5');

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

        $oneHalf = new MutableNumber('0.5');

        $this->assertEquals('1', $oneHalf->ceil()->getValue());
        $this->assertEquals('0', $oneHalf->floor()->getValue());

    }

    public function testRound()
    {

        $pointFive = new MutableNumber('0.5');

        $this->assertEquals('1', $pointFive->round()->getValue());

        $pointOneFive = new MutableNumber('0.15');

        $this->assertEquals('0.2', $pointOneFive->round(1)->getValue());

        $testNum = new MutableNumber('62.169797510839');

        $this->assertEquals('62.1697975108', $testNum->round(10)->getValue());

        $closeToOne = new MutableNumber('0.999999999999999');

        $this->assertEquals('1', $closeToOne->round()->getValue());
        $this->assertEquals('1', $closeToOne->round(4)->getValue());

    }

    public function testNumberOfLeadingZeros()
    {

        $num = new MutableNumber('0.00000000001');

        $this->assertEquals(10, $num->numberOfLeadingZeros());

    }

    public function testOverflow()
    {

        $intMax = new MutableNumber(PHP_INT_MAX);

        $this->assertEquals((string)PHP_INT_MAX, $intMax->add(1)->subtract(1)->getValue());

        $largeInt = new MutableNumber('99999999999999999999999999999');

        $this->assertEquals('100000000000000000000000000000', $largeInt->add(1)->getValue());

    }

    public function testPrecisionLimit()
    {

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('Precision of any number cannot be calculated beyond 2147483646 digits');

        $precisionLimit = new MutableNumber(1, 2147483647);

    }

}