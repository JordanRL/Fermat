<?php

namespace Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups;

use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Core\Types\Tuple;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\LinearAlgebra
 */
interface VectorInterface
{

    public function add(VectorInterface $vector): VectorInterface;

    public function subtract(VectorInterface $vector): VectorInterface;

    public function multiply(int|float|string|NumberInterface|VectorInterface $value): NumberInterface|VectorInterface;

    public function multiplyScalar(NumberInterface $number): VectorInterface;

    public function multiplyVectorProduct(VectorInterface $vector): VectorInterface;

    public function multiplyScalarProduct(VectorInterface $vector): NumberInterface;

    public function asTuple(): Tuple;

    public function asMatrix(): MatrixInterface;

    public function asNumberCollection(): NumberCollectionInterface;

}