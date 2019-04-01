<?php

namespace Samsara\Fermat\Values\Algebra;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;

class PolynomialFunctionTest extends TestCase
{

    public function testEvaluateAt()
    {

        $coeff = [
            0 => 1,
            1 => 0,
            2 => 2
        ];

        $shape = [
            0 => '1',
            2 => '2'
        ];

        $polynomial = new PolynomialFunction($coeff);

        $this->assertEquals($shape, $polynomial->describeShape());
        $this->assertEquals('9', $polynomial->evaluateAt(2)->getValue());
        $this->assertEquals('19', $polynomial->evaluateAt(3)->getValue());
        
        $coeff = [
            0 => 5,
            1 => 3
        ];

        $shape = [
            0 => '5',
            1 => '3'
        ];

        $polynomial = new PolynomialFunction($coeff);

        $this->assertEquals($shape, $polynomial->describeShape());
        $this->assertEquals('11', $polynomial->evaluateAt(2)->getValue());
        $this->assertEquals('14', $polynomial->evaluateAt(3)->getValue());

    }

    public function testDerivative()
    {

        $coeff = [
            0 => 1,
            2 => 2
        ];

        $polynomial = new PolynomialFunction($coeff);

        $this->assertEquals('9', $polynomial->evaluateAt(2)->getValue());

        /** @var PolynomialFunction $derivative */
        $derivative = $polynomial->derivativeExpression();

        $shape = [
            1 => '4'
        ];

        $this->assertEquals($shape, $derivative->describeShape());
        $this->assertEquals('8', $derivative->evaluateAt(2)->getValue());

    }

    public function testIntegral()
    {

        $coeff = [
            0 => 1,
            2 => 3
        ];

        $polynomial = new PolynomialFunction($coeff);

        $this->assertEquals('13', $polynomial->evaluateAt(2)->getValue());

        $shape = [
            1 => '1',
            3 => '1'
        ];

        /** @var PolynomialFunction $integral */
        $integral = $polynomial->integralExpression();

        $this->assertEquals($shape, $integral->describeShape());
        $this->assertEquals('10', $integral->evaluateAt(2)->getValue());

        $shape = [
            0 => '5',
            1 => '1',
            3 => '1'
        ];

        $integral = $polynomial->integralExpression(5);

        $this->assertEquals($shape, $integral->describeShape());
        $this->assertEquals('15', $integral->evaluateAt(2)->getValue());

    }

    public function testBadConstructor()
    {

        $coeff = [
            'string' => 1,
            2 => 2
        ];

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The key string was found in the $coefficients array; an integer was expected');

        $polynomial = new PolynomialFunction($coeff);

    }

}