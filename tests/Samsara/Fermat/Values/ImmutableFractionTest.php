<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ImmutableFractionTest extends TestCase
{
    /**
     * @medium
     */
    public function testSimplify()
    {

        $sixFourths = new ImmutableFraction(new ImmutableDecimal(6), new ImmutableDecimal(4));

        $this->assertEquals('6/4', $sixFourths->getValue());
        $this->assertEquals('6', $sixFourths->getNumerator()->getValue());
        $this->assertEquals('4', $sixFourths->getDenominator()->getValue());

        $sixFourths = $sixFourths->simplify();

        $this->assertEquals('3/2', $sixFourths->getValue());
        $this->assertEquals('3', $sixFourths->getNumerator()->getValue());
        $this->assertEquals('2', $sixFourths->getDenominator()->getValue());

    }
    /**
     * @medium
     */
    public function testSmallestCommonDenominator()
    {

        $oneThird = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(3));
        $oneHalf = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(2));

        $this->assertEquals('6', $oneThird->getSmallestCommonDenominator($oneHalf)->getValue());

        $this->assertEquals('5/6', $oneThird->add($oneHalf)->getValue());

    }
    /**
     * @medium
     */
    public function testAbsFunctions()
    {

        $negOneHalf = new ImmutableFraction(new ImmutableDecimal('-1'), new ImmutableDecimal('2'));

        $this->assertEquals('-1/2', $negOneHalf->getValue());
        $this->assertEquals('1/2', $negOneHalf->absValue());
        $this->assertEquals('1/2', $negOneHalf->abs()->getValue());

        $oneHalf = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(2));

        $this->assertEquals('1/2', $oneHalf->absValue());
        $this->assertEquals('1/2', $oneHalf->abs()->getValue());

    }
    /**
     * @medium
     */
    public function testCompare()
    {

        $oneThird = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(3));
        $oneHalf = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(2));

        $this->assertEquals(1, $oneHalf->compare($oneThird));

        $this->assertEquals(-1, $oneThird->compare($oneHalf));

        $this->assertEquals(0, $oneHalf->compare($oneHalf));

    }
    /**
     * @medium
     */
    public function testAdd()
    {

        $oneThird = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(3));
        $oneHalf = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(2));

        $this->assertEquals('5/6', $oneThird->add($oneHalf)->getValue());
        $this->assertEquals('2/3', $oneThird->add($oneThird)->getValue());

    }
    /**
     * @medium
     */
    public function testSubtract()
    {

        $oneThird = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(3));
        $oneHalf = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(2));

        $this->assertEquals('1/6', $oneHalf->subtract($oneThird)->getValue());

        $twoThirds = new ImmutableFraction(new ImmutableDecimal(2), new ImmutableDecimal(3));

        $this->assertEquals('1/3', $twoThirds->subtract($oneThird)->getValue());

    }
    /**
     * @medium
     */
    public function testMultiply()
    {

        $oneThird = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(3));
        $oneHalf = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(2));

        $this->assertEquals('1/6', $oneThird->multiply($oneHalf)->getValue());

    }
    /**
     * @medium
     */
    public function testDivide()
    {

        $oneThird = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(3));
        $oneHalf = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(2));

        $this->assertEquals('2/3', $oneThird->divide($oneHalf)->getValue());

    }

    /*
    public function testConvertBase()
    {

        $oneFifth = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(5));

        $this->assertEquals(10, $oneFifth->getBase());

        $oneFifth = $oneFifth->convertToBase(5);

        $this->assertEquals(5, $oneFifth->getBase());

        $this->assertEquals('1/10', $oneFifth->getValue());

    }
    /**/
    /**
     * @medium
     */
    public function testPow()
    {

        $oneHalf = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(2));

        $this->assertEquals('1/4', $oneHalf->pow(2)->getValue());
        $this->assertEquals('0.2176376408', $oneHalf->pow('2.2')->getValue());

    }
    /**
     * @medium
     */
    public function testSqrt()
    {

        $oneQuarter = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(4));

        $this->assertEquals('1/2', $oneQuarter->sqrt()->getValue());

        $oneHalf = new ImmutableFraction(new ImmutableDecimal(1), new ImmutableDecimal(2));

        $this->assertEquals('0.7071067812', $oneHalf->sqrt()->getValue());

    }

}