<?php

namespace Samsara\Fermat\LinearAlgebra\Values;

use Samsara\Fermat\LinearAlgebra\Types\Matrix;

/**
 * @package Samsara\Fermat\LinearAlgebra
 */
class MutableMatrix extends Matrix
{

    protected function setValue(array $data, $mode = Matrix::MODE_ROWS_INPUT): static
    {
        $this->normalizeInputData($data, $mode);

        return $this;
    }

}