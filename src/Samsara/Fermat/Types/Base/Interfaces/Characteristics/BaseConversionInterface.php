<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Characteristics;


use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

interface BaseConversionInterface
{

    /**
     * @param int $base
     *
     * @return DecimalInterface
     */
    public function convertToBase($base);

    /**
     * @return int
     */
    public function getBase(): int;

}