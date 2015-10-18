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

    public function setTheta($theta)
    {
        $this->theta = $theta;

        return $this;
    }

    public function setInclination($theta)
    {
        return $this->setTheta($theta);
    }

    public function getTheta()
    {
        return $this->theta;
    }

    public function getInclination()
    {
        return $this->getTheta();
    }

    public function setPhi($phi)
    {
        $this->phi = $phi;

        return $this;
    }

    public function setAzimuth($phi)
    {
        return $this->setPhi($phi);
    }

    public function getPhi()
    {
        return $this->phi;
    }

    public function getAzimuth()
    {
        return $this->getPhi();
    }

    public function setRho($rho)
    {
        $this->rho = $rho;

        return $this;
    }

    public function setMagnitude($rho)
    {
        return $this->setRho($rho);
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