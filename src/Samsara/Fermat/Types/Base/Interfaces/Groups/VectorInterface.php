<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Groups;

use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Tuple;

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