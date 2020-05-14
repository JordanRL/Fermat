<?php


namespace Samsara\Fermat\Types\Traits\Matrix;


use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Types\Base\Interfaces\Groups\MatrixInterface;
use Samsara\Fermat\Types\NumberCollection;

trait DirectAccessTrait
{

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

}