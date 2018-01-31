<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;

class ImmutableFractionTest extends TestCase
{

    public function testSimplify()
    {

        $sixFourths = new ImmutableFraction(new ImmutableNumber(6), new ImmutableNumber(4));

        $this->assertEquals('6/4', $sixFourths->getValue());
        $this->assertEquals('6', $sixFourths->getNumerator()->getValue());
        $this->assertEquals('4', $sixFourths->getDenominator()->getValue());

        $sixFourths = $sixFourths->simplify();
        
        $this->assertEquals('3/2', $sixFourths->getValue());
        $this->assertEquals('3', $sixFourths->getNumerator()->getValue());
        $this->assertEquals('2', $sixFourths->getDenominator()->getValue());

    }

}