<?php

namespace Samsara\Fermat\Types\Base;

interface FunctionInterface
{

    public function derivativeExpression(): FunctionInterface;

    public function integralExpression(): FunctionInterface;

}