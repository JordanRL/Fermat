<?php

namespace Samsara\Fermat\Provider;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\Base\NumberInterface;

class SeriesProvider
{

    /**
     * Creates a series that evaluates the following:
     *
     * SUM[$startTerm -> infinity](
     *  $numerator($n) × $input^$exponent($n)
     *  --------------------------------
     *          $denominator($n)
     * )
     *
     * Where $n is the current term number, starting at $startTerm, and increasing by 1 each loop; where $numerator,
     * $exponent, and $denominator are callables that take the term number (as an int) as their only input, and give the
     * value of that section at that term number; and where $input is the x value being considered for the series.
     *
     * The function continues adding terms until a term has MORE leading zeros than the $precision setting. (That is,
     * until it adds zero to the total when considering significant digits.)
     *
     * @param NumberInterface $input
     * @param callable        $numerator
     * @param callable        $exponent
     * @param callable        $denominator
     * @param int             $startTermAt
     * @param int             $precision
     *
     * @return NumberInterface
     */
    public static function maclaurinSeries(
        NumberInterface $input, // x value in series
        callable $numerator, // a function determining what the sign (+/-) at the nth term
        callable $exponent, // a function determining the exponent of x at the nth term
        callable $denominator, // a function determining the denominator at the nth term
        $startTermAt = 0,
        $precision = 10)
    {

        $x = Numbers::makeZero();
        $value = Numbers::make(Numbers::IMMUTABLE, $input->getValue());

        $continue = true;
        $termNumber = $startTermAt;

        while ($continue) {
            $term = Numbers::makeOne();

            $term = $term->multiply($value->pow($exponent($termNumber)))
                ->divide($denominator($termNumber))
                ->multiply($numerator($termNumber));

            if ($term->numberOfLeadingZeros() >= $precision) {
                $continue = false;
            }

            $x = $x->add($term);

            $termNumber++;
        }

        return $x->roundToPrecision($precision);

    }
    
}