<?php

namespace Samsara\Fermat\Values\Algebra;

use PHPUnit\Framework\TestCase;

class PolynomialFunctionTest extends TestCase
{

    public function testEvaluateAt()
    {

        $coeff = [
            0 => 1,
            1 => 0,
            2 => 2
        ];

        $polynomial = new PolynomialFunction($coeff);

        $this->assertEquals('9', $polynomial->evaluateAt(2)->getValue());
        $this->assertEquals('19', $polynomial->evaluateAt(3)->getValue());
        
        $coeff = [
            0 => 5,
            1 => 3
        ];

        $polynomial = new PolynomialFunction($coeff);

        $this->assertEquals('11', $polynomial->evaluateAt(2)->getValue());
        $this->assertEquals('14', $polynomial->evaluateAt(3)->getValue());

    }

}