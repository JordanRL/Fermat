<?php

namespace Samsara\Fermat\Types\Base;

interface CoordinateInterface
{
    public function size();

    public function all();

    public function distanceTo(CoordinateInterface $coordinate);

}