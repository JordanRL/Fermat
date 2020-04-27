<?php

namespace Samsara\Fermat\Types\Base;

interface MatrixInterface
{

    public function getRowCount(): int;

    public function getRow(): array;

    public function getColumnCount(): int;

    public function getColumn(): array;

    public function pushRow(array $row): MatrixInterface;

    public function pushColumn(array $column): MatrixInterface;

    public function rowColumnInvert(): MatrixInterface;

    public function add($value): MatrixInterface;

    public function subtract($value): MatrixInterface;

    public function multiply($value): MatrixInterface;

    public function divide($value): MatrixInterface;

}