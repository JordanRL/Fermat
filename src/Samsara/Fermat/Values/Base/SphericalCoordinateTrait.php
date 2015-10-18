<?php

namespace Samsara\Fermat\Values\Base;

trait SphericalCoordinateTrait
{


    /**
     * @var NumberInterface
     */
    private $theta;

    /**
     * @var NumberInterface
     */
    private $phi;

    private $rho;

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