<?php

namespace Samsara\Fermat\Core\Types;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Stats\Distribution\Continuous\Exponential;
use Samsara\Fermat\Stats\Distribution\Discrete\Poisson;

/**
 * @group Types
 */
class NumberCollectionTest extends TestCase
{

    public function testCollect()
    {

        $collection1 = new NumberCollection([2, 4, 6, 8]);

        $this->assertEquals('2', $collection1->get(0)->getValue());

        $collection2 = new NumberCollection();

        $collection2->collect([1, 3, 5, 7]);

        $this->assertEquals('1', $collection2->get(0)->getValue());

        $this->expectException(IntegrityConstraint::class);

        $collection1->collect([1, 3, 4, 7]);

    }

    public function testChanges()
    {

        $collection = new NumberCollection();

        $this->expectException(\OutOfRangeException::class);

        $collection->get(0);

        $collection->push(new ImmutableDecimal(5));

        $this->assertEquals('5', $collection->get(0)->getValue());

        $collection->push(new ImmutableDecimal(8));

        $this->assertEquals('8', $collection->get(1)->getValue());

        $this->assertEquals('5', $collection->shift()->getValue());

        $collection->unshift(new ImmutableDecimal(11));

        $this->assertEquals('8', $collection->pop()->getValue());

        $this->assertEquals('11', $collection->get(0)->getValue());

    }

    public function testSortAndReverse()
    {

        $collection = new NumberCollection([3, 2, 5, 1]);

        $collection->sort();

        $this->assertEquals('5', $collection->get(3)->getValue());
        $this->assertEquals('1', $collection->get(0)->getValue());

        $collection->reverse();

        $this->assertEquals('5', $collection->get(0)->getValue());
        $this->assertEquals('1', $collection->get(3)->getValue());

    }

    public function testSumAndMean()
    {

        $collection = new NumberCollection([2, 3, 4, 5]);

        $this->assertEquals('3.5', $collection->mean()->getValue());
        $this->assertEquals('14', $collection->sum()->getValue());

    }

    public function testArithmetic()
    {

        $collection = new NumberCollection([1, 2, 3, 4]);

        $collection->add(1);

        $this->assertEquals('2', $collection->get(0)->getValue());

        $collection->subtract(1);

        $this->assertEquals('1', $collection->get(0)->getValue());

        $collection->multiply(2);

        $this->assertEquals('2', $collection->get(0)->getValue());

        $collection->divide(2);

        $this->assertEquals('1', $collection->get(0)->getValue());

        $collection->pow(2);

        $this->assertEquals('1', $collection->get(0)->getValue());
        $this->assertEquals('4', $collection->get(1)->getValue());

        $collection->exp();

        $this->assertEquals(Numbers::E, $collection->get(0)->getValue());

        $collection = new NumberCollection([1, 2, 3, 4]);
        $collection->exp(2);

        $this->assertEquals('2', $collection->get(0)->getValue());
        $this->assertEquals('4', $collection->get(1)->getValue());

    }

    public function testDistributions1()
    {

        $collection = new NumberCollection([1, 2, 3, 4]);

        $normal = $collection->makeNormalDistribution();

        $this->assertEquals('2.5', $normal->getMean()->getValue());
        $this->assertEquals('0.6726', $normal->percentAboveX(2)->truncate(4)->getValue());

    }

    public function testDistributions2()
    {

        $collection = new NumberCollection([1, 2, 3, 4]);

        $poisson = $collection->makePoissonDistribution();

        $this->assertEquals(Poisson::class, get_class($poisson));

    }

    public function testDistributions3()
    {

        $collection = new NumberCollection([1, 2, 3, 4]);

        $exponential = $collection->makeExponentialDistribution();

        $this->assertEquals(Exponential::class, get_class($exponential));

    }

    public function testSelectScale()
    {

        $collection = new NumberCollection([
            '0.4444444444444',
            new ImmutableDecimal(1, 15),
            '1'
        ]);

        $this->assertEquals(15, $collection->selectScale());

    }

}