<?php

namespace Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables;

use Samsara\Fermat\Core\Values\ImmutableDecimal;

interface FunctionInterface
{
    public function evaluateAt($x): ImmutableDecimal;

    public function derivativeExpression(): FunctionInterface;

    public function integralExpression(): FunctionInterface;

    public function describeShape(): array;

}