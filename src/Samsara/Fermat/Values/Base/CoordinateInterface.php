<?php

namespace Samsara\Fermat\Values\Base;

interface CoordinateInterface
{
    public function size();

    public function all();

    public function distanceTo(CoordinateInterface $coordinate);

}