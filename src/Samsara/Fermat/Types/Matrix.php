<?php

namespace Samsara\Fermat\Types;

use ReflectionException;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Matrices;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Types\Base\Interfaces\Groups\MatrixInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableMatrix;
use Samsara\Fermat\Values\ImmutableDecimal;

abstract class Matrix implements MatrixInterface
{
    public const MODE_ROWS_INPUT = 'rows';
    public const MODE_COLUMNS_INPUT = 'columns';

    /** @var NumberCollection[] */
    protected $rows;
    /** @var NumberCollection[] */
    protected $columns;
    /** @var
     *
     *
     * int
     */
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
     * @return bool
     */
    public function isSquare(): bool
    {
        return $this->getRowCount() === $this->getColumnCount();
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->numRows;
    }

    /**
     * @param int $row
     * @return NumberCollection
     */
    public function getRow(int $row): NumberCollection
    {
        return clone $this->rows[$row];
    }

    /**
     * @return int
     */
    public function getColumnCount(): int
    {
        return $this->numColumns;
    }

    /**
     * @param int $column
     * @return NumberCollection
     */
    public function getColumn(int $column): NumberCollection
    {
        return clone $this->columns[$column];
    }

    /**
     * @return MatrixInterface
     */
    public function getAdjoint(): MatrixInterface
    {
        $rows = $this->columns;

        return $this->setValue($rows);
    }

    /**
     * @return MatrixInterface
     */
    public function getAdjugate(): MatrixInterface
    {
        return $this->getAdjoint();
    }

    /**
     * @return MatrixInterface
     */
    public function transpose(): MatrixInterface
    {
        return $this->getAdjoint();
    }

    /**
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws ReflectionException
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
     * @param NumberCollection $row
     * @return $this
     * @throws IntegrityConstraint
     */
    public function pushRow(NumberCollection $row): MatrixInterface
    {
        if ($row->count() !== $this->numColumns) {
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

        ++$this->numRows;

        return $this;
    }

    /**
     * @param NumberCollection $column
     * @return $this
     * @throws IntegrityConstraint
     */
    public function pushColumn(NumberCollection $column): MatrixInterface
    {
        if ($column->count() !== $this->numRows) {
            throw new IntegrityConstraint(
                'The members of a new column must match the number of rows in a matrix.',
                'Provide a NumberCollection that has the right number of members.',
                'The provided column did not have the correct number of members. Expected '.$this->numColumns.', found '.$column->count()
            );
        }

        $this->columns[] = $column;

        foreach ($column->toArray() as $key => $value) {
            $this->rows[$key]->push($value);
        }

        ++$this->numColumns;

        return $this;
    }

    /**
     * @return NumberCollection
     */
    public function popRow(): NumberCollection
    {
        --$this->numRows;

        return array_pop($this->rows);
    }

    /**
     * @return NumberCollection
     */
    public function popColumn(): NumberCollection
    {
        --$this->numColumns;

        return array_pop($this->columns);
    }

    /**
     * @param NumberCollection $row
     * @return $this
     * @throws IntegrityConstraint
     */
    public function unshiftRow(NumberCollection $row): MatrixInterface
    {
        if ($row->count() !== $this->numColumns) {
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

        ++$this->numRows;

        return $this;
    }

    /**
     * @param NumberCollection $column
     * @return $this
     * @throws IntegrityConstraint
     */
    public function unshiftColumn(NumberCollection $column): MatrixInterface
    {
        if ($column->count() !== $this->numRows) {
            throw new IntegrityConstraint(
                'The members of a new column must match the number of rows in a matrix.',
                'Provide a NumberCollection that has the right number of members.',
                'The provided column did not have the correct number of members. Expected '.$this->numColumns.', found '.$column->count()
            );
        }

        array_unshift($this->columns, $column);

        foreach ($column->toArray() as $key => $value) {
            $this->rows[$key]->unshift($value);
        }

        ++$this->numColumns;

        return $this;
    }

    /**
     * @return NumberCollection
     */
    public function shiftRow(): NumberCollection
    {
        --$this->numRows;

        return array_shift($this->rows);
    }

    /**
     * @return NumberCollection
     */
    public function shiftColumn(): NumberCollection
    {
        --$this->numColumns;

        return array_shift($this->columns);
    }

    /**
     * @param bool $clockwise
     * @return MatrixInterface
     */
    public function rotate(bool $clockwise = true): MatrixInterface
    {
        $tempData =  $clockwise ? $this->rows :  $this->columns;
        $mode = $clockwise ? self::MODE_COLUMNS_INPUT : self::MODE_ROWS_INPUT;

        $tempData = array_reverse($tempData);

        return $this->setValue($tempData, $mode);
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
     * @throws ReflectionException
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
     * @throws ReflectionException
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
     * @param NumberCollection[] $data
     *
     * @return NumberCollection[]
     */
    protected static function swapArrayHierarchy(array $data): array
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