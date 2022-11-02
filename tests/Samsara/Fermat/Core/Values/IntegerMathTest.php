<?php

namespace Samsara\Fermat\Core\Values;

use PHPUnit\Framework\TestCase;

class IntegerMathTest extends TestCase
{

    public function testPrimeFactors()
    {
        $num = new ImmutableDecimal(12);
        $factors = $num->getPrimeFactors();

        $this->assertEquals('2', $factors->get(0)->getValue());
        $this->assertEquals('2', $factors->get(1)->getValue());
        $this->assertEquals('3', $factors->get(2)->getValue());
        $this->assertEquals(3, $factors->count());


        $num = new ImmutableDecimal(94);
        $factors = $num->getPrimeFactors();

        $this->assertEquals('2', $factors->get(0)->getValue());
        $this->assertEquals('47', $factors->get(1)->getValue());
        $this->assertEquals(2, $factors->count());
    }

}
