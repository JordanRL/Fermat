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

    /**
     * PolynomialFunction constructor.
     *
     * @param array $coefficients
     *
     * @throws IntegrityConstraint
     */
    public function __construct(array $coefficients)
    {
        parent::__construct(Expression::POLYNOMIAL);

        $sanitizedCoefficients = [];

        foreach ($coefficients as $exponent => $coefficient) {
            if (!is_int($exponent)) {
                throw new IntegrityConstraint(
                    'Keys in the $coefficients array must be integers',
                    'Only use integer keys for the $coefficients array',
                    'The key '.$exponent.' was found in the $coefficients array; an integer was expected'
                );
            }

            /** @var ImmutableNumber $fermatCoefficient */
            $fermatCoefficient = Numbers::make(Numbers::IMMUTABLE, $coefficient);

            if (!$fermatCoefficient->isEqual(0)) {
                $sanitizedCoefficients[$exponent] = $fermatCoefficient;
            }
        }

        $this->coefficients = $sanitizedCoefficients;

        $this->expression = function($x) use ($sanitizedCoefficients): ImmutableNumber {
            /** @var ImmutableNumber $value */
            $value = Numbers::makeZero();

            /** @var ImmutableNumber $xPart */
            $xPart = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

            foreach ($sanitizedCoefficients as $exponent => $coefficient) {
                if ($exponent == 0) {
                    $value = $value->add($coefficient);
                } else {
                    $term = $coefficient->multiply($xPart->pow($exponent));
                    $value = $value->add($term);
                }
            }

            if ($value->isEqual(0)) {
                $value = Numbers::makeOne();
            }

            return $value;
        };
    }

    /**
     * @param int|float|string|NumberInterface|DecimalInterface $x
     *
     * @return ImmutableNumber
     * @throws IntegrityConstraint
     */
    public function evaluateAt($x): ImmutableNumber
    {
        /** @var callable $answer */
        $answer = $this->expression;

        return $answer($x);
    }

    /**
     * @return FunctionInterface
     * @throws IntegrityConstraint
     */
    public function derivativeExpression(): FunctionInterface
    {
        $newCoefficients = [];

        /**
         * @var int             $exponent
         * @var ImmutableNumber $coefficient
         */
        foreach ($this->coefficients as $exponent => $coefficient) {
            if ($exponent == 0) {
                continue;
            }

            $newCoefficients[$exponent-1] = $coefficient->multiply($exponent);
        }

        return new PolynomialFunction($newCoefficients);
    }

    /**
     * @param int|float|string|NumberInterface|DecimalInterface $C
     *
     * @return FunctionInterface
     * @throws IntegrityConstraint
     */
    public function integralExpression($C = 0): FunctionInterface
    {
        $C = Numbers::make(Numbers::IMMUTABLE, $C);

        $newCoefficients = [];

        if (!$C->isEqual(0)) {
            $newCoefficients[0] = $C;
        }

        /**
         * @var int             $exponent
         * @var ImmutableNumber $coefficient
         */
        foreach ($this->coefficients as $exponent => $coefficient) {
            $newExponent = $exponent+1;

            $newCoefficients[$newExponent] = $coefficient->divide($newExponent);
        }

        return new PolynomialFunction($newCoefficients);
    }

    public function describeShape(): array 
    {

        $shape = [];

        /**
         * @var int             $exponent
         * @var ImmutableNumber $coefficient
         */
        foreach ($this->coefficients as $exponent => $coefficient) {
            $shape[$exponent] = $coefficient->getValue();
        }

        return $shape;

    }

}