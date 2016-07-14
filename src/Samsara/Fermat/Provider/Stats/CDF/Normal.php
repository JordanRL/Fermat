<?php

namespace Samsara\Fermat\Provider\Stats\CDF;

use Samsara\Fermat\Numbers;

class Normal
{

    private $mean;

    private $sd;

    public function __construct($mean, $sd)
    {
        $mean = Numbers::makeOrDont(Numbers::IMMUTABLE, $mean);
        $sd = Numbers::makeOrDont(Numbers::IMMUTABLE, $sd);

        $this->mean = $mean;
        $this->sd = $sd;
    }

    public static function makeFromMean($p, $x, $mean)
    {

    }

    public static function makeFromSd($p, $x, $sd)
    {

    }

}