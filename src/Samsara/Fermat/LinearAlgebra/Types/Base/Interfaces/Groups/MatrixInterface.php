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

    public function pushRow(NumberCollection $row): static;

    public function pushColumn(NumberCollection $column): static;

    public function popRow(): NumberCollection;

    public function popColumn(): NumberCollection;

    public function unshiftRow(NumberCollection $row): static;

    public function unshiftColumn(NumberCollection $column): static;

    public function shiftRow(): NumberCollection;

    public function shiftColumn(): NumberCollection;

    public function rotate(bool $clockwise = true): static;

    public function add(MatrixInterface $value): static;

    public function addScalarAsI(Decimal $value): static;

    public function addScalarAsJ(Decimal $value): static;

    public function subtract(MatrixInterface $value): static;

    public function subtractScalarAsI(Decimal $value): static;

    public function subtractScalarAsJ(Decimal $value): static;

    public function multiply($value): static;

    public function inverseMatrix(): static;

}