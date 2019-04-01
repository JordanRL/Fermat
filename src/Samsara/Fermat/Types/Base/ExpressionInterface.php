<?php

namespace Samsara\Fermat\Types\Base;

interface ExpressionInterface
{

    public function evaluateAt($x);

}