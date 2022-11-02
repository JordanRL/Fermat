<?php

namespace Samsara\Fermat\Expressions\Provider;

use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Expressions\Values\Algebra\PolynomialFunction;

class SpecialPolynomials
{

    public static function fallingFactorial(int|float|string|Decimal $n): PolynomialFunction
    {
        $basePoly = new PolynomialFunction([0, 1]);
        /** @var ImmutableDecimal $n */
        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

        for ($i = 1; $n->isGreaterThan($i); $i++) {
            $basePoly = $basePoly->multiplyByPolynomial(new PolynomialFunction([$i * -1, 1]));
        }

        return $basePoly;
    }

    public static function risingFactorial(int|float|string|Decimal $n): PolynomialFunction
    {
        $basePoly = new PolynomialFunction([0, 1]);
        /** @var ImmutableDecimal $n */
        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

        for ($i = 1; $n->isGreaterThan($i); $i++) {
            $basePoly = $basePoly->multiplyByPolynomial(new PolynomialFunction([$i, 1]));
        }

        return $basePoly;
    }

}