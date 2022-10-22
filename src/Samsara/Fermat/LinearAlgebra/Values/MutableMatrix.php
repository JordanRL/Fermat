<?php

namespace Samsara\Fermat\LinearAlgebra\Values;

use Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\MatrixInterface;
use Samsara\Fermat\LinearAlgebra\Types\Matrix;

/**
 * @package Samsara\Fermat\LinearAlgebra
 */
class MutableMatrix extends Matrix
{

    protected function setValue(array $data, $mode = Matrix::MODE_ROWS_INPUT): MatrixInterface
    {
        $this->normalizeInputData($data, $mode);

        return $this;
    }

}