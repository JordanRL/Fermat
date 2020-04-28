<?php

namespace Samsara\Fermat\Types;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Matrices;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Types\Base\MatrixInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableMatrix;
use Samsara\Fermat\Values\ImmutableNumber;

abstract class Matrix implements MatrixInterface
{
    const MODE_ROWS_INPUT = 'rows';
    const MODE_COLUMNS_INPUT = 'columns';

    /** @var NumberCollection[] */
    protected $rows;
    /** @var NumberCollection[] */
    protected $columns;
    /** @var int */
    protected $numRows;
    /** @var int */
    protected $numColumns;

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

    protected function normalizeInputData(array $data, string $mode)
    {
        if ($mode === Matrix::MODE_ROWS_INPUT) {
            $this->rows = $data;
            $this->columns = self::swapArrayHierarchy($data);
            $this->numRows = count($this->rows);
            $this->numColumns = count($this->columns);
        } elseif ($mode === Matrix::MODE_COLUMNS_INPUT) {
            $this->columns = $data;
            $this->rows = self::swapArrayHierarchy($data);
            $this->numRows = count($this->rows);
            $this->numColumns = count($this->columns);
        }
    }

    public function isSquare(): bool
    {
        if ($this->numRows === $this->numColumns) {
            return true;
        } else {
            return false;
        }
    }

    public function getRowCount(): int
    {
        return $this->numRows;
    }

    public function getRow(int $row): NumberCollection
    {
        return clone $this->rows[$row];
    }

    public function getColumnCount(): int
    {
        return $this->numColumns;
    }

    public function getColumn(int $column): NumberCollection
    {
        return clone $this->columns[$column];
    }

    public function getDeterminant(): ImmutableNumber
    {
        if (!$this->isSquare()) {
            throw new IncompatibleObjectState(
                'Only matrices which are square have determinants.'
            );
        }

        if ($this->numRows > 2) {
            $determinant = Numbers::makeZero();

            foreach ($this->rows[0]->toArray() as $key => $value) {
                $childMatrixData = [];

                for ($i = 1;$i < $this->numRows;$i++) {
                    $childMatrixData[$i-1] = $this->rows[$i]->filterByKeys([$key]);
                }

                $childMatrix = new ImmutableMatrix($childMatrixData);

                $determinant = $determinant->add($value->multiply($childMatrix->getDeterminant())->multiply(SequenceProvider::nthPowerNegativeOne($key)));
            }
        } else {
            /** @var ImmutableNumber $value */
            $determinant = $this->rows[0]->get(0)->multiply($this->rows[1]->get(1))->subtract($this->rows[1]->get(0)->multiply($this->rows[0]->get(1)));
        }

        return $determinant;
    }

    public function pushRow(NumberCollection $row): MatrixInterface
    {
        if ($row->count() != $this->numColumns) {
            throw new IntegrityConstraint(
                'The members of a new row must match the number of columns in a matrix.',
                'Provide a NumberCollection that has the right number of members.',
                'The provided row did not have the correct number of members. Expected '.$this->numColumns.', found '.$row->count()
            );
        }

        $this->rows[] = $row;

        foreach ($row->toArray() as $key => $value) {
            $this->columns[$key]->push($value);
        }

        $this->numRows += 1;

        return $this;
    }

    public function pushColumn(NumberCollection $column): MatrixInterface
    {
        if ($column->count() != $this->numRows) {
            throw new IntegrityConstraint(
                'The members of a new row must match the number of columns in a matrix.',
                'Provide a NumberCollection that has the right number of members.',
                'The provided row did not have the correct number of members. Expected '.$this->numColumns.', found '.$column->count()
            );
        }

        $this->columns[] = $column;

        foreach ($column->toArray() as $key => $value) {
            $this->rows[$key]->push($value);
        }

        $this->numColumns += 1;

        return $this;
    }

    public function popRow(): NumberCollection
    {
        $this->numRows -= 1;

        return array_pop($this->rows);
    }

    public function popColumn(): NumberCollection
    {
        $this->numColumns -= 1;

        return array_pop($this->columns);
    }

    public function unshiftRow(NumberCollection $row): MatrixInterface
    {
        if ($row->count() != $this->numColumns) {
            throw new IntegrityConstraint(
                'The members of a new row must match the number of columns in a matrix.',
                'Provide a NumberCollection that has the right number of members.',
                'The provided row did not have the correct number of members. Expected '.$this->numColumns.', found '.$row->count()
            );
        }

        array_unshift($this->rows, $row);

        foreach ($row->toArray() as $key => $value) {
            $this->columns[$key]->unshift($value);
        }

        $this->numRows += 1;

        return $this;
    }

    public function unshiftColumn(NumberCollection $column): MatrixInterface
    {
        if ($column->count() != $this->numRows) {
            throw new IntegrityConstraint(
                'The members of a new row must match the number of columns in a matrix.',
                'Provide a NumberCollection that has the right number of members.',
                'The provided row did not have the correct number of members. Expected '.$this->numColumns.', found '.$column->count()
            );
        }

        array_unshift($this->columns, $column);

        foreach ($column->toArray() as $key => $value) {
            $this->rows[$key]->unshift($value);
        }

        $this->numColumns += 1;

        return $this;
    }

    public function shiftRow(): NumberCollection
    {
        $this->numRows -= 1;

        return array_shift($this->rows);
    }

    public function shiftColumn(): NumberCollection
    {
        $this->numColumns -= 1;

        return array_shift($this->columns);
    }

    public function rotate(bool $clockwise = true): MatrixInterface
    {
        if ($clockwise) {
            $tempData = $this->rows;

            $tempData = array_reverse($tempData);

            return $this->setValue($tempData, Matrix::MODE_COLUMNS_INPUT);
        } else {
            $tempData = $this->columns;

            $tempData = array_reverse($tempData);

            return $this->setValue($tempData, Matrix::MODE_ROWS_INPUT);
        }
    }

    public function add(MatrixInterface $value): MatrixInterface
    {
        if ($this->getRowCount() != $value->getRowCount() || $this->getColumnCount() != $value->getColumnCount()) {
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

    public function addScalarAsJ(NumberInterface $value): MatrixInterface
    {
        $J = Matrices::onesMatrix(Matrices::IMMUTABLE_MATRIX, $this->getRowCount(), $this->getColumnCount());
        $J = $J->multiply($value);

        return $this->add($J);
    }

    public function subtract(MatrixInterface $value): MatrixInterface
    {
        if ($this->getRowCount() != $value->getRowCount() || $this->getColumnCount() != $value->getColumnCount()) {
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

    public function subtractScalarAsI(NumberInterface $value): MatrixInterface
    {
        $value = $value->multiply(-1);

        return $this->addScalarAsI($value);
    }

    public function subtractScalarAsJ(NumberInterface $value): MatrixInterface
    {
        $value = $value->multiply(-1);

        return $this->addScalarAsJ($value);
    }

    public function multiply($value): MatrixInterface
    {
        if ($value instanceof MatrixInterface) {
            if ($this->getColumnCount() != $value->getRowCount()) {
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
        $inverseDeterminant = Numbers::makeOne()->divide($determinant);

        return $this->multiply($inverseDeterminant);
    }

    /**
     * @param NumberCollection[] $data
     *
     * @return NumberCollection[]
     */
    protected static function swapArrayHierarchy(array $data)
    {
        $swappedArray = [];

        foreach ($data as $key => $value) {
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