<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\TrigonometryProvider;

class SphericalVector
{

    public function __construct($theta, $phi, $rho)
    {
        $this->theta = Numbers::makeOrDont(Numbers::IMMUTABLE, $theta);
        $this->phi = Numbers::makeOrDont(Numbers::IMMUTABLE, $phi);
        if (is_object($rho)) {
            $this->rho = $rho;
        } else {
            $this->rho = Numbers::makeOrDont(Numbers::IMMUTABLE, $rho);
        }
    }

    public static function createFromCartesian($x, $y, $z)
    {
        $rho = TrigonometryProvider::sphericalCartesianDistance($x, $y, $z);
        $phi = TrigonometryProvider::sphericalCartesianAzimuth($x, $y);
        $theta = TrigonometryProvider::sphericalCartesianInclination($x, $y, $z);

        return new SphericalVector($theta, $phi, $rho);
    }

}