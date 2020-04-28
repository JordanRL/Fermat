<?php

namespace Samsara\Fermat\Types\Base\Interfaces;

use Samsara\Fermat\Values\ImmutableDecimal;

interface CoordinateInterface
{

    public function __construct(array $data);

    public function getAxis($axis): ImmutableDecimal;

    public function numberOfDimensions(): int;

    public function axisValues(): array;

    public function distanceTo(CoordinateInterface $coordinate): ImmutableDecimal;

}