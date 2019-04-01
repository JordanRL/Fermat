<?php

namespace Samsara\Fermat\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Values\ImmutableNumber;

class SequenceProviderTest extends TestCase
{

    public function testNthEulerZigzag()
    {

        $fifth = SequenceProvider::nthEulerZigzag(5);

        $this->assertEquals('16', $fifth->getValue());

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('This library does not support the Euler Zigzag Sequence (OEIS: A000111) beyond E(50)');

        SequenceProvider::nthEulerZigzag(51);

    }

    public function testNthBernoulliNumber()
    {

        $zero = SequenceProvider::nthBernoulliNumber(0);

        $this->assertEquals('1', $zero->getValue());

        $one = SequenceProvider::nthBernoulliNumber(1);

        $this->assertEquals('0.5', $one->getValue());

        $three = SequenceProvider::nthBernoulliNumber(3);

        $this->assertEquals('0', $three->getValue());

        $two = SequenceProvider::nthBernoulliNumber(2);

        $this->assertEquals('0.16666', $two->truncateToPrecision(5)->getValue());

    }

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

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The nthFibonacciNumber function takes the term number as its argument; provide an integer term number');

        SequenceProvider::nthFibonacciNumber(new ImmutableNumber('0.5'));

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('A negative term number for the Fibonacci sequence was requested; provide a positive term number');

        SequenceProvider::nthFibonacciNumber(new ImmutableNumber('-1'));

    }

}