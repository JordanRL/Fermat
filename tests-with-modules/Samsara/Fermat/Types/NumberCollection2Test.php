<?php

namespace Samsara\Fermat\Types;

use PHPUnit\Framework\TestCase;

class NumberCollection2Test extends TestCase
{

    public function testNormalDistribution()
    {

        $collection = new NumberCollection([1, 2, 3, 4]);

        $normal = $collection->makeNormalDistribution();

        $this->assertEquals('0.6726', $normal->percentAboveX(2)->truncate(4)->getValue());

    }
    
}