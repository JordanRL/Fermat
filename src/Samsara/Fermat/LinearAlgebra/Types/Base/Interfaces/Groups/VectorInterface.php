<?php

namespace Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups;

use Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Tuple;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\LinearAlgebra
 */
interface VectorInterface
{

    public function add(VectorInterface $vector): VectorInterface;

    public function subtract(VectorInterface $vector): VectorInterface;

    public function multiply(int|float|string|Decimal|VectorInterface $value): Decimal|VectorInterface;

    public function multiplyScalar(Decimal $number): VectorInterface;

    public function multiplyVectorProduct(VectorInterface $vector): VectorInterface;

    public function multiplyScalarProduct(VectorInterface $vector): Decimal;

    public function asTuple(): Tuple;

    public function asMatrix(): MatrixInterface;

    public function asNumberCollection(): NumberCollectionInterface;

}