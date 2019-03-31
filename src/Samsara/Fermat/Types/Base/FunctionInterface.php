<?php

namespace Samsara\Fermat\Types\Base;

interface FunctionInterface
{

    public function derivativeExpression(): ExpressionInterface;

    public function integralExpression(): ExpressionInterface;

}