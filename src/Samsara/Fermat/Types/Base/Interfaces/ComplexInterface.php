<?php

namespace Samsara\Fermat\Types\Base\Interfaces;

interface ComplexInterface
{

    public function asPolar();

    public function getMagnitude();

    public function getAngle();

    public function getRealPart();

    public function getComplexPart();

}