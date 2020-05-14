<?php


namespace Samsara\Fermat\Types\Traits\Matrix;


use Samsara\Fermat\Types\Base\Interfaces\Groups\MatrixInterface;
use Samsara\Fermat\Types\NumberCollection;

trait ShapeTrait
{

    /** @var NumberCollection[] */
    protected $rows;
    /** @var NumberCollection[] */
    protected $columns;
    /** @var int */
    protected $numRows;
    /** @var int */
    protected $numColumns;

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

}