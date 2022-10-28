<?php

namespace Samsara\Fermat\Expressions\Types;

use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Expressions\Enums\Functions;
use Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables\ExpressionInterface;

/**
 * @package Samsara\Fermat\Expressions
 */
abstract class Expression implements ExpressionInterface
{


    /** @var callable */
    protected $expression;
    /** @var ImmutableDecimal[]  */
    protected array $terms = [];
    private Functions $type;

    public function __construct(Functions $type)
    {
        $this->type = $type;
    }

    abstract function evaluateAt(int|float|string|Decimal $x): ImmutableDecimal;

}