<?php

namespace Samsara\Fermat\Expressions\Values\Algebra;

use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Expressions\Enums\Functions;
use Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables\FunctionInterface;
use Samsara\Fermat\Expressions\Types\Expression;

/**
 * Class ExponentialFunction
 *
 * Represents an exponential function.
 *
 * @extends Expression
 * @implements FunctionInterface
 */
class ExponentialFunction extends Expression implements FunctionInterface
{

    /**
     * Constructs a new instance of the class.
     *
     * @param ImmutableDecimal $exponent The exponent term of the exponential.
     * @param ImmutableDecimal $coefficient The coefficient term of the exponential.
     */
    final public function __construct(ImmutableDecimal $exponent, ImmutableDecimal $coefficient)
    {
        parent::__construct(Functions::Exponential);

        $this->terms['exponent'] = $exponent;
        $this->terms['coefficient'] = $coefficient;

        $this->expression = function (ImmutableDecimal $x): ImmutableDecimal {
            $exponent = $x->multiply($this->terms['exponent']);

            /** @var ImmutableDecimal $result */
            $result = $exponent->exp()->multiply($this->terms['coefficient']);

            return $result;
        };
    }

    /**
     * Returns the derivative expression.
     *
     * @return static The derivative expression.
     */
    public function derivativeExpression(): static
    {
        $exponent = $this->terms['exponent'];
        $coefficient = $this->terms['coefficient']->multiply($exponent);

        return new static($exponent, $coefficient);
    }

    /**
     * Returns an array that describes the shape.
     *
     * @return array The array that describes the shape.
     */
    public function describeShape(): array
    {
        $terms = [];

        foreach ($this->terms as $key => $value) {
            $terms[$key] = $value->getValue();
        }

        return $terms;
    }

    /**
     * Evaluates the function at a given value.
     *
     * @param int|float|string|Decimal $x The value at which to evaluate the function.
     *
     * @return ImmutableDecimal The result of evaluating the function at the given value.
     */
    function evaluateAt(int|float|string|Decimal $x): ImmutableDecimal
    {
        $answer = $this->expression;

        return $answer($x);
    }

    /**
     * Calculates the integral expression of the current function.
     *
     * @return static The integral expression of the current function.
     */
    public function integralExpression(): static
    {
        $exponent = $this->terms['exponent'];
        $coefficient = $this->terms['coefficient']->divide($exponent);

        return new static($exponent, $coefficient);
    }
}