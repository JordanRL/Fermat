<?php

namespace Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Expressions
 */
interface ExpressionInterface
{

    public function evaluateAt($x);

}