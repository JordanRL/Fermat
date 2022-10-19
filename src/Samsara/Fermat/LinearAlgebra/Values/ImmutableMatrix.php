<?php

namespace Samsara\Fermat\LinearAlgebra\Values;

use Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\MatrixInterface;
use Samsara\Fermat\LinearAlgebra\Types\Matrix;

class ImmutableMatrix extends Matrix
{

    protected function setValue(array $data, $mode = Matrix::MODE_ROWS_INPUT): MatrixInterface
    {
        return new ImmutableMatrix($data, $mode);
    }

}