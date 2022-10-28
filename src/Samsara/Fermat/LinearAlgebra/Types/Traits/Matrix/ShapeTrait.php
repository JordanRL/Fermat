<?php


namespace Samsara\Fermat\LinearAlgebra\Types\Traits\Matrix;


use Samsara\Fermat\Core\Types\NumberCollection;

/**
 * @package Samsara\Fermat\LinearAlgebra
 */
trait ShapeTrait
{

    /** @var NumberCollection[] */
    protected array $columns;
    protected int $numColumns;
    protected int $numRows;
    /** @var NumberCollection[] */
    protected array $rows;

    /**
     * @return static
     */
    public function getAdjoint(): static
    {
        $rows = $this->columns;

        return $this->setValue($rows);
    }

    /**
     * @return static
     */
    public function getAdjugate(): static
    {
        return $this->getAdjoint();
    }

    /**
     * @param int $column
     *
     * @return NumberCollection
     */
    public function getColumn(int $column): NumberCollection
    {
        return clone $this->columns[$column];
    }

    /**
     * @return int
     */
    public function getColumnCount(): int
    {
        return $this->numColumns;
    }

    /**
     * @param int $row
     *
     * @return NumberCollection
     */
    public function getRow(int $row): NumberCollection
    {
        return clone $this->rows[$row];
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->numRows;
    }

    /**
     * @return bool
     */
    public function isSquare(): bool
    {
        return $this->getRowCount() === $this->getColumnCount();
    }

    /**
     * @param bool $clockwise
     *
     * @return static
     */
    public function rotate(bool $clockwise = true): static
    {
        $tempData = $clockwise ? $this->rows : $this->columns;
        $mode = $clockwise ? self::MODE_COLUMNS_INPUT : self::MODE_ROWS_INPUT;

        $tempData = array_reverse($tempData);

        return $this->setValue($tempData, $mode);
    }

    /**
     * @return static
     */
    public function transpose(): static
    {
        return $this->getAdjoint();
    }

}