<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Fermat\Numbers;

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

    }

    public function testFactorial()
    {

        $three = new ImmutableNumber(3);

        $this->assertEquals('6', $three->factorial()->getValue());

        $five = new ImmutableNumber(5);

        $this->assertEquals('120', $five->factorial()->getValue());

        $negativeOne = new ImmutableNumber(-1);

        $this->setExpectedException(IncompatibleObjectState::class, 'Cannot make a factorial with a number less than 1 (other than zero)');

        $negativeOne->factorial();

        $oneTenth = new ImmutableNumber('0.1');

        $this->setExpectedException(IncompatibleObjectState::class, 'Can only perform a factorial on a whole number');

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

        $this->setExpectedException(IncompatibleObjectState::class, 'Can only perform a double factorial on a whole number');

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

        $e = Numbers::makeE();

        $this->assertEquals('485165195.40979', $e->pow(20)->round(5)->getValue());

    }

    public function testLn()
    {

        $five = new ImmutableNumber(5);

        $this->assertEquals('1.6094379124341', $five->ln()->getValue());

        $this->assertEquals('1.60943791243', $five->ln(11)->getValue());

        $fifteen = new ImmutableNumber(15);

        $this->assertEquals('2.70805020110', $fifteen->ln(11)->getValue());

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

        $this->assertEquals('31622776601683.7933199889', $largeInt->sqrt()->getValue());

    }

    public function testSin()
    {
        /** @var ImmutableNumber $pi */
        $pi = Numbers::makePi();

        $this->assertEquals('0', $pi->sin()->getValue());

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

    public function testRound()
    {

        $pointFive = new ImmutableNumber('0.5');

        $this->assertEquals('1', $pointFive->round()->getValue());

        $pointOneFive = new ImmutableNumber('0.15');

        $this->assertEquals('0.2', $pointOneFive->round(1)->getValue());

        $testNum = new ImmutableNumber('62.169797510839');

        $this->assertEquals('62.1697975108', $testNum->round(10)->getValue());

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

}