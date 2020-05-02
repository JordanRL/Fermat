<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Evaluateables;

use Samsara\Fermat\Values\ImmutableDecimal;

interface FunctionInterface
{
    public function evaluateAt($x): ImmutableDecimal;

    public function derivativeExpression(): FunctionInterface;

    public function integralExpression(): FunctionInterface;

    public function describeShape(): array;

}