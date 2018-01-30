<?php

namespace Samsara\Fermat\Types\Base;

interface MatrixInterface
{

    public function getRowCount(): int;

    public function getColumnCount(): int;

    public function pushRow(array $row): MatrixInterface;

    public function pushColumn(array $column): MatrixInterface;

}