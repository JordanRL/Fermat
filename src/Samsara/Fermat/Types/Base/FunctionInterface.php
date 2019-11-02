<?php

namespace Samsara\Fermat\Types\Base;

use Samsara\Fermat\Values\ImmutableNumber;

interface FunctionInterface
{
    public function evaluateAt($x): ImmutableNumber;

    public function derivativeExpression(): FunctionInterface;

    public function integralExpression(): FunctionInterface;

    public function describeShape(): array;

}