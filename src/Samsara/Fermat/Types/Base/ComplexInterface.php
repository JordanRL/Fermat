<?php

namespace Samsara\Fermat\Types\Base;

interface ComplexInterface
{

    public function asPolar();

    public function getMagnitude();

    public function getAngle();

    public function getRealPart();

    public function getComplexPart();

}