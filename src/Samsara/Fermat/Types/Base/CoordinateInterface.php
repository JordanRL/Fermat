<?php

namespace Samsara\Fermat\Types\Base;

use Samsara\Fermat\Values\ImmutableNumber;

interface CoordinateInterface
{

    public function getAxis($axis): ImmutableNumber;

    public function numberOfDimensions(): int;

    public function axisValues(): array;

    public function distanceTo(CoordinateInterface $coordinate): ImmutableNumber;

}