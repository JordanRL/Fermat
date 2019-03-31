<?php

namespace Samsara\Fermat\Values\Algebra;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\ExpressionInterface;
use Samsara\Fermat\Types\Base\FunctionInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Types\Expression;
use Samsara\Fermat\Values\ImmutableNumber;

class PolynomialFunction extends Expression implements ExpressionInterface, FunctionInterface
{
    /** @var array  */
    protected $coefficients = [];

    public function __construct(array $coefficients)
    {
        parent::__construct(Expression::POLYNOMIAL);

        foreach ($coefficients as $exponent => $coefficient) {
            if (!is_int($exponent)) {
                throw new IntegrityConstraint(
                    'Keys in the $coefficients array must be integers',
                    'Only use integer keys for the $coefficients array',
                    'The key '.$exponent.' was found in the $coefficients array; an integer was expected'
                );
            }

            if (
                !is_int($coefficient) &&
                !is_float($coefficient) &&
                !is_numeric($coefficient) &&
                !($coefficient instanceof DecimalInterface) &&
                !($coefficient instanceof NumberInterface)
            ) {
                throw new IntegrityConstraint(
                    'Values for coefficients must be valid for an ImmutableNumber constructor',
                    'Only give values which can be used for an ImmutableNumber constructor',
                    'The coefficient '.$coefficient.' is not valid for an ImmutableNumber constructor'
                );
            }
        }

        $this->coefficients = $coefficients;

        $this->expression = function($x) use ($coefficients): ImmutableNumber {
            /** @var ImmutableNumber $value */
            $value = Numbers::makeZero();

            /** @var ImmutableNumber $xPart */
            $xPart = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

            foreach ($coefficients as $exponent => $coefficient) {
                /** @var ImmutableNumber $term */
                $term = Numbers::makeOrDont(Numbers::IMMUTABLE, $coefficient);

                if ($term->isEqual(0)) {
                    continue;
                }

                if ($exponent == 0) {
                    $value = $value->add($term);
                } else {
                    $term = $term->multiply($xPart->pow($exponent));
                    $value = $value->add($term);
                }
            }

            return $value;
        };
    }

    public function evaluateAt($x): NumberInterface
    {
        $answer = $this->expression;

        return $answer($x);
    }

    public function derivativeExpression(): ExpressionInterface
    {
        // TODO: Implement derivativeExpression() method.
    }

    public function integralExpression(): ExpressionInterface
    {
        // TODO: Implement integralExpression() method.
    }

}