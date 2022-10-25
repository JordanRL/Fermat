<?php

namespace Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups;

use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\NumberCollection;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\LinearAlgebra
 */
interface MatrixInterface
{

    public function isSquare(): bool;

    public function getRowCount(): int;

    public function getRow(int $row): NumberCollection;

    public function getColumnCount(): int;

    public function getColumn(int $column): NumberCollection;

    public function getDeterminant(): ImmutableDecimal;

    public function pushRow(NumberCollection $row): MatrixInterface;

    public function pushColumn(NumberCollection $column): MatrixInterface;

    public function popRow(): NumberCollection;

    public function popColumn(): NumberCollection;

    public function unshiftRow(NumberCollection $row): MatrixInterface;

    public function unshiftColumn(NumberCollection $column): MatrixInterface;

    public function shiftRow(): NumberCollection;

    public function shiftColumn(): NumberCollection;

    public function rotate(bool $clockwise = true): MatrixInterface;

    public function add(MatrixInterface $value): MatrixInterface;

    public function addScalarAsI(Decimal $value): MatrixInterface;

    public function addScalarAsJ(Decimal $value): MatrixInterface;

    public function subtract(MatrixInterface $value): MatrixInterface;

    public function subtractScalarAsI(Decimal $value): MatrixInterface;

    public function subtractScalarAsJ(Decimal $value): MatrixInterface;

    public function multiply($value): MatrixInterface;

    public function inverseMatrix(): MatrixInterface;

}