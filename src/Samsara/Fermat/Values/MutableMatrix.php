<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Base\Interfaces\Groups\MatrixInterface;
use Samsara\Fermat\Types\Matrix;

class MutableMatrix extends Matrix
{

    protected function setValue(array $data, $mode = Matrix::MODE_ROWS_INPUT): MatrixInterface
    {
        $this->normalizeInputData($data, $mode);

        return $this;
    }

}