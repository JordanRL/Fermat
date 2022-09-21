<?php

namespace Samsara\Fermat\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SequenceProviderTest extends TestCase
{
    /**
     * @small
     */
    public function testOddNumber()
    {

        $five = SequenceProvider::nthOddNumber(2);

        $this->assertEquals('5', $five->getValue());

        $firstFiveOdds = SequenceProvider::nthOddNumber(0, true, 5);

        $this->assertEquals(5, $firstFiveOdds->count());

        $this->assertEquals('5', $firstFiveOdds->get(2)->getValue());

    }

    /**
     * @small
     */
    public function testEvenNumber()
    {

        $four = SequenceProvider::nthEvenNumber(2);

        $this->assertEquals('4', $four->getValue());

        $firstFiveEvens = SequenceProvider::nthEvenNumber(0, true, 5);

        $this->assertEquals(5, $firstFiveEvens->count());

        $this->assertEquals('4', $firstFiveEvens->get(2)->getValue());

    }

    /**
     * @small
     */
    public function testPowNegOne()
    {

        $one = SequenceProvider::nthPowerNegativeOne(2);

        $this->assertEquals('1', $one->getValue());

        $firstFivePows = SequenceProvider::nthPowerNegativeOne(0, true, 5);

        $this->assertEquals(5, $firstFivePows->count());

        $this->assertEquals('-1', $firstFivePows->get(1)->getValue());
        $this->assertEquals('1', $firstFivePows->get(2)->getValue());
        $this->assertEquals('-1', $firstFivePows->get(3)->getValue());

    }

    /**
     * @medium
     */
    public function testNthEulerZigzag()
    {

        $fifth = SequenceProvider::nthEulerZigzag(5);

        $this->assertEquals('16', $fifth->getValue());

        $eulerZigzagCollection = SequenceProvider::nthEulerZigzag(2, true, 3);

        $this->assertEquals(3, $eulerZigzagCollection->count());
        $this->assertEquals('5', $eulerZigzagCollection->get(2)->getValue());

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('This library does not support the Euler Zigzag Sequence (OEIS: A000111) beyond E(50)');

        SequenceProvider::nthEulerZigzag(51);
    }
    /**
     *
     */
    public function testNthBernoulliNumber()
    {

        $zero = SequenceProvider::nthBernoulliNumber(0);

        $this->assertEquals('1', $zero->getValue());

        $one = SequenceProvider::nthBernoulliNumber(1);

        $this->assertEquals('-0.5', $one->getValue());

        $two = SequenceProvider::nthBernoulliNumber(2);

        $this->assertEquals('0.16666', $two->truncateToScale(5)->getValue());

        $three = SequenceProvider::nthBernoulliNumber(3);

        $this->assertEquals('0', $three->getValue());

        $four = SequenceProvider::nthBernoulliNumber(4);

        $this->assertEquals('−0.03333', $four->truncateToScale(5)->getValue());

    }
    /**
     * @medium
     */
    public function testNthFibonacciNumber()
    {

        $zero = SequenceProvider::nthFibonacciNumber(0);

        $this->assertEquals('0', $zero->getValue());

        $one = SequenceProvider::nthFibonacciNumber(1);

        $this->assertEquals('1', $one->getValue());

        $two = SequenceProvider::nthFibonacciNumber(2);

        $this->assertEquals('1', $two->getValue());

        $eight = SequenceProvider::nthFibonacciNumber(8);

        $this->assertEquals('21', $eight->getValue());


        $firstTenFibs = SequenceProvider::nthFibonacciNumber(0, true, 10);

        $this->assertEquals(10, $firstTenFibs->count());
        $this->assertEquals('21', $firstTenFibs->get(8)->getValue());

        $collectionFibs = SequenceProvider::nthFibonacciNumber(7, true, 3);

        $this->assertEquals(3, $collectionFibs->count());
        $this->assertEquals('34', $collectionFibs->get(2)->getValue());

    }
    /**
     * @medium
     */
    public function testNegativeFibonacci()
    {

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('A negative term number for the Fibonacci sequence was requested; provide a positive term number');

        SequenceProvider::nthFibonacciNumber(-1);

    }

}