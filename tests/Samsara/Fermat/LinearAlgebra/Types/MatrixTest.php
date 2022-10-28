<?php

namespace Samsara\Fermat\LinearAlgebra\Types;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\LinearAlgebra\Matrices;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\LinearAlgebra\Values\ImmutableMatrix;
use Samsara\Fermat\Core\Types\NumberCollection;

class MatrixTest extends TestCase
{

    public function testPushRow()
    {

        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $this->assertEquals(2, $matrix->getRowCount());
        $this->assertEquals(2, $matrix->getColumnCount());

        $matrix->pushRow(new NumberCollection([
            Numbers::make(Numbers::IMMUTABLE, '1'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
        ]));

        $this->assertEquals(3, $matrix->getRowCount());
        $this->assertEquals(2, $matrix->getColumnCount());

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The provided row did not have the correct number of members.');

        $matrix->pushRow(new NumberCollection([
            Numbers::make(Numbers::IMMUTABLE, '1'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
        ]));

    }

    public function testPushColumn()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $this->assertEquals(2, $matrix->getRowCount());
        $this->assertEquals(2, $matrix->getColumnCount());

        $matrix->pushColumn(new NumberCollection([
            Numbers::make(Numbers::IMMUTABLE, '1'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
        ]));

        $this->assertEquals(2, $matrix->getRowCount());
        $this->assertEquals(3, $matrix->getColumnCount());

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The provided column did not have the correct number of members.');

        $matrix->pushColumn(new NumberCollection([
            Numbers::make(Numbers::IMMUTABLE, '1'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
        ]));
    }

    public function testSubtractScalarAsJ()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $matrix = $matrix->subtractScalarAsJ(Numbers::make(Numbers::IMMUTABLE, '1'));

        $this->assertEquals('2', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('4', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('3', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('1', $matrix->getRow(1)->get(1)->getValue());
    }

    public function testRotate()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $matrix = $matrix->rotate();

        $this->assertEquals('4', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('3', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('2', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('5', $matrix->getRow(1)->get(1)->getValue());
    }

    public function testPopRow()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $row = $matrix->popRow();

        $this->assertEquals(1, $matrix->getRowCount());
        $this->assertEquals('4', $row->get(0)->getValue());
        $this->assertEquals('2', $row->get(1)->getValue());
    }

    public function testPopColumn()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $row = $matrix->popColumn();

        $this->assertEquals(1, $matrix->getColumnCount());
        $this->assertEquals('5', $row->get(0)->getValue());
        $this->assertEquals('2', $row->get(1)->getValue());
    }

    public function testUnshiftRow()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $matrix->unshiftRow(new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]));

        $this->assertEquals(2, $matrix->getRowCount());
        $this->assertEquals('3', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('5', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('4', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('2', $matrix->getRow(1)->get(1)->getValue());

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The provided row did not have the correct number of members.');

        $matrix->unshiftRow(new NumberCollection([
            Numbers::make(Numbers::IMMUTABLE, '1'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
        ]));
    }

    public function testUnshiftColumn()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '4')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData, Matrix::MODE_COLUMNS_INPUT);

        $matrix->unshiftColumn(new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '5'), Numbers::make(Numbers::IMMUTABLE, '2')]));

        $this->assertEquals(2, $matrix->getColumnCount());
        $this->assertEquals('5', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('3', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('2', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('4', $matrix->getRow(1)->get(1)->getValue());

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('The provided column did not have the correct number of members.');

        $matrix->unshiftColumn(new NumberCollection([
            Numbers::make(Numbers::IMMUTABLE, '1'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
        ]));
    }

    public function testMultiply()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);
        $matrix2 = Matrices::onesMatrix(Matrices::IMMUTABLE_MATRIX, 2, 2);

        $matrix2 = $matrix2->multiply(2);

        $this->assertEquals('2', $matrix2->getRow(0)->get(1)->getValue());

        $matrix = $matrix->multiply($matrix2);

        $this->assertEquals(2, $matrix->getColumnCount());
        $this->assertEquals(2, $matrix->getRowCount());
        $this->assertEquals('16', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('16', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('12', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('12', $matrix->getRow(1)->get(1)->getValue());

        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3')])
        ];

        $matrix3 = new ImmutableMatrix($matrixData);

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('Attempted to multiply two matrices that do not have the needed row and column correspondence.');

        $matrix->multiply($matrix3);
    }

    public function testAdd()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);
        $matrix2 = Matrices::onesMatrix(Matrices::IMMUTABLE_MATRIX, 2, 2);

        $matrix = $matrix->add($matrix2);

        $this->assertEquals('4', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('6', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('5', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('3', $matrix->getRow(1)->get(1)->getValue());

        $matrix3 = Matrices::onesMatrix(Matrices::IMMUTABLE_MATRIX, 1, 1);

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('Attempted addition on matrices of different sizes.');

        $matrix->add($matrix3);
    }

    public function testSubtract()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);
        $matrix2 = Matrices::onesMatrix(Matrices::IMMUTABLE_MATRIX, 2, 2);

        $matrix = $matrix->subtract($matrix2);

        $this->assertEquals('2', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('4', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('3', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('1', $matrix->getRow(1)->get(1)->getValue());

        $matrix3 = Matrices::onesMatrix(Matrices::IMMUTABLE_MATRIX, 1, 1);

        $this->expectException(IntegrityConstraint::class);
        $this->expectExceptionMessage('Attempted subtraction on matrices of different sizes.');

        $matrix->subtract($matrix3);
    }

    public function testGetRow()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $row = $matrix->getRow(0);

        $this->assertEquals(2, $row->count());
        $this->assertEquals('5', $row->get(1)->getValue());
    }

    public function testAddScalarAsJ()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $matrix = $matrix->addScalarAsJ(Numbers::makeOne());

        $this->assertEquals('4', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('6', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('5', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('3', $matrix->getRow(1)->get(1)->getValue());
    }

    public function testGetRowAndColumnCount()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $this->assertEquals(2, $matrix->getColumnCount());
        $this->assertEquals(2, $matrix->getRowCount());
    }

    public function testAddScalarAsI()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);
        $matrix = $matrix->addScalarAsI(Numbers::make(Numbers::IMMUTABLE, 2));

        $this->assertEquals('5', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('5', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('4', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('4', $matrix->getRow(1)->get(1)->getValue());
    }

    public function testSubtractScalarAsI()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);
        $matrix = $matrix->subtractScalarAsI(Numbers::make(Numbers::IMMUTABLE, 2));

        $this->assertEquals('1', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('5', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('4', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('0', $matrix->getRow(1)->get(1)->getValue());
    }

    public function testIsSquare()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $this->assertTrue($matrix->isSquare());

        $matrix = $matrix->pushRow(new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]));

        $this->assertFalse($matrix->isSquare());
    }

    public function testGetDeterminant()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $this->assertEquals('-14', $matrix->getDeterminant()->getValue());

        $matrix = $matrix->pushRow(new NumberCollection([
            Numbers::make(Numbers::IMMUTABLE, '1'),
            Numbers::make(Numbers::IMMUTABLE, '6'),
        ]));

        $matrix = $matrix->pushColumn(new NumberCollection([
            Numbers::make(Numbers::IMMUTABLE, '1'),
            Numbers::make(Numbers::IMMUTABLE, '2'),
            Numbers::make(Numbers::IMMUTABLE, '3')
        ]));

        $this->assertEquals('-46', $matrix->getDeterminant()->getValue());
    }

    public function testChildMatrix()
    {
        $matrixData = [
            new NumberCollection([new ImmutableDecimal(3), new ImmutableDecimal(0), new ImmutableDecimal(2)]),
            new NumberCollection([new ImmutableDecimal(2), new ImmutableDecimal(0), new ImmutableDecimal(-2)]),
            new NumberCollection([new ImmutableDecimal(0), new ImmutableDecimal(1), new ImmutableDecimal(1)])
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $childMatrix = $matrix->childMatrix(0, 0);

        $this->assertEquals('0', $childMatrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('-2', $childMatrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('1', $childMatrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('1', $childMatrix->getRow(1)->get(1)->getValue());
    }

    public function testGetMatrixOfMinors()
    {
        $matrixData = [
            new NumberCollection([new ImmutableDecimal(3), new ImmutableDecimal(0), new ImmutableDecimal(2)]),
            new NumberCollection([new ImmutableDecimal(2), new ImmutableDecimal(0), new ImmutableDecimal(-2)]),
            new NumberCollection([new ImmutableDecimal(0), new ImmutableDecimal(1), new ImmutableDecimal(1)])
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $minorMatrix = $matrix->getMatrixOfMinors();

        $this->assertEquals('2', $minorMatrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('2', $minorMatrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('-2', $minorMatrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('3', $minorMatrix->getRow(1)->get(1)->getValue());
    }

    public function testApplyAlternatingSigns()
    {
        $matrixData = [
            new NumberCollection([new ImmutableDecimal(3), new ImmutableDecimal(0), new ImmutableDecimal(2)]),
            new NumberCollection([new ImmutableDecimal(2), new ImmutableDecimal(0), new ImmutableDecimal(-2)]),
            new NumberCollection([new ImmutableDecimal(0), new ImmutableDecimal(1), new ImmutableDecimal(1)])
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $minorMatrix = $matrix->getMatrixOfMinors();
        $cofactorMatrix = $minorMatrix->applyAlternatingSigns();

        $this->assertEquals('2', $cofactorMatrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('-2', $cofactorMatrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('2', $cofactorMatrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('3', $cofactorMatrix->getRow(1)->get(1)->getValue());
    }

    public function testGetInverseMatrix()
    {
        $matrixData = [
            new NumberCollection([new ImmutableDecimal(3), new ImmutableDecimal(0), new ImmutableDecimal(2)]),
            new NumberCollection([new ImmutableDecimal(2), new ImmutableDecimal(0), new ImmutableDecimal(-2)]),
            new NumberCollection([new ImmutableDecimal(0), new ImmutableDecimal(1), new ImmutableDecimal(1)])
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $inverseMatrix = $matrix->getInverseMatrix();

        $this->assertEquals('0.2', $inverseMatrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('0.2', $inverseMatrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('-0.2', $inverseMatrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('0.3', $inverseMatrix->getRow(1)->get(1)->getValue());

        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '1'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '1')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);

        $matrix = $matrix->getInverseMatrix();

        $this->assertEquals('-0.2', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('0.4', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('0.6', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('-0.2', $matrix->getRow(1)->get(1)->getValue());
    }

    public function testAdjoint()
    {

        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);
        $adjoint = $matrix->getAdjoint();

        $this->assertEquals('3', $adjoint->getRow(0)->get(0)->getValue());
        $this->assertEquals('4', $adjoint->getRow(0)->get(1)->getValue());
        $this->assertEquals('5', $adjoint->getRow(1)->get(0)->getValue());
        $this->assertEquals('2', $adjoint->getRow(1)->get(1)->getValue());

    }

    public function testAdjugate()
    {

        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);
        $adjugate = $matrix->getAdjugate();

        $this->assertEquals('3', $adjugate->getRow(0)->get(0)->getValue());
        $this->assertEquals('4', $adjugate->getRow(0)->get(1)->getValue());
        $this->assertEquals('5', $adjugate->getRow(1)->get(0)->getValue());
        $this->assertEquals('2', $adjugate->getRow(1)->get(1)->getValue());

    }

    public function testTranspose()
    {

        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);
        $transpose = $matrix->transpose();

        $this->assertEquals('3', $transpose->getRow(0)->get(0)->getValue());
        $this->assertEquals('4', $transpose->getRow(0)->get(1)->getValue());
        $this->assertEquals('5', $transpose->getRow(1)->get(0)->getValue());
        $this->assertEquals('2', $transpose->getRow(1)->get(1)->getValue());

    }

    public function testShiftRow()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);
        $row = $matrix->shiftRow();

        $this->assertEquals('4', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('2', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals(1, $matrix->getRowCount());
        $this->assertEquals('3', $row->get(0)->getValue());
        $this->assertEquals('5', $row->get(1)->getValue());
    }

    public function testShiftColumn()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new ImmutableMatrix($matrixData);
        $column = $matrix->shiftColumn();

        $this->assertEquals('5', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('2', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals(1, $matrix->getColumnCount());
        $this->assertEquals('3', $column->get(0)->getValue());
        $this->assertEquals('4', $column->get(1)->getValue());
    }

}
