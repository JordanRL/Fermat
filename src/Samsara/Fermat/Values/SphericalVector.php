<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\TrigonometryProvider;

class SphericalVector
{

    /**
     * @var Base\NumberInterface
     */
    private $theta;

    /**
     * @var Base\NumberInterface
     */
    private $phi;

    /**
     * @var Base\NumberInterface
     */
    private $rho;

    public function __construct($theta, $phi, $rho)
    {
        $this->theta = Numbers::makeOrDont(Numbers::IMMUTABLE, $theta);
        $this->phi = Numbers::makeOrDont(Numbers::IMMUTABLE, $phi);
        $this->rho = Numbers::makeOrDont(Numbers::IMMUTABLE, $rho);
    }

    public static function createFromCartesian($x, $y, $z)
    {
        $rho = TrigonometryProvider::sphericalCartesianDistance($x, $y, $z);
        $phi = TrigonometryProvider::sphericalCartesianAzimuth($x, $y);
        $theta = TrigonometryProvider::sphericalCartesianInclination($x, $y, $z);

        return new Vector($theta, $phi, $rho);
    }

    public function getTheta()
    {
        return $this->theta;
    }

    public function getInclination()
    {
        return $this->getTheta();
    }

    public function getPhi()
    {
        return $this->phi;
    }

    public function getAzimuth()
    {
        return $this->getPhi();
    }

    public function getRho()
    {
        return $this->rho;
    }

    public function getMagnitude()
    {
        return $this->getRho();
    }

}