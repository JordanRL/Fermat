<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;

/**
 * @group DecimalLegacy
 */
class MutableFractionTest extends TestCase
{

    public function testSimplify()
    {

        $sixFourths = new MutableFraction(new ImmutableDecimal(6), new ImmutableDecimal(4));

        $sixFourths->simplify();

        $this->assertEquals('3/2', $sixFourths->getValue());
        $this->assertEquals('3', $sixFourths->getNumerator()->getValue());
        $this->assertEquals('2', $sixFourths->getDenominator()->getValue());

    }

}
