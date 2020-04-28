<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;

class MutableNumberTest extends TestCase
{

    public function testModulo()
    {

        $four = new MutableDecimal(4);

        $this->assertEquals('0', $four->modulo(2)->getValue());

        $five = new MutableDecimal(5);

        $this->assertEquals('1', $five->modulo(2)->getValue());

    }

    public function testContinuousModulo()
    {

        $pi = new MutableDecimal(Numbers::PI);

        $this->assertEquals('0', $pi->continuousModulo(Numbers::PI)->getValue());

        $twoPi = new MutableDecimal(Numbers::TAU);

        $twoPi->add(2);

        $this->assertEquals('2', $twoPi->continuousModulo(Numbers::PI)->getValue());

    }

}