<?php

namespace Samsara\Fermat;

use PHPUnit\Framework\TestCase;

class MatricesTest extends TestCase
{

    /**
     * @small
     */
    public function testZeroMatrix()
    {
        $matrix1 = Matrices::zeroMatrix(Matrices::IMMUTABLE_MATRIX, 2, 2);

        $this->assertEquals('0', $matrix1->getRow(0)->get(0)->getValue());
        $this->assertEquals('0', $matrix1->getRow(0)->get(1)->getValue());
        $this->assertEquals('0', $matrix1->getRow(1)->get(0)->getValue());
        $this->assertEquals('0', $matrix1->getRow(1)->get(1)->getValue());

        $this->assertInstanceOf(Matrices::IMMUTABLE_MATRIX, $matrix1);

        $matrix2 = Matrices::zeroMatrix(Matrices::MUTABLE_MATRIX, 2, 2);

        $this->assertEquals('0', $matrix2->getRow(0)->get(0)->getValue());
        $this->assertEquals('0', $matrix2->getRow(0)->get(1)->getValue());
        $this->assertEquals('0', $matrix2->getRow(1)->get(0)->getValue());
        $this->assertEquals('0', $matrix2->getRow(1)->get(1)->getValue());

        $this->assertInstanceOf(Matrices::MUTABLE_MATRIX, $matrix2);
    }

    /**
     * @small
     */
    public function testIdentityMatrix()
    {
        $matrix1 = Matrices::identityMatrix(Matrices::IMMUTABLE_MATRIX, 2);

        $this->assertEquals('1', $matrix1->getRow(0)->get(0)->getValue());
        $this->assertEquals('0', $matrix1->getRow(0)->get(1)->getValue());
        $this->assertEquals('0', $matrix1->getRow(1)->get(0)->getValue());
        $this->assertEquals('1', $matrix1->getRow(1)->get(1)->getValue());

        $this->assertInstanceOf(Matrices::IMMUTABLE_MATRIX, $matrix1);

        $matrix2 = Matrices::identityMatrix(Matrices::MUTABLE_MATRIX, 2);

        $this->assertEquals('1', $matrix2->getRow(0)->get(0)->getValue());
        $this->assertEquals('0', $matrix2->getRow(0)->get(1)->getValue());
        $this->assertEquals('0', $matrix2->getRow(1)->get(0)->getValue());
        $this->assertEquals('1', $matrix2->getRow(1)->get(1)->getValue());

        $this->assertInstanceOf(Matrices::MUTABLE_MATRIX, $matrix2);
    }

    /**
     * @small
     */
    public function testCofactorMatrix()
    {
        $matrix1 = Matrices::cofactorMatrix(Matrices::IMMUTABLE_MATRIX, 3);

        $this->assertEquals('1', $matrix1->getRow(0)->get(0)->getValue());
        $this->assertEquals('-1', $matrix1->getRow(0)->get(1)->getValue());
        $this->assertEquals('1', $matrix1->getRow(0)->get(2)->getValue());
        $this->assertEquals('-1', $matrix1->getRow(1)->get(0)->getValue());
        $this->assertEquals('1', $matrix1->getRow(1)->get(1)->getValue());
        $this->assertEquals('-1', $matrix1->getRow(1)->get(2)->getValue());

        $this->assertInstanceOf(Matrices::IMMUTABLE_MATRIX, $matrix1);

        $matrix2 = Matrices::cofactorMatrix(Matrices::MUTABLE_MATRIX, 3);

        $this->assertEquals('1', $matrix2->getRow(0)->get(0)->getValue());
        $this->assertEquals('-1', $matrix2->getRow(0)->get(1)->getValue());
        $this->assertEquals('1', $matrix2->getRow(0)->get(2)->getValue());
        $this->assertEquals('-1', $matrix2->getRow(1)->get(0)->getValue());
        $this->assertEquals('1', $matrix2->getRow(1)->get(1)->getValue());
        $this->assertEquals('-1', $matrix2->getRow(1)->get(2)->getValue());

        $this->assertInstanceOf(Matrices::MUTABLE_MATRIX, $matrix2);
    }

    /**
     * @small
     */
    public function testOnesMatrix()
    {
        $matrix1 = Matrices::onesMatrix(Matrices::IMMUTABLE_MATRIX, 2, 2);

        $this->assertEquals('1', $matrix1->getRow(0)->get(0)->getValue());
        $this->assertEquals('1', $matrix1->getRow(0)->get(1)->getValue());
        $this->assertEquals('1', $matrix1->getRow(1)->get(0)->getValue());
        $this->assertEquals('1', $matrix1->getRow(1)->get(1)->getValue());

        $this->assertInstanceOf(Matrices::IMMUTABLE_MATRIX, $matrix1);

        $matrix2 = Matrices::onesMatrix(Matrices::MUTABLE_MATRIX, 2, 2);

        $this->assertEquals('1', $matrix2->getRow(0)->get(0)->getValue());
        $this->assertEquals('1', $matrix2->getRow(0)->get(1)->getValue());
        $this->assertEquals('1', $matrix2->getRow(1)->get(0)->getValue());
        $this->assertEquals('1', $matrix2->getRow(1)->get(1)->getValue());

        $this->assertInstanceOf(Matrices::MUTABLE_MATRIX, $matrix2);
    }
}
