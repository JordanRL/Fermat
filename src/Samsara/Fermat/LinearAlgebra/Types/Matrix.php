<?php

namespace Samsara\Fermat\LinearAlgebra\Types;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\SequenceProvider;
use Samsara\Fermat\Core\Types\Base\Number;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Types\NumberCollection;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\LinearAlgebra\Matrices;
use Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\MatrixInterface;
use Samsara\Fermat\LinearAlgebra\Types\Traits\Matrix\DirectAccessTrait;
use Samsara\Fermat\LinearAlgebra\Types\Traits\Matrix\ShapeTrait;

/**
 * @package Samsara\Fermat\LinearAlgebra
 */
abstract class Matrix implements MatrixInterface
{
    public const MODE_ROWS_INPUT = 'rows';
    public const MODE_COLUMNS_INPUT = 'columns';

    use ShapeTrait;
    use DirectAccessTrait;

    /**
     * Matrix constructor. The array of number collections can be an array of rows, or an array of columns. Default is rows.
     *
     * @param NumberCollection[] $data
     * @param string             $mode
     */
    final public function __construct(array $data, string $mode = Matrix::MODE_ROWS_INPUT)
    {

        $this->normalizeInputData($data, $mode);

    }

    /**
     * Calculates the determinant of the matrix.
     *
     * @return ImmutableDecimal The determinant of the matrix.
     *
     * @throws IncompatibleObjectState If the matrix is not square.
     *     Use the `isSquare()` method to check the shape of the matrix before calculating the determinant.
     */
    public function getDeterminant(): ImmutableDecimal
    {
        if (!$this->isSquare()) {
            throw new IncompatibleObjectState(
                'Only matrices which are square have determinants.',
                'Check for matrix shape before getting the determinant'
            );
        }

        if ($this->numRows > 2) {
            $determinant = Numbers::makeZero();

            foreach ($this->rows[0]->toArray() as $key => $value) {
                $childMatrix = $this->childMatrix(0, $key);

                $determinant = $determinant->add($value->multiply($childMatrix->getDeterminant())->multiply(SequenceProvider::nthPowerNegativeOne($key)));
            }
        } else {
            $determinant = $this->rows[0]->get(0)->multiply($this->rows[1]->get(1))->subtract($this->rows[1]->get(0)->multiply($this->rows[0]->get(1)));
        }

        return $determinant;
    }

    /**
     * Calculates the inverse matrix of the current matrix.
     *
     * @return static The inverse matrix of the current matrix.
     */
    public function getInverseMatrix(): static
    {
        $determinant = $this->getDeterminant();
        $inverseDeterminant = new ImmutableFraction(Numbers::makeOne(), $determinant);

        // TODO: Implement minors & cofactors method https://www.mathsisfun.com/algebra/matrix-inverse-minors-cofactors-adjugate.html
        if ($this->getRowCount() > 2) {
            $minors = $this->getMatrixOfMinors();
            $cofactors = $minors->applyAlternatingSigns();
            $cofactorsAdjoint = $cofactors->getAdjoint();
        } else {
            $cofactorsAdjoint = $this->applyAlternatingSigns();
        }

        return $cofactorsAdjoint->multiply($inverseDeterminant);
    }

    /**
     * Returns a new collection that contains the matrix of minors for each element in the current collection.
     *
     * The matrix of minors is obtained by creating a new collection where each element is the determinant of the minor of the element in the current collection.
     * A minor is obtained by removing the row and column that the element is in, and calculating the determinant of the resulting submatrix.
     *
     * @return static A new collection containing the matrix of minors.
     */
    public function getMatrixOfMinors(): static
    {
        $fn = function (Decimal|Fraction|ComplexNumber $value, int $rowIndex, int $columnIndex) {
            $minorOf = $this->childMatrix($rowIndex, $columnIndex, true);
            return $minorOf->getDeterminant();
        };

        return $this->mapFuction($fn);
    }

    /**
     * Adds a MatrixInterface object to the current matrix and returns a new matrix with the sum.
     *
     * @param MatrixInterface $value The matrix to be added. The matrix must have the same size as the current matrix.
     *
     * @return static A new matrix containing the sum of the current matrix and the given matrix.
     *
     * @throws IntegrityConstraint If the matrices have different sizes.
     */
    public function add(MatrixInterface $value): static
    {
        if ($this->getRowCount() !== $value->getRowCount() || $this->getColumnCount() !== $value->getColumnCount()) {
            throw new IntegrityConstraint(
                'Matrices must be the same size in order to be added.',
                'Only add two matrices if they are the same size.',
                'Attempted addition on matrices of different sizes.'
            );
        }

        $resultArray = [];

        foreach ($this->rows as $rowKey => $row) {
            $resultArray[$rowKey] = new NumberCollection();
            /**
             * @var int     $columnKey
             * @var Decimal $num
             */
            foreach ($row->toArray() as $columnKey => $num) {
                $resultArray[$rowKey]->push($num->add($value->getRow($rowKey)->get($columnKey)));
            }
        }

        return $this->setValue($resultArray, self::MODE_ROWS_INPUT);
    }

    /**
     * This function takes an input scalar value and multiplies an identity matrix by that scalar, then does matrix
     * addition with the resulting matrix.
     *
     * @param Number $value
     *
     * @return static
     * @throws IntegrityConstraint
     */
    public function addScalarAsI(Number $value): static
    {
        if (!$this->isSquare()) {
            throw new IntegrityConstraint(
                'Can only add scalar as scaled identity matrix if the matrix is square.',
                'Add the scalar as scaled ones matrix, or construct a matrix manually to add.',
                'Cannot add a scalar as scaled identity matrix when the original matrix is not square.'
            );
        }

        $I = Matrices::identityMatrix(Matrices::IMMUTABLE_MATRIX, $this->getRowCount());
        $I = $I->multiply($value);

        return $this->add($I);
    }

    /**
     * This function takes a scalar input value and adds that value to each position in the matrix directly.
     *
     * @param Number $value
     *
     * @return static
     * @throws IntegrityConstraint
     */
    public function addScalarAsJ(Number $value): static
    {
        $J = Matrices::onesMatrix(Matrices::IMMUTABLE_MATRIX, $this->getRowCount(), $this->getColumnCount());
        $J = $J->multiply($value);

        return $this->add($J);
    }

    /**
     * @return static
     * @throws IntegrityConstraint
     */
    public function applyAlternatingSigns(): static
    {
        $fn = function (Decimal|Fraction|ComplexNumber $value, int $rowIndex, int $columnIndex) {
            if (($rowIndex + $columnIndex) % 2 == 1) {
                return $value->multiply(-1);
            }

            return $value;
        };

        return $this->mapFuction($fn);
    }

    /**
     * This function returns a subset of the current matrix as a new matrix with one row and one column removed
     * from the dataset.
     *
     * @param int  $excludeRow
     * @param int  $excludeColumn
     * @param bool $forceNewMatrix
     *
     * @return static
     */
    public function childMatrix(int $excludeRow, int $excludeColumn, bool $forceNewMatrix = false): static
    {

        $newRows = [];

        for ($i = 0; $i < $this->getRowCount(); $i++) {
            if ($i === $excludeRow) {
                continue;
            }

            $newRows[] = $this->getRow($i)->filterByKeys([$excludeColumn]);
        }

        return $forceNewMatrix ? new static($newRows) : $this->setValue($newRows);

    }

    /**
     * Applies a given function to each element in the collection and returns a new collection with the results.
     *
     * @param callable $fn The function to apply to each element. The function should accept three parameters:
     *     the value of the element, the index of the row, and the index of the column.
     *
     * @return static A new collection with the results of the function applied to each element.
     */
    public function mapFuction(callable $fn): static
    {
        $rows = [];
        foreach ($this->rows as $rowIndex => $row) {
            $rowValues = new NumberCollection();
            foreach ($row as $columnIndex => $value) {
                $newValue = $fn($value, $rowIndex, $columnIndex);
                if (is_null($newValue)) {
                    continue;
                }
                $rowValues->push($newValue);
            }
            if ($rowValues->count() == 0) {
                continue;
            }
            $rows[] = $rowValues;
        }

        return $this->setValue($rows);
    }

    /**
     * @param $value
     *
     * @return static
     * @throws IntegrityConstraint
     */
    public function multiply($value): static
    {
        if ($value instanceof MatrixInterface) {
            if ($this->getColumnCount() !== $value->getRowCount()) {
                throw new IntegrityConstraint(
                    'The columns of matrix A must equal the columns of matrix B.',
                    'Ensure that compatible matrices are multiplied.',
                    'Attempted to multiply two matrices that do not have the needed row and column correspondence.'
                );
            }

            $resultArray = [];

            foreach ($this->rows as $rowKey => $row) {
                $resultArray[$rowKey] = new NumberCollection();
                for ($i = 0; $i < $value->getColumnCount(); $i++) {
                    $cellVal = Numbers::makeZero();
                    /** @var Decimal $num */
                    foreach ($row->toArray() as $index => $num) {
                        $cellVal = $cellVal->add($num->multiply($value->getColumn($i)->get($index)));
                    }

                    $resultArray[$rowKey]->push($cellVal);
                }
            }
        } else {
            $value = $value instanceof Fraction ? $value->asDecimal() : Numbers::makeOrDont(Numbers::IMMUTABLE, $value);

            $resultArray = [];

            foreach ($this->rows as $row) {
                $newRow = clone $row;
                $newRow->multiply($value);

                $resultArray[] = $newRow;
            }
        }

        return $this->setValue($resultArray, self::MODE_ROWS_INPUT);
    }

    /**
     * Subtract a matrix from the current matrix.
     *
     * @param MatrixInterface $value The matrix to subtract from the current matrix.
     *
     * @return static The resulting matrix after subtraction.
     *
     * @throws IntegrityConstraint If the matrices are not the same size.
     */
    public function subtract(MatrixInterface $value): static
    {
        if ($this->getRowCount() !== $value->getRowCount() || $this->getColumnCount() !== $value->getColumnCount()) {
            throw new IntegrityConstraint(
                'Matrices must be the same size in order to be subtracted.',
                'Only subtract two matrices if they are the same size.',
                'Attempted subtraction on matrices of different sizes.'
            );
        }

        $resultArray = [];

        foreach ($this->rows as $rowKey => $row) {
            $resultArray[$rowKey] = new NumberCollection();
            /**
             * @var int     $columnKey
             * @var Decimal $num
             */
            foreach ($row->toArray() as $columnKey => $num) {
                $resultArray[$rowKey]->push($num->subtract($value->getRow($rowKey)->get($columnKey)));
            }
        }

        return $this->setValue($resultArray, self::MODE_ROWS_INPUT);
    }

    /**
     * Subtract a scalar value from each element of the collection.
     *
     * @param Decimal $value The scalar value to subtract.
     *
     * @return static Returns a new instance of the collection with the scalar value subtracted from each element.
     */
    public function subtractScalarAsI(Decimal $value): static
    {
        $value = $value->multiply(-1);

        return $this->addScalarAsI($value);
    }

    /**
     * Subtract a scalar value from each element in the collection.
     *
     * @param Decimal $value The scalar value to subtract.
     *
     * @return static A new instance of the collection with the scalar subtracted from each element.
     */
    public function subtractScalarAsJ(Decimal $value): static
    {
        $value = $value->multiply(-1);

        return $this->addScalarAsJ($value);
    }

    /**
     * This function takes an input of rows or columns and returns the dataset formatted in the opposite type
     * of input.
     *
     * @param NumberCollection[] $data
     *
     * @return NumberCollection[]
     */
    protected static function swapArrayHierarchy(array $data): array
    {
        $swappedArray = [];

        foreach ($data as $value) {
            foreach ($value->toArray() as $subKey => $subValue) {
                if (!isset($swappedArray[$subKey])) {
                    $swappedArray[$subKey] = new NumberCollection();
                }

                $swappedArray[$subKey]->push($subValue);
            }
        }

        return $swappedArray;
    }

    /**
     * Normalize the input data based on the given mode.
     *
     * @param array  $data The input data to be normalized.
     * @param string $mode The mode for normalizing the data. Possible values are 'MODE_ROWS_INPUT' and 'MODE_COLUMNS_INPUT'.
     *
     * @return void
     */
    protected function normalizeInputData(array $data, string $mode): void
    {
        if ($mode === self::MODE_ROWS_INPUT) {
            $this->rows = $data;
            $this->columns = self::swapArrayHierarchy($data);
            $this->numRows = count($this->rows);
            $this->numColumns = count($this->columns);
        } elseif ($mode === self::MODE_COLUMNS_INPUT) {
            $this->columns = $data;
            $this->rows = self::swapArrayHierarchy($data);
            $this->numRows = count($this->rows);
            $this->numColumns = count($this->columns);
        }
    }

    abstract protected function setValue(array $data, $mode = Matrix::MODE_ROWS_INPUT): static;

}