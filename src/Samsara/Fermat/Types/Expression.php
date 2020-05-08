<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Types\Base\Interfaces\Evaluateables\ExpressionInterface;

abstract class Expression implements ExpressionInterface
{
    const POLYNOMIAL = 1;
    const EXPONENTIAL = 2;
    const LOGARITHMIC = 3;

    /** @var callable */
    protected $expression;

    /** @var int */
    private $type;

    public function __construct(int $type)
    {
        $this->type = $type;
    }

    abstract function evaluateAt($x);

}