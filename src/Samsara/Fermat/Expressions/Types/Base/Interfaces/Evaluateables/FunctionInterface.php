<?php

namespace Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables;

use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Expressions
 */
interface FunctionInterface extends ExpressionInterface
{
    public function evaluateAt(int|float|string|Decimal $x): ImmutableDecimal;

    public function derivativeExpression(): FunctionInterface;

    public function integralExpression(): FunctionInterface;

    public function describeShape(): array;

}