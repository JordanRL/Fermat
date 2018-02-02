<?php

namespace Samsara\Fermat\Provider;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Values\ImmutableNumber;

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
        callable $numerator, // a function determining what the sign (+/-) is at the nth term
        callable $exponent, // a function determining the exponent of x at the nth term
        callable $denominator, // a function determining the denominator at the nth term
        $startTermAt = 0,
        $precision = 10)
    {

        $sum = Numbers::makeZero(100);
        $value = Numbers::make(Numbers::IMMUTABLE, $input->getValue());

        $continue = true;
        $termNumber = $startTermAt;

        $adjustmentOfZero = 0;

        $currentPrecision = 0;

        while ($continue) {
            $term = Numbers::makeOne(100);

            try {
                $term = $term->multiply($value->pow($exponent($termNumber)))
                    ->divide($denominator($termNumber), 100)
                    ->multiply($numerator($termNumber));
            } catch (IntegrityConstraint $constraint) {
                return $sum->truncateToPrecision($currentPrecision+1);
            }

            /** @var ImmutableNumber $term */
            if ($term->numberOfLeadingZeros() >= $precision) {
                $continue = false;
            }

            $currentPrecision = $term->numberOfLeadingZeros();

            if ($term->isEqual(0)) {
                $adjustmentOfZero++;
            } else {
                $adjustmentOfZero = 0;
            }

            if ($adjustmentOfZero > 5) {
                $continue = false;
            }

            $sum = $sum->add($term);

            $termNumber++;
        }

        return $sum->roundToPrecision($precision);

    }

    public static function genericTwoPartSeries(
        callable $part1,
        callable $part2,
        callable $exponent,
        $startTermAt = 0,
        $precision = 10)
    {

        $x = Numbers::makeZero($precision+1);

        $continue = true;
        $termNumber = $startTermAt;

        while ($continue) {
            $term = Numbers::makeOne($precision+1);

            /** @var ImmutableNumber $term */
            $term = $term->multiply($part2($termNumber))->pow($exponent($termNumber))
                ->multiply($part1($termNumber));

            if ($term->numberOfLeadingZeros()-1 >= $precision) {
                $continue = false;
            }

            $x = $x->add($term);

            $termNumber++;
        }

        return $x->roundToPrecision($precision+1);

    }
    
}