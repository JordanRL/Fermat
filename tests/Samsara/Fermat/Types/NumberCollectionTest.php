<?php

namespace Samsara\Fermat\Types;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\ImmutableDecimal;

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

    public function testDistributions()
    {

        $collection = new NumberCollection([1, 2, 3, 4]);

        $this->expectException(MissingPackage::class);
        $collection->makeNormalDistribution();

        $this->expectException(MissingPackage::class);
        $collection->makePoissonDistribution();

        $this->expectException(MissingPackage::class);
        $collection->makeExponentialDistribution();

        //$this->assertEquals('0.6726', $normal->percentAboveX(2)->truncate(4)->getValue());

    }

}