<?php

namespace Samsara\Fermat\Expressions\Values\Algebra;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\NumberCollection;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Expressions\Enums\Functions;
use Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables\FunctionInterface;
use Samsara\Fermat\Expressions\Types\Expression;

/**
 * @package Samsara\Fermat\Expressions
 */
class PolynomialFunction extends Expression implements FunctionInterface
{

    /**
     * Constructs a polynomial function with provided coefficients.
     *
     * This constructor method instantiates a PolynomialFunction object with the provided coefficients that can be either an array
     * or a NumberCollection. The coefficients are sanitized to be an ImmutableDecimal and used to define each term of the polynomial.
     * The 'terms' field is populated with the sanitized coefficients, and a closure function for evaluating the polynomial expression is
     * assigned to the 'expression' field.
     *
     * @param array|NumberCollection $coefficients The coefficients of the polynomial, it must be either an array or a NumberCollection.
     *
     * @throws IntegrityConstraint If a non-integer key is found in the coefficients array.
     */
    public function __construct(array|NumberCollection $coefficients)
    {
        parent::__construct(Functions::Polynomial);

        $sanitizedCoefficients = [];

        /*
         * Sanitize the coefficients for each term so that they are in the expected form of an ImmutableDecimal.
         */
        foreach ($coefficients as $exponent => $coefficient) {
            if (!is_int($exponent)) {
                throw new IntegrityConstraint(
                    'Keys in the $coefficients array must be integers',
                    'Only use integer keys for the $coefficients array',
                    'The key ' . $exponent . ' was found in the $coefficients array; an integer was expected'
                );
            }

            $fermatCoefficient = new ImmutableDecimal($coefficient);

            if (!$fermatCoefficient->isEqual(0)) {
                $sanitizedCoefficients[$exponent] = $fermatCoefficient;
            }
        }

        $this->terms = $sanitizedCoefficients;

        $this->expression = function ($x): ImmutableDecimal {
            $value = Numbers::makeZero();

            /** @var ImmutableDecimal $xPart */
            $xPart = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

            foreach ($this->terms as $exponent => $coefficient) {
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
     * This function performs a FOIL expansion on a list of parameters.
     *
     * Assumptions:
     *      1. The coefficients are the numbers provided in the arrays
     *      2. The coefficients are listed in descending order of their exponent on the function variable. For example,
     *         if you were multiplying (2 + 3x)*(5 - 1x^2 + 1x), it would expect these inputs:
     *              -  [3, 2], [-1, 1, 5]
     *      3. If not all exponents are used continuously, a zero must be provided for the position that is skipped. For
     *         example, if one of the provided groups was 4x^2 + 2, it would expect: [4, 0, 2]
     *
     * @param int[]|float[]|Decimal[] $group1
     * @param int[]|float[]|Decimal[] $group2
     *
     * @return PolynomialFunction
     * @throws IntegrityConstraint
     */
    public static function createFromFoil(array $group1, array $group2): PolynomialFunction
    {
        $group1exp = count($group1) - 1;
        $group2exp = count($group2) - 1;

        /** @var ImmutableDecimal[] $finalCoefs */
        $finalCoefs = [];

        $group1 = Numbers::makeOrDont(Numbers::IMMUTABLE, $group1);
        $group2 = Numbers::makeOrDont(Numbers::IMMUTABLE, $group2);

        if ($group1exp <= $group2exp) {
            $largerGroup = $group2;
            $largerExp = $group2exp;
            $smallerGroup = $group1;
            $smallerExp = $group1exp;
        } else {
            $largerGroup = $group1;
            $largerExp = $group1exp;
            $smallerGroup = $group2;
            $smallerExp = $group2exp;
        }

        /**
         * @var int              $key1
         * @var ImmutableDecimal $value1
         */
        foreach ($largerGroup as $key1 => $value1) {
            $largerKey = $largerExp - $key1;

            /**
             * @var int              $key2
             * @var ImmutableDecimal $value2
             */
            foreach ($smallerGroup as $key2 => $value2) {
                $smallerKey = $smallerExp - $key2;
                $newVal = $value1->multiply($value2);
                $newExp = $largerKey + $smallerKey;

                if (isset($finalCoefs[$newExp])) {
                    $finalCoefs[$newExp] = $finalCoefs[$newExp]->add($newVal);
                } else {
                    $finalCoefs[$newExp] = $newVal;
                }
            }
        }

        return new PolynomialFunction($finalCoefs);
    }

    /**
     * Calculates the derivative of a polynomial function.
     *
     * A derivative in calculus is a measure of how a function changes as its input changes.
     * In terms of polynomial function, the derivative of a term (ax^n) is (n * ax^{n-1}).
     *
     * This method loops through each term (coefficient) of the polynomial function
     * and multiplies the coefficient by its exponent, then decreases the exponent by one.
     *
     * It essentially applies the power rule of derivatives, which states: d/dx[x^n] = n * x^{n-1}.
     *
     * Note: Terms with an exponent of 0 (constant terms) are dropped in the derivative,
     * as their derivative is 0.
     *
     * @example For a polynomial function 3x^2 + 2x + 1, the derivative would be 2*3x + 2 or 6x + 2.
     *
     * @return static Returns a new instance which represents the derivative of the original polynomial function
     *
     * @see https://en.wikipedia.org/wiki/Derivative
     * @see https://en.wikipedia.org/wiki/Polynomial#Polynomials_in_one_variable
     * @see PolynomialFunction For the definition of the function's valid input and expected output.
     * @see FunctionInterface::derivativeExpression() For the derivative function in the FunctionInterface.
     */
    public function derivativeExpression(): static
    {
        $newCoefficients = [];

        /**
         * @var int              $exponent
         */
        foreach ($this->terms as $exponent => $coefficient) {
            if ($exponent == 0) {
                continue;
            }

            $newCoefficients[$exponent - 1] = $coefficient->multiply($exponent);
        }

        return new static($newCoefficients);
    }

    /**
     * This method returns the shape of the PolynomialFunction.
     *
     * It returns an associative array where the exponent values are the keys, and
     * the corresponding coefficients are the string values, which are the results
     * of the `getValue()` function on the `ImmutableDecimal` object.
     *
     * @return array
     *
     * @example
     * If the PolynomialFunction is represented by equation 3x^2 + 2x - 1,
     * the result of this function would be:
     *  {2 => '3', 1 => '2', 0 => '-1'}
     */
    public function describeShape(): array
    {

        $shape = [];

        /**
         * @var int              $exponent
         * @var ImmutableDecimal $coefficient
         */
        foreach ($this->terms as $exponent => $coefficient) {
            $shape[$exponent] = $coefficient->getValue();
        }

        return $shape;

    }

    /**
     * Evaluates the PolynomialFunction at the given value 'x'.
     *
     * The function substitutes 'x' with the provided value and returns the computed result as an instance of ImmutableDecimal.
     *
     * @param int|float|string|Decimal $x The value at which polynomial needs to be evaluated.
     * @return ImmutableDecimal The result of the computation.
     *
     * @see PolynomialFunction::__construct for the callable that is used to evaluate the polynomial.
     */
    public function evaluateAt(int|float|string|Decimal $x): ImmutableDecimal
    {
        $answer = $this->expression;

        return $answer($x);
    }

    /**
     * This function calculates the integral of the polynomial expression.
     * It treats the input $C as the constant of integration, which defaults to 0.
     * The function makes $C an 'IMMUTABLE' type numeric value with the help of the Numbers::make function.
     * Then, for each term in the polynomial, it increases the exponent by one and divides the coefficient by the new exponent.
     * Finally, it returns a new PolynomialFunction constructed with the new coefficients.
     *
     * @param float|int|string|Decimal $C The constant of integration.
     * @return FunctionInterface The integral of the polynomial function.
     *
     * @example
     * Let's assume we have a PolynomialFunction P with terms [2 => '3', 1 => '2', 0 => '1'] (which represents the function `3x^2 + 2x + 1`)
     *
     * If we call `P->integralExpression(1)`, it will return another PolynomialFunction Q,
     * with coefficients [0 => '1', 3 => '1', 2 => '1', 1 => '0.5']  (which represents the function `x^3 + x^2 + 0.5x + 1`)
     * That's because the integral of `3x^2` is `x^3`, the integral of `2x` is `x^2`, and the integral of `1` is `0.5x`,
     * and we added the constant of integration `1` at the end.
     *
     */
    public function integralExpression(float|int|string|Decimal $C = 0): FunctionInterface
    {
        $C = Numbers::make(Numbers::IMMUTABLE, $C);

        $newCoefficients = [];

        if (!$C->isEqual(0)) {
            $newCoefficients[0] = $C;
        }

        /**
         * @var int              $exponent
         * @var ImmutableDecimal $coefficient
         */
        foreach ($this->terms as $exponent => $coefficient) {
            $newExponent = $exponent + 1;

            $newCoefficients[$newExponent] = $coefficient->divide($newExponent);
        }

        return new PolynomialFunction($newCoefficients);
    }

    public function multiplyByPolynomial(PolynomialFunction $polynomialFunction): PolynomialFunction
    {
        $thisShape = $this->describeShape();
        $thatShape = $polynomialFunction->describeShape();

        /** @var ImmutableDecimal[] $newCoefs */
        $newCoefs = [];

        foreach ($thisShape as $thisExp => $thisVal) {
            /** @var ImmutableDecimal $thisVal */
            $thisVal = Numbers::makeOrDont(Numbers::IMMUTABLE, $thisVal);
            foreach ($thatShape as $thatExp => $thatVal) {
                $newExp = $thisExp + $thatExp;
                /** @var ImmutableDecimal $thatVal */
                $thatVal = Numbers::makeOrDont(Numbers::IMMUTABLE, $thatVal);
                $newVal = $thisVal->multiply($thatVal);

                if (isset($newCoefs[$newExp])) {
                    $newCoefs[$newExp] = $newCoefs[$newExp]->add($newVal);
                } else {
                    $newCoefs[$newExp] = $newVal;
                }
            }
        }

        return new PolynomialFunction($newCoefs);
    }

}