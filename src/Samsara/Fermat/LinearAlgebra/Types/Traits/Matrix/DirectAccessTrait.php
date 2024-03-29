<?php


namespace Samsara\Fermat\LinearAlgebra\Types\Traits\Matrix;


use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Types\NumberCollection;
use Samsara\Fermat\LinearAlgebra\Types\Matrix;

/**
 * @package Samsara\Fermat\LinearAlgebra
 */
trait DirectAccessTrait
{

    /**
     * @return NumberCollection
     */
    public function popColumn(): NumberCollection
    {
        --$this->numColumns;

        return array_pop($this->columns);
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
     * @param NumberCollection $column
     *
     * @return static
     * @throws IntegrityConstraint
     */
    public function pushColumn(NumberCollection $column): static
    {
        if ($column->count() !== $this->numRows) {
            throw new IntegrityConstraint(
                'The members of a new column must match the number of rows in a matrix.',
                'Provide a NumberCollection that has the right number of members.',
                'The provided column did not have the correct number of members. Expected ' . $this->numColumns . ', found ' . $column->count()
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
     * @param NumberCollection $row
     *
     * @return static
     * @throws IntegrityConstraint
     */
    public function pushRow(NumberCollection $row): static
    {
        if ($row->count() !== $this->numColumns) {
            throw new IntegrityConstraint(
                'The members of a new row must match the number of columns in a matrix.',
                'Provide a NumberCollection that has the right number of members.',
                'The provided row did not have the correct number of members. Expected ' . $this->numColumns . ', found ' . $row->count()
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
     * @return NumberCollection
     */
    public function shiftColumn(): NumberCollection
    {
        $column = array_shift($this->columns);

        $this->normalizeInputData($this->columns, Matrix::MODE_COLUMNS_INPUT);

        return $column;
    }

    /**
     * @return NumberCollection
     */
    public function shiftRow(): NumberCollection
    {
        $row = array_shift($this->rows);

        $this->normalizeInputData($this->rows, Matrix::MODE_ROWS_INPUT);

        return $row;
    }

    /**
     * @param NumberCollection $column
     *
     * @return static
     * @throws IntegrityConstraint
     */
    public function unshiftColumn(NumberCollection $column): static
    {
        if ($column->count() !== $this->numRows) {
            throw new IntegrityConstraint(
                'The members of a new column must match the number of rows in a matrix.',
                'Provide a NumberCollection that has the right number of members.',
                'The provided column did not have the correct number of members. Expected ' . $this->numColumns . ', found ' . $column->count()
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
     * @param NumberCollection $row
     *
     * @return static
     * @throws IntegrityConstraint
     */
    public function unshiftRow(NumberCollection $row): static
    {
        if ($row->count() !== $this->numColumns) {
            throw new IntegrityConstraint(
                'The members of a new row must match the number of columns in a matrix.',
                'Provide a NumberCollection that has the right number of members.',
                'The provided row did not have the correct number of members. Expected ' . $this->numColumns . ', found ' . $row->count()
            );
        }

        array_unshift($this->rows, $row);

        foreach ($row->toArray() as $key => $value) {
            $this->columns[$key]->unshift($value);
        }

        ++$this->numRows;

        return $this;
    }

    abstract protected function normalizeInputData(array $data, string $mode): void;

}