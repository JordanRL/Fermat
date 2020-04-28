<?php

namespace Samsara\Fermat\Types\Base\Interfaces;

use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Types\Base\NumberInterface;

interface BaseConversionInterface
{

    /**
     * @param int $base
     *
     * @return NumberInterface|DecimalInterface
     */
    public function convertToBase($base);

}