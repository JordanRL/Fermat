<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Groups;

use Samsara\Fermat\Types\NumberCollection;
use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;

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

    public function addScalarAsI(NumberInterface $value): MatrixInterface;

    public function addScalarAsJ(NumberInterface $value): MatrixInterface;

    public function subtract(MatrixInterface $value): MatrixInterface;

    public function subtractScalarAsI(NumberInterface $value): MatrixInterface;

    public function subtractScalarAsJ(NumberInterface $value): MatrixInterface;

    public function multiply($value): MatrixInterface;

    public function inverseMatrix(): MatrixInterface;

}