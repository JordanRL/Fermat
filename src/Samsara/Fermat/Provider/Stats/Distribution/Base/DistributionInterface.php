<?php

namespace Samsara\Fermat\Provider\Stats\Distribution\Base;

interface DistributionInterface
{

    public function random();

    public function rangeRandom($min, $max);

    public function cdf($x);

    public function pdf($x1, $x2);

}