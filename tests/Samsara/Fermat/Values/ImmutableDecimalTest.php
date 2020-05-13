<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\NumberCollection;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
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
        $this->assertEquals(10, $five->getPrecision());
        $this->assertEquals(10, $five->getBase());

        /** @var ImmutableDecimal $five */
        $five = $five->convertToBase(16);

        $this->assertEquals('5', $five->getValue());
        $this->assertEquals(10, $five->getPrecision());
        $this->assertEquals(16, $five->getBase());

        /** @var ImmutableDecimal $five */
        $five = $five->convertToBase(5);

        $this->assertEquals('10', $five->getValue());
        $this->assertEquals(10, $five->getPrecision());
        $this->assertEquals(5, $five->getBase());

    }
    /**
     * @group arithmetic
     * @medium
     */
    public function testAdd()
    {

        $five = new ImmutableDecimal(5);
        $ten = new ImmutableDecimal(10);
        $oneQuarter = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(4));


        $this->assertEquals('10', $five->add(5)->getValue());
        $this->assertEquals(5, $five->asInt());

        $this->assertEquals('15', $five->add($ten)->getValue());

        $this->assertEquals('5.25', $five->add($oneQuarter)->getValue());

        $sixTenths = new ImmutableDecimal('0.6');
        $fourTenths = new ImmutableDecimal('0.4');

        $this->assertEquals('1', $sixTenths->add($fourTenths)->getValue());

        $oneTenth = new ImmutableDecimal('0.1');
        $twoTenths = new ImmutableDecimal('0.2');

        $this->assertEquals('0.3', $oneTenth->add($twoTenths)->getValue());

        $tenPrecision = new ImmutableDecimal('0.0000000001');
        $elevenPrecision = new ImmutableDecimal('0.00000000001');

        $this->assertEquals('0.1000000001', $oneTenth->add($tenPrecision)->getValue());
        $this->assertEquals('0.10000000001', $oneTenth->add($elevenPrecision)->getValue());

    }
    /**
     * @group arithmetic
     * @medium
     */
    public function testSubtract()
    {

        $five = new ImmutableDecimal(5);
        $six = new ImmutableDecimal(6);

        $this->assertEquals('1', $six->subtract($five)->getValue());
        $this->assertEquals('-1', $five->subtract($six)->getValue());
        $this->assertEquals('3', $five->subtract(2)->getValue());
        $this->assertEquals('3', $five->subtract('2')->getValue());

        $oneQuarter = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(4));

        $this->assertEquals('4.75', $five->subtract($oneQuarter)->getValue());

        $sixTenths = new ImmutableDecimal('0.6');
        $fourTenths = new ImmutableDecimal('0.4');

        $this->assertEquals('0.2', $sixTenths->subtract($fourTenths)->getValue());

        $oneTenth = new ImmutableDecimal('0.1');
        $twoTenths = new ImmutableDecimal('0.2');

        $this->assertEquals('0.1', $twoTenths->subtract($oneTenth)->getValue());

        $tenPrecision = new ImmutableDecimal('0.0000000001');
        $elevenPrecision = new ImmutableDecimal('0.00000000001');

        $this->assertEquals('0.0999999999', $oneTenth->subtract($tenPrecision)->getValue());
        $this->assertEquals('0.09999999999', $oneTenth->subtract($elevenPrecision)->getValue());

    }
    /**
     * @group arithmetic
     * @medium
     */
    public function testMultiply()
    {

        $five = new ImmutableDecimal(5);
        $six = new ImmutableDecimal(6);

        $this->assertEquals('30', $six->multiply($five)->getValue());
        $this->assertEquals('30', $five->multiply($six)->getValue());
        $this->assertEquals('-5', $five->multiply(-1)->getValue());
        $this->assertEquals(-5, $five->multiply(-1)->asInt());

        $oneQuarter = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(4));

        $this->assertEquals('1.5', $six->multiply($oneQuarter)->getValue());

        $sixTenths = new ImmutableDecimal('0.6');

        $this->assertEquals('0.15', $sixTenths->multiply($oneQuarter)->getValue());

        $oneTenth = new ImmutableDecimal('0.1');
        $twoTenths = new ImmutableDecimal('0.2');

        $this->assertEquals('0.02', $twoTenths->multiply($oneTenth)->getValue());

    }
    /**
     * @group arithmetic
     * @medium
     */
    public function testDivide()
    {

        $five = new ImmutableDecimal(5);
        $ten = new ImmutableDecimal(10);

        $this->assertEquals('2', $ten->divide($five)->getValue());
        $this->assertEquals('0.5', $five->divide($ten)->getValue());

        $oneQuarter = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(4));

        $this->assertEquals('40', $ten->divide($oneQuarter)->getValue());

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

        $three->setExtensions(false);
        $five->setExtensions(false);

        $this->assertEquals('6', $three->factorial()->getValue());
        $this->assertEquals('120', $five->factorial()->getValue());

        $negativeOne = new ImmutableDecimal(-1);

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Cannot make a factorial with a number less than 1 (other than zero)');

        $negativeOne->factorial();

        $oneTenth = new ImmutableDecimal('1.1');

        $this->expectException(IncompatibleObjectState::class);
        $this->expectExceptionMessage('Can only perform a factorial on a whole number');

        $oneTenth->factorial();

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
    public function testPow()
    {

        $five = new ImmutableDecimal(5);
        $two = new ImmutableDecimal(2);

        $this->assertEquals('25', $five->pow($two)->getValue());

        $fourPointTwo = new ImmutableDecimal('4.2');
        $three = new ImmutableDecimal(3);

        $this->assertEquals('74.088', $fourPointTwo->pow($three)->getValue());

        $fortyTwoTenths = new ImmutableFraction(new ImmutableDecimal(42), new ImmutableDecimal(10));

        $this->assertEquals('100.9042061088', $three->pow($fortyTwoTenths)->getValue());

        $e = Numbers::makeE();

        $this->assertEquals('485165195.40979', $e->pow(20)->round(5)->getValue());

    }
    /**
     * @medium
     */
    public function testExp()
    {

        $one = new ImmutableDecimal(1);
        $e = Numbers::makeE();

        $this->assertTrue($one->exp()->truncate(5)->isEqual($e->truncate(5)));

    }
    /**
     * @medium
     */
    public function testLn()
    {

        $five = new ImmutableDecimal(5);

        $answer1 = $five->ln()->getValue();
        $this->assertEquals('1.6094379124', $answer1);

        $this->assertEquals('1.60943791243', $five->ln(11)->getValue());

        $fifteen = new ImmutableDecimal(15);

        $this->assertEquals('2.7080502011', $fifteen->ln(11)->getValue());

        $oneFifty = new ImmutableDecimal(150);

        $this->assertEquals('5.010635294096', $oneFifty->ln(12)->getValue());

        $largeInt = new ImmutableDecimal('1000000000000000000000000000');

        $this->assertEquals('62.16979751', $largeInt->ln(8)->getValue());

        $this->assertEquals('62.16979', $largeInt->ln(5, false)->getValue());

    }
    /**
     * @medium
     */
    public function testLog10()
    {

        $five = new ImmutableDecimal(5);

        $this->assertEquals('0.6989700043', $five->log10()->getValue());

        $this->assertEquals('0.69897000434', $five->log10(11)->getValue());

        $fifteen = new ImmutableDecimal(15);

        $this->assertEquals('1.17609125906', $fifteen->log10(11)->getValue());

        $oneFifty = new ImmutableDecimal(150);

        $this->assertEquals('2.176091259056', $oneFifty->log10(12)->getValue());

        $largeInt = new ImmutableDecimal('1000000000000000000000000000');

        $this->assertEquals('27', $largeInt->log10(8)->getValue());

        $this->assertEquals('27', $largeInt->log10(5, false)->getValue());

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

        $this->assertEquals('1.4142135623', $two->sqrt()->getValue());

        $largeInt = new ImmutableDecimal('1000000000000000000000000000');

        $this->assertEquals('31622776601683.7933199889', $largeInt->sqrt(10)->getValue());

    }
    /**
     * @medium
     */
    public function testSin()
    {
        /** @var ImmutableDecimal $pi */
        $pi = Numbers::makePi(10);
        $zero = new ImmutableDecimal(0);

        $this->assertEquals('0', $pi->sin()->getValue());
        $this->assertEquals('0', $zero->sin()->getValue());

        $four = new ImmutableDecimal(4);

        $this->assertEquals('-0.7568024953', $four->sin()->getValue());

        $largeInt = new ImmutableDecimal('1000000000000000000000000000');

        $this->assertEquals('0.718063496139118', $largeInt->sin(15)->getValue());
        $this->assertEquals('0.71806349613912', $largeInt->sin(14)->getValue());
        $this->assertEquals('0.71806349613911', $largeInt->sin(14, false)->getValue());

    }
    /**
     * @medium
     */
    public function testCos()
    {
        /** @var ImmutableDecimal $pi */
        $pi = Numbers::makePi();

        $this->assertEquals('-1', $pi->cos()->getValue());

        $four = new ImmutableDecimal(4);

        $this->assertEquals('-0.6536436209', $four->cos()->getValue());

        $largeInt = new ImmutableDecimal('1000000000000000000000000000');

        $this->assertEquals('-0.695977596990354', $largeInt->cos(15)->getValue());
        $this->assertEquals('-0.69597759699035', $largeInt->cos(14)->getValue());
        $this->assertEquals('-0.695977596990353', $largeInt->cos(15, false)->getValue());

    }
    /**
     * @medium
     */
    public function testTan()
    {

        $twoPiDivThree = Numbers::make2Pi()->divide(3);

        $this->assertEquals('-1.73205080756888', $twoPiDivThree->tan(14)->getValue());
        $this->assertEquals('-1.73205080756887', $twoPiDivThree->tan(14, false)->getValue());

        $piDivTwo = Numbers::makePi()->divide(2);

        $this->assertEquals('INF', $piDivTwo->tan()->getValue());

    }
    /**
     * @medium
     */
    public function testCot()
    {

        $five = new ImmutableDecimal(5);

        $this->assertEquals('-0.295812916', $five->cot(9)->getValue());
        $this->assertEquals('-0.295812915', $five->cot(9, false)->getValue());

        $test = new ImmutableDecimal('-0.7853981633');

        $this->assertEquals('1', $test->cot(2)->getValue());

    }
    /**
     * @medium
     */
    public function testSec()
    {

        $five = new ImmutableDecimal(5);

        $this->assertEquals('3.525320086', $five->sec(9)->getValue());
        $this->assertEquals('3.525320085', $five->sec(9, false)->getValue());

    }
    /**
     * @medium
     */
    public function testCsc()
    {

        $five = new ImmutableDecimal(5);

        $this->assertEquals('-1.042835213', $five->csc(9)->getValue());
        $this->assertEquals('-1.042835212', $five->csc(9, false)->getValue());

    }
    /**
     * @large
     */
    public function testArctan()
    {

        $five = new ImmutableDecimal(5);

        $this->assertEquals('1.373400767', $five->arctan(9)->getValue());
        $this->assertEquals('1.373400766', $five->arctan(9, false)->getValue());

        $oneHalf = new ImmutableDecimal('0.5');

        $this->assertEquals('0.463647609', $oneHalf->arctan(9, false)->getValue());

        $oneHalf = new ImmutableDecimal('-0.5');

        $this->assertEquals('-0.463647609', $oneHalf->arctan(9, false)->getValue());

        $five = new ImmutableDecimal(25);

        $this->assertEquals('1.5308176396', $five->arctan(10, false)->getValue());
        $this->assertEquals('1.5308176397', $five->arctan(10)->getValue());

    }
    /**
     * @large
     */
    public function testArcsin()
    {

        $one = new ImmutableDecimal(1);

        $this->assertEquals('1.5707963267', $one->arcsin(10, false)->getValue());
        $this->assertEquals('1.5707963268', $one->arcsin(10)->getValue());

        $zero = new ImmutableDecimal(0);

        $this->assertEquals('0', $zero->arcsin(10, false)->getValue());
        $this->assertEquals('0', $zero->arcsin(10)->getValue());

        $negOne = new ImmutableDecimal(-1);

        $this->assertEquals('-1.5707963267', $negOne->arcsin(10, false)->getValue());
        $this->assertEquals('-1.5707963268', $negOne->arcsin(10)->getValue());

        $oneHalf = new ImmutableDecimal('0.5');

        $this->assertEquals('0.5235987755', $oneHalf->arcsin(10, false)->getValue());
        $this->assertEquals('0.5235987756', $oneHalf->arcsin(10)->getValue());

        $onePointFive = new ImmutableDecimal('1.5');

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The arcsin function only has real values for inputs which have an absolute value of 1 or smaller');

        $onePointFive->arcsin();

    }
    /**
     * @large
     */
    public function testArccos()
    {

        $one = new ImmutableDecimal(1);

        $this->assertEquals('0', $one->arccos()->getValue());

        $zero = new ImmutableDecimal(0);

        $this->assertEquals('1.5707963267', $zero->arccos(10, false)->getValue());
        $this->assertEquals('1.5707963268', $zero->arccos(10)->getValue());

        $negOne = new ImmutableDecimal(-1);

        $this->assertEquals('3.1415926535', $negOne->arccos(10, false)->getValue());
        $this->assertEquals('3.1415926536', $negOne->arccos(10)->getValue());

        $oneHalf = new ImmutableDecimal('0.5');

        $this->assertEquals('1.0471975511', $oneHalf->arccos(10, false)->getValue());
        $this->assertEquals('1.0471975512', $oneHalf->arccos(10)->getValue());

        $onePointFive = new ImmutableDecimal('1.5');

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The arccos function only has real values for inputs which have an absolute value of 1 or smaller');

        $onePointFive->arccos();

    }
    /**
     * @large
     */
    public function testArccot()
    {
        $one = new ImmutableDecimal(1);

        $this->assertEquals('0.7853981633', $one->arccot(10, false)->getValue());
        $this->assertEquals('0.7853981634', $one->arccot(10)->getValue());

        $zero = new ImmutableDecimal(0);

        $this->assertEquals('1.5707963267', $zero->arccot(10, false)->getValue());
        $this->assertEquals('1.5707963268', $zero->arccot(10)->getValue());

        $negOne = new ImmutableDecimal(-1);

        $this->assertEquals('2.3561944901', $negOne->arccot(10, false)->getValue());
        $this->assertEquals('2.3561944902', $negOne->arccot(10)->getValue());

        $oneHalf = new ImmutableDecimal('0.5');

        $this->assertEquals('1.1071487177', $oneHalf->arccot(10, false)->getValue());
        $this->assertEquals('1.1071487178', $oneHalf->arccot(10)->getValue());

        $negOneHalf = new ImmutableDecimal('-0.5');

        $this->assertEquals('2.0344439357', $negOneHalf->arccot(10, false)->getValue());
        $this->assertEquals('2.0344439358', $negOneHalf->arccot(10)->getValue());

        $negPointNine = new ImmutableDecimal('-0.9');

        $this->assertEquals('2.3036114285', $negPointNine->arccot(10, false)->getValue());
        $this->assertEquals('2.3036114286', $negPointNine->arccot(10)->getValue());

        $negOnePointOne = new ImmutableDecimal('-1.1');

        $this->assertEquals('2.4037775934', $negOnePointOne->arccot(10, false)->getValue());
        $this->assertEquals('2.4037775935', $negOnePointOne->arccot(10)->getValue());

    }
    /**
     * @large
     */
    public function testArcsec()
    {

        $one = new ImmutableDecimal(1);

        $this->assertEquals('0', $one->arcsec(10, false)->getValue());

        $negOne = new ImmutableDecimal(-1);

        $this->assertEquals('3.1415926535', $negOne->arcsec(10, false)->getValue());
        $this->assertEquals('3.1415926536', $negOne->arcsec(10)->getValue());

        $oneHalf = new ImmutableDecimal('1.5');

        $this->assertEquals('0.8410686705', $oneHalf->arcsec(10, false)->getValue());
        $this->assertEquals('0.8410686706', $oneHalf->arcsec(10)->getValue());

        $oneHalf = new ImmutableDecimal('0.5');

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The arcsec function only has real values for inputs which have an absolute value greater than 1');

        $oneHalf->arcsec();

    }
    /**
     * @large
     */
    public function testArccsc()
    {

        $one = new ImmutableDecimal(1);

        $this->assertEquals('1.5707963267', $one->arccsc(10, false)->getValue());
        $this->assertEquals('1.5707963268', $one->arccsc(10)->getValue());

        $negOne = new ImmutableDecimal(-1);

        $this->assertEquals('-1.5707963267', $negOne->arccsc(10, false)->getValue());
        $this->assertEquals('-1.5707963268', $negOne->arccsc(10)->getValue());


        $oneAndAHalf = new ImmutableDecimal('1.5');

        $this->assertEquals('0.7297276562', $oneAndAHalf->arccsc(10, false)->getValue());
        $this->assertEquals('0.7297276562', $oneAndAHalf->arccsc(10)->getValue());

        $oneHalf = new ImmutableDecimal('0.5');

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The arccsc function only has real values for inputs which have an absolute value greater than 1');

        $oneHalf->arccsc();

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

        $three->setExtensions(false);
        $six->setExtensions(false);

        $this->assertEquals('3', $three->getGreatestCommonDivisor($six)->getValue());
        $this->assertEquals('3', $six->getGreatestCommonDivisor($three)->getValue());

    }
    /**
     * @medium
     */
    public function testConverts()
    {

        $five = new ImmutableDecimal(5);

        $five = $five->convertToBase(5);

        $this->assertEquals('10', $five->getValue());

        $four = new ImmutableDecimal(4);

        $this->assertEquals('14', $five->add($four)->getValue());
        $this->assertEquals('20', $five->add($five)->getValue());

    }
    /**
     * @medium
     */
    public function testAbsMethods()
    {

        $negFive = new ImmutableDecimal(-5);

        $this->assertEquals('5', $negFive->abs()->getValue());
        $this->assertEquals('5', $negFive->absValue());

        $five = new ImmutableDecimal(5);

        $this->assertEquals('5', $five->abs()->getValue());
        $this->assertEquals('5', $five->absValue());

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
        //$largeNonPrime = new ImmutableNumber('99799811');
        $oneHalf = new ImmutableDecimal('0.5');

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

        $this->assertEquals('1', $pointFive->round()->getValue());

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
    public function testPrecisionLimit()
    {

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('Precision of any number cannot be calculated beyond 2147483646 digits');

        $precisionLimit = new ImmutableDecimal(1, 2147483647);

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

        $pi = new ImmutableDecimal(Numbers::PI);

        $this->assertEquals('0', $pi->continuousModulo(Numbers::PI)->getValue());

        $twoPi = new ImmutableDecimal(Numbers::TAU);

        $twoPiPlusTwo = $twoPi->add(2);

        $this->assertEquals('2', $twoPiPlusTwo->continuousModulo(Numbers::PI)->getValue());

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
        $this->expectExceptionMessage('Cannot export number as integer because it is out of range');

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

    /**
     * @group arithmetic
     * @group complex
     * @medium
     */
    public function testImaginaryAdd()
    {

        $zero = Numbers::makeZero(5);
        $one = new ImmutableDecimal(1);
        $oneI = new ImmutableDecimal('1i');
        $two = new ImmutableDecimal(2);
        $twoI = new ImmutableDecimal('2i');

        $this->assertEquals('1+1i', $one->add($oneI)->getValue());
        $this->assertEquals('2+2i', $twoI->add($two)->getValue());

        $onePointFive = new ImmutableDecimal('1.5');
        $onePointFiveI = new ImmutableDecimal('1.5i');

        $this->assertEquals('1.5+1.5i', $onePointFive->add($onePointFiveI)->getValue());
        $this->assertEquals('1.5', $onePointFiveI->getAsBaseTenRealNumber());

        $this->assertEquals('3i', $twoI->add($oneI)->getValue());

        $this->assertEquals('1.5i', $onePointFiveI->add($zero)->getValue());
        $this->assertEquals('1.5i', $zero->add($onePointFiveI)->getValue());

    }

    /**
     * @group arithmetic
     * @group complex
     * @medium
     */
    public function testImaginarySubtract()
    {

        $zero = Numbers::makeZero(5);
        $one = new ImmutableDecimal(1);
        $oneI = new ImmutableDecimal('1i');
        $two = new ImmutableDecimal(2);
        $twoI = new ImmutableDecimal('2i');

        $this->assertEquals('1-1i', $one->subtract($oneI)->getValue());
        $this->assertEquals('-2+2i', $twoI->subtract($two)->getValue());

        $onePointFive = new ImmutableDecimal('1.5');
        $onePointFiveI = new ImmutableDecimal('1.5i');

        $this->assertEquals('1.5-1.5i', $onePointFive->subtract($onePointFiveI)->getValue());
        $this->assertEquals('1.5', $onePointFiveI->getAsBaseTenRealNumber());

        $answer = $twoI->subtract($oneI);

        $this->assertTrue($answer->isImaginary());

        $this->assertEquals('1i', $answer->getValue());

        $this->assertEquals('1.5i', $onePointFiveI->subtract($zero)->getValue());
        $this->assertEquals('-1.5i', $zero->subtract($onePointFiveI)->getValue());

    }

}
