<?php

namespace Samsara\Fermat\Expressions\Values\Algebra;

use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Expressions\Enums\Functions;
use Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables\FunctionInterface;
use Samsara\Fermat\Expressions\Types\Expression;

class ExponentialFunction extends Expression implements FunctionInterface
{

    final public function __construct(ImmutableDecimal $exponent, ImmutableDecimal $coefficient)
    {
        parent::__construct(Functions::Exponential);

        $this->terms['exponent'] = $exponent;
        $this->terms['coefficient'] = $coefficient;

        $this->expression = function(ImmutableDecimal $x): ImmutableDecimal {
            $exponent = $x->multiply($this->terms['exponent']);

            /** @var ImmutableDecimal $result */
            $result = $exponent->exp()->multiply($this->terms['coefficient']);

            return $result;
        };
    }

    function evaluateAt(int|float|string|Decimal $x): ImmutableDecimal
    {
        $answer = $this->expression;

        return $answer($x);
    }

    public function derivativeExpression(): FunctionInterface
    {
        $exponent = $this->terms['exponent'];
        $coefficient = $this->terms['coefficient']->multiply($exponent);

        return new static($exponent, $coefficient);
    }

    public function integralExpression(): FunctionInterface
    {
        $exponent = $this->terms['exponent'];
        $coefficient = $this->terms['coefficient']->divide($exponent);

        return new static($exponent, $coefficient);
    }

    public function describeShape(): array
    {
        $terms = [];

        foreach ($this->terms as $key => $value) {
            $terms[$key] = $value->getValue();
        }

        return $terms;
    }
}