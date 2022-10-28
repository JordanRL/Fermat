<?php

namespace Samsara\Fermat\Core\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @group Providers
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

        $firstFiveOdds = SequenceProvider::nthOddNumber(0, null, true, 5);
        $this->assertEquals(5, $firstFiveOdds->count());
        $this->assertEquals('5', $firstFiveOdds->get(2)->getValue());

        $phpIntMax = SequenceProvider::nthOddNumber(PHP_INT_MAX/2);
        $expected = (new ImmutableDecimal(PHP_INT_MAX))->add(2);
        $this->assertEquals($expected->getValue(), $phpIntMax->getValue());

    }

    /**
     * @small
     */
    public function testEvenNumber()
    {

        $four = SequenceProvider::nthEvenNumber(2);
        $this->assertEquals('4', $four->getValue());

        $firstFiveEvens = SequenceProvider::nthEvenNumber(0, null, true, 5);
        $this->assertEquals(5, $firstFiveEvens->count());
        $this->assertEquals('4', $firstFiveEvens->get(2)->getValue());

        $phpIntMax = SequenceProvider::nthEvenNumber(PHP_INT_MAX/2);
        $expected = (new ImmutableDecimal(PHP_INT_MAX))->add(1);
        $this->assertEquals($expected->getValue(), $phpIntMax->getValue());

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
     * @small
     */
    public function testNthEulerZigzag()
    {
        $fifth = SequenceProvider::nthEulerZigzag(5);
        $this->assertEquals('16', $fifth->getValue());

        $eulerZigzagCollection = SequenceProvider::nthEulerZigzag(2, true, 3);
        $this->assertEquals(3, $eulerZigzagCollection->count());
        $this->assertEquals('5', $eulerZigzagCollection->get(2)->getValue());

        $this->expectException(IntegrityConstraint::class);
        SequenceProvider::nthEulerZigzag(51);
    }

    /**
     * @medium
     */
    public function testNthBernoulliNumber()
    {

        $zero = SequenceProvider::nthBernoulliNumber(0);
        $this->assertEquals('1', $zero->getValue());

        $one = SequenceProvider::nthBernoulliNumber(1);
        $this->assertEquals('-0.5', $one->getValue());

        $two = SequenceProvider::nthBernoulliNumber(2);
        $this->assertEquals('0.16667', $two->getValue());

        $three = SequenceProvider::nthBernoulliNumber(3);
        $this->assertEquals('0', $three->getValue());

        $four = SequenceProvider::nthBernoulliNumber(4);
        $this->assertEquals('-0.03333', $four->getValue());

        $six = SequenceProvider::nthBernoulliNumber(6);
        $this->assertEquals('0.02381', $six->getValue());

        $eighteen = SequenceProvider::nthBernoulliNumber(18);
        $this->assertEquals('54.97118', $eighteen->getValue());

        $eighteen = SequenceProvider::nthBernoulliNumber(18, 20);
        $this->assertEquals('54.97117794486215538847', $eighteen->getValue());

        $twenty = SequenceProvider::nthBernoulliNumber(20);
        $this->assertEquals('-529.12424', $twenty->getValue());

        $twentyTwo = SequenceProvider::nthBernoulliNumber(22);
        $this->assertEquals('6192.12319', $twentyTwo->getValue());

    }

    /**
     * @small
     */
    public function testNthPrimeNumbers()
    {
        $five = SequenceProvider::nthPrimeNumbers(5);
        $this->assertEquals(5, $five->count());
        $this->assertEquals('11', $five->get(4)->getValue());
    }

    /**
     * @small
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
     * @small
     */
    public function testNegativeFibonacci()
    {

        $this->expectException(IntegrityConstraint::class);
        SequenceProvider::nthFibonacciNumber(-1);

    }

    /**
     * @small
     */
    public function testNegativeFibonacciPair()
    {

        $this->expectException(IntegrityConstraint::class);
        SequenceProvider::nthFibonacciPair(-1);

    }

    /**
     * @small
     */
    public function testNthLucasNumber()
    {

        $lucas = SequenceProvider::nthLucasNumber(0);
        $this->assertEquals('2', $lucas->getValue());

        $lucas = SequenceProvider::nthLucasNumber(1);
        $this->assertEquals('1', $lucas->getValue());

        $lucas = SequenceProvider::nthLucasNumber(8);
        $this->assertEquals('47', $lucas->getValue());

    }

    /**
     * @small
     */
    public function testNegativeLucasNumber()
    {

        $this->expectException(IntegrityConstraint::class);
        SequenceProvider::nthLucasNumber(-1);

    }

    /**
     * @small
     */
    public function testNthTriangularNumber()
    {

        $triangularNumber = SequenceProvider::nthTriangularNumber(0);
        $this->assertEquals('0', $triangularNumber->getValue());

        $triangularNumber = SequenceProvider::nthTriangularNumber(1);
        $this->assertEquals('1', $triangularNumber->getValue());

        $triangularNumber = SequenceProvider::nthTriangularNumber(8);
        $this->assertEquals('36', $triangularNumber->getValue());

    }

    /**
     * @small
     */
    public function testNegativeTriangularNumber()
    {

        $this->expectException(IntegrityConstraint::class);
        SequenceProvider::nthTriangularNumber(-1);

    }

}