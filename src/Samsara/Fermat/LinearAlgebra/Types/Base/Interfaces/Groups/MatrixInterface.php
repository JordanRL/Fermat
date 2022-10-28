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

    public function add(MatrixInterface $value): static;

    public function addScalarAsI(Decimal $value): static;

    public function addScalarAsJ(Decimal $value): static;

    public function getColumn(int $column): NumberCollection;

    public function getColumnCount(): int;

    public function getDeterminant(): ImmutableDecimal;

    public function getInverseMatrix(): static;

    public function getRow(int $row): NumberCollection;

    public function getRowCount(): int;

    public function isSquare(): bool;

    public function multiply($value): static;

    public function popColumn(): NumberCollection;

    public function popRow(): NumberCollection;

    public function pushColumn(NumberCollection $column): static;

    public function pushRow(NumberCollection $row): static;

    public function rotate(bool $clockwise = true): static;

    public function shiftColumn(): NumberCollection;

    public function shiftRow(): NumberCollection;

    public function subtract(MatrixInterface $value): static;

    public function subtractScalarAsI(Decimal $value): static;

    public function subtractScalarAsJ(Decimal $value): static;

    public function unshiftColumn(NumberCollection $column): static;

    public function unshiftRow(NumberCollection $row): static;

}