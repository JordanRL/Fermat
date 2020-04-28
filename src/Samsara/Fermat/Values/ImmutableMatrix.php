<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Matrix;

class ImmutableMatrix extends Matrix
{

    public function __construct(array $data, string $mode = Matrix::MODE_ROWS_INPUT)
    {
        parent::__construct($data, $mode);
    }

}