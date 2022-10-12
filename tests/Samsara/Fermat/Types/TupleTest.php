<?php

namespace Samsara\Fermat\Types;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 * @group Types
 */
class TupleTest extends TestCase
{

    public function testGet()
    {

        $tuple = new Tuple(5, 10);

        $this->assertEquals('10', $tuple->get(1)->getValue());

        $this->expectException(IncompatibleObjectState::class);

        $tuple->get(5);

    }

    public function testSet()
    {

        $tuple = new Tuple(5, 10);

        $this->assertEquals('10', $tuple->get(1)->getValue());

        $tuple->set(1, new ImmutableDecimal(6));

        $this->assertEquals('6', $tuple->get(1)->getValue());

        $this->expectException(IncompatibleObjectState::class);

        $tuple->set(2, new ImmutableDecimal(10));

    }

    public function testSizeAndAll()
    {

        $tuple = new Tuple(5, 10);

        $this->assertEquals(2, $tuple->size());

        $values = $tuple->all();

        $this->assertEquals('10', $values[1]->getValue());

    }

}