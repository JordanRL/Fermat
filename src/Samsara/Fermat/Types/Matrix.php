<?php

namespace Samsara\Fermat\Types;

use ReflectionException;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Matrices;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Types\Base\Interfaces\Groups\MatrixInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Traits\Matrix\DirectAccessTrait;
use Samsara\Fermat\Types\Traits\Matrix\ShapeTrait;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableMatrix;
use Samsara\Fermat\Values\ImmutableDecimal;

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
     * @param string $mode
     */
    public function __construct(array $data, string $mode = Matrix::MODE_ROWS_INPUT)
    {

        $this->normalizeInputData($data, $mode);

    }

    /**
     * @param array $data
     * @param string $mode
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

    /**
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function getDeterminant(): ImmutableDecimal
    {
        if (!$this->isSquare()) {
            throw new IncompatibleObjectState(
                'Only matrices which are square have determinants.'
            );
        }

        if ($this->numRows > 2) {
            $determinant = Numbers::makeZero();

            foreach ($this->rows[0]->toArray() as $key => $value) {
                $childMatrix = $this->childMatrix(0, $key);

                $determinant = $determinant->add($value->multiply($childMatrix->getDeterminant())->multiply(SequenceProvider::nthPowerNegativeOne($key)));
            }
        } else {
            /** @var ImmutableDecimal $value */
            $determinant = $this->rows[0]->get(0)->multiply($this->rows[1]->get(1))->subtract($this->rows[1]->get(0)->multiply($this->rows[0]->get(1)));
        }

        return $determinant;
    }

    /**
     * @param MatrixInterface $value
     * @return MatrixInterface
     * @throws IntegrityConstraint
     */
    public function add(MatrixInterface $value): MatrixInterface
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
             * @var int $columnKey
             * @var NumberInterface $num
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
     * @param NumberInterface $value
     * @return MatrixInterface
     * @throws IntegrityConstraint
     */
    public function addScalarAsI(NumberInterface $value): MatrixInterface
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
     * @param NumberInterface $value
     * @return MatrixInterface
     * @throws IntegrityConstraint
     */
    public function addScalarAsJ(NumberInterface $value): MatrixInterface
    {
        $J = Matrices::onesMatrix(Matrices::IMMUTABLE_MATRIX, $this->getRowCount(), $this->getColumnCount());
        $J = $J->multiply($value);

        return $this->add($J);
    }

    /**
     * @param MatrixInterface $value
     * @return MatrixInterface
     * @throws IntegrityConstraint
     */
    public function subtract(MatrixInterface $value): MatrixInterface
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
             * @var int $columnKey
             * @var NumberInterface $num
             */
            foreach ($row->toArray() as $columnKey => $num) {
                $resultArray[$rowKey]->push($num->subtract($value->getRow($rowKey)->get($columnKey)));
            }
        }

        return $this->setValue($resultArray, self::MODE_ROWS_INPUT);
    }

    /**
     * @param NumberInterface $value
     *
     * @return MatrixInterface
     * @throws IntegrityConstraint
     */
    public function subtractScalarAsI(NumberInterface $value): MatrixInterface
    {
        $value = $value->multiply(-1);

        return $this->addScalarAsI($value);
    }

    /**
     * @param NumberInterface $value
     *
     * @return MatrixInterface
     * @throws IntegrityConstraint
     */
    public function subtractScalarAsJ(NumberInterface $value): MatrixInterface
    {
        $value = $value->multiply(-1);

        return $this->addScalarAsJ($value);
    }

    /**
     * @param $value
     *
     * @return MatrixInterface
     * @throws IntegrityConstraint
     */
    public function multiply($value): MatrixInterface
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
                for ($i = 0;$i < $value->getColumnCount();$i++) {
                    $cellVal = Numbers::makeZero();
                    /** @var NumberInterface $num */
                    foreach ($row->toArray() as $index => $num) {
                        $cellVal = $cellVal->add($num->multiply($value->getColumn($i)->get($index)));
                    }

                    $resultArray[$rowKey]->push($cellVal);
                }
            }
        } else {
            $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value);

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
     * @return MatrixInterface
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function inverseMatrix(): MatrixInterface
    {
        $determinant = $this->getDeterminant();
        $inverseDeterminant = new ImmutableFraction(Numbers::makeOne(), $determinant);

        $data = $this->rows;
        $columnCount = $this->getColumnCount();

        $newMatrixData = [];

        // TODO: Implement minors & cofactors method https://www.mathsisfun.com/algebra/matrix-inverse-minors-cofactors-adjugate.html
        for ($i = 0;$i < $columnCount;$i++) {
            for ($r = 0;$r < $columnCount;$r++) {

            }
        }

        return $this->multiply($inverseDeterminant);
    }

    public function minors()
    {



    }

    /**
     * This function returns a subset of the current matrix as a new matrix with one row and one column removed
     * from the dataset.
     *
     * @param int $excludeRow
     * @param int $excludeColumn
     * @param bool $forceNewMatrix
     *
     * @return MatrixInterface
     */
    protected function childMatrix(int $excludeRow, int $excludeColumn, bool $forceNewMatrix = false)
    {

        $newRows = [];

        for ($i = 0;$i < $this->getRowCount();$i++) {
            if ($i === $excludeRow) {
                continue;
            }

            $newRows[] = $this->getRow($i)->filterByKeys([$excludeColumn]);
        }

        return $forceNewMatrix ? new ImmutableMatrix($newRows) : $this->setValue($newRows);

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

    abstract protected function setValue(array $data, $mode = Matrix::MODE_ROWS_INPUT): MatrixInterface;

}