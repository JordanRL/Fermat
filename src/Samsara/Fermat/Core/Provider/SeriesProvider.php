<?php

namespace Samsara\Fermat\Core\Provider;

use ReflectionException;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
class SeriesProvider
{

    public const SUM_MODE_ADD = 1;
    public const SUM_MODE_SUB = 2;
    public const SUM_MODE_ALT_ADD = 3;
    public const SUM_MODE_ALT_SUB = 4;
    public const SUM_MODE_ALT_FIRST_ADD = 5;
    public const SUM_MODE_ALT_FIRST_SUB = 6;

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
        $value = Numbers::make(Numbers::IMMUTABLE, $input->getValue(NumberBase::Ten), $scale);

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
                $term = $term->divide($denominator($termNumber), $scale);
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
                    'A call was made to SeriesProvider::maclaurinSeries() that seems to be diverging. Exiting the loop.',
                    'The series being calculated appears to be diverging, and the process has been stopped in an attempt to avoid an infinite loop.'
                );
            }

            $prevDiff = $currDiff;
            $prevSum = $sum;

            $termNumber++;
        }

        return $sum->roundToScale($scale);

    }

    /**
     * This function processes a generalized continued fraction. In order to use this you must provide
     * two callable classes that implement the ContinuedFractionTermInterface. This interface defines the
     * expected inputs and outputs of the callable used by this function.
     *
     * This function evaluates continued fractions in the form:
     *
     * b0 + (a1 / (b1 + (a2 / (b2 + (a3 / b3 + ...)))))
     *
     * This is a continued fraction in the form used in complex analysis, referred to as a generalized continued fraction.
     *
     * For more information about this, please read the wikipedia article on the subject:
     *
     * [https://en.wikipedia.org/wiki/Generalized_continued_fraction](https://en.wikipedia.org/wiki/Generalized_continued_fraction)
     *
     * @param ContinuedFractionTermInterface $aPart
     * @param ContinuedFractionTermInterface $bPart
     * @param int $terms
     * @param int $scale
     * @param int $sumMode
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public static function generalizedContinuedFraction(
        ContinuedFractionTermInterface $aPart,
        ContinuedFractionTermInterface $bPart,
        int $terms,
        int $scale,
        int $sumMode = self::SUM_MODE_ADD
    ): ImmutableDecimal
    {

        $intScale = $scale;
        $start = $bPart(0);
        $prevDenominator = new ImmutableDecimal(0, $intScale);
        $loop = 0;

        for ($i = $terms;$i > 0;$i--) {
            $loop++;
            switch ($sumMode) {
                case self::SUM_MODE_ADD:
                case self::SUM_MODE_ALT_FIRST_SUB:
                    $prevDenominator = $bPart($i)->add($prevDenominator);
                    break;

                case self::SUM_MODE_SUB:
                case self::SUM_MODE_ALT_FIRST_ADD:
                    $prevDenominator = $bPart($i)->subtract($prevDenominator);
                    break;
            }

            if ($prevDenominator->isEqual(0)) {
                throw new IntegrityConstraint(
                    'Cannot divide by zero',
                    'balh',
                    'Current $i: '.$i.' | Current terms: '.$terms.' | Loop: '.$loop
                );
            } else {
                $prevDenominator = $aPart($i)->divide($prevDenominator, $intScale);
            }
        }

        switch ($sumMode) {
            case self::SUM_MODE_SUB:
            case self::SUM_MODE_ALT_FIRST_SUB:
                $result = $start->subtract($prevDenominator);
                break;

            case self::SUM_MODE_ALT_FIRST_ADD:
            case self::SUM_MODE_ADD:
                $result = $start->add($prevDenominator);
                break;
        }

        return $result;

    }
    
}