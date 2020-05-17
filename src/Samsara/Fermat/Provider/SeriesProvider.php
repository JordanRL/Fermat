<?php

namespace Samsara\Fermat\Provider;

use ReflectionException;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

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
     * The function continues adding terms until a term has MORE leading zeros than the $scale setting. (That is,
     * until it adds zero to the total when considering significant digits.)
     *
     * @param SimpleNumberInterface $input
     * @param callable $numerator
     * @param callable $exponent
     * @param callable $denominator
     * @param int $startTermAt
     * @param int $scale
     * @param int $consecutiveDivergeLimit
     * @param int $totalDivergeLimit
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws OptionalExit
     * @throws ReflectionException
     */
    public static function maclaurinSeries(
        SimpleNumberInterface $input, // x value in series
        callable $numerator, // a function determining what the sign (+/-) is at the nth term
        callable $exponent, // a function determining the exponent of x at the nth term
        callable $denominator, // a function determining the denominator at the nth term
        int $startTermAt = 0,
        int $scale = 10,
        int $consecutiveDivergeLimit = 5,
        int $totalDivergeLimit = 10): ImmutableDecimal
    {

        ++$scale;

        $sum = Numbers::makeZero($scale);
        $value = Numbers::make(Numbers::IMMUTABLE, $input->getValue(), $scale);

        $continue = true;
        $termNumber = $startTermAt;

        $adjustmentOfZero = 0;
        $prevDiff = Numbers::makeZero($scale);
        $prevSum = $sum;
        $divergeCount = -1;
        $persistentDivergeCount = -1;
        $currentScale = 0;

        while ($continue) {
            $term = Numbers::makeOne($scale);

            try {
                $exTerm = $value->pow($exponent($termNumber));
                $term = $term->multiply($exTerm);
                $term = $term->divide($denominator($termNumber));
                $term = $term->multiply($numerator($termNumber));
            } catch (IntegrityConstraint $constraint) {
                return $sum->truncateToScale($currentScale+1);
            }

            /** @var ImmutableDecimal $term */
            if ($term->numberOfLeadingZeros() >= $scale && !$term->isWhole()) {
                $continue = false;
            }

            $currentScale = $term->numberOfLeadingZeros();

            if ($term->isEqual(0)) {
                $adjustmentOfZero++;
            } else {
                $adjustmentOfZero = 0;
            }

            if ($adjustmentOfZero > 15) {
                $continue = false;
            }

            /** @var ImmutableDecimal $sum */
            $sum = $sum->add($term);
            $currDiff = $sum->subtract($prevSum)->abs();

            if ($prevDiff->isLessThan($currDiff)) {
                $divergeCount++;
                $persistentDivergeCount++;
            } else {
                $divergeCount = 0;
            }

            if ($divergeCount === $consecutiveDivergeLimit || $persistentDivergeCount === $totalDivergeLimit) {
                throw new OptionalExit(
                    'Series appear to be diverging. Current diverge count: '.$divergeCount.' | Persistent diverge count: '.$persistentDivergeCount,
                    'A call was made to SeriesProvider::maclaurinSeries() that seems to be diverging. Exiting the loop.'
                );
            }

            $prevDiff = $currDiff;
            $prevSum = $sum;

            $termNumber++;
        }

        return $sum->roundToScale($scale);

    }
    
}