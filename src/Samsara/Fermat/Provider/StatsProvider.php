<?php

namespace Samsara\Fermat\Provider;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\NumberInterface;

class StatsProvider
{

    /**
     * @param $x
     *
     * @return NumberInterface
     */
    public static function normalCDF($x)
    {
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        $pi = Numbers::makePi();
        $e = Numbers::makeE();
        $one = Numbers::makeOne();

        $eExponent = Numbers::make(Numbers::IMMUTABLE, $x->getValue());
        $eExponent->pow(2)->divide(2)->multiply(-1);

        $answer = Numbers::make(Numbers::IMMUTABLE, 0.5);
        $answer = $answer->add(
            $one->divide($pi->multiply(2)->sqrt())
                ->multiply($e->pow($eExponent))
                ->multiply(SeriesProvider::maclaurinSeries(
                    $x,
                    function ($n) {
                        return Numbers::makeOne();
                    },
                    function ($n) {
                        return SequenceProvider::nthOddNumber($n);
                    },
                    function ($n) {
                        return SequenceProvider::nthOddNumber($n)->doubleFactorial();
                    }
                ))
        );

        return $answer;

    }

    public static function complementNormalCDF($x)
    {
        $p = self::normalCDF($x);
        $one = Numbers::makeOne();

        return $one->subtract($p);
    }

    public static function gaussErrorFunction($x)
    {

        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);
        $answer = Numbers::makeOne();
        $pi = Numbers::makePi();

        $answer = $answer->multiply(2)->divide($pi->sqrt());

        $answer = $answer->multiply(
            SeriesProvider::maclaurinSeries(
                $x,
                function ($n) {
                    $negOne = Numbers::make(Numbers::IMMUTABLE, -1);

                    return $negOne->pow($n);
                },
                function ($n) {
                    return SequenceProvider::nthOddNumber($n);
                },
                function ($n) {
                    $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

                    return $n->factorial()->multiply(SequenceProvider::nthOddNumber($n));
                }
            )
        );

        return $answer;

    }

    public static function inverseNormalCDF($p, $precision = 10)
    {
        $pi = Numbers::makePi();
        $r2pi = $pi->multiply(2)->sqrt();
        $e = Numbers::makeE();
        $p = Numbers::makeOrDont(Numbers::IMMUTABLE, $p);

        $continue = true;

        $xCur = Numbers::make(Numbers::IMMUTABLE, $p);

        while ($continue) {

            $cumulative = self::normalCDF($xCur);
            $dx = $cumulative->subtract($p)->divide($r2pi->multiply($e->pow($xCur->pow(2))->divide(-2)));
            $xCur = $xCur->subtract($dx);

            if ($dx->numberOfLeadingZeros() > $precision) {
                $continue = false;
            }

        }

        if ($p->isLessThan(0.5)) {
            return $xCur->multiply(-1);
        } else {
            return $xCur;
        }
    }

    public static function binomialCoefficient($n, $k)
    {

        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);
        $k = Numbers::makeOrDont(Numbers::IMMUTABLE, $k);

        if ($k->isLessThan(0) || $n->isLessThan($k)) {
            throw new IntegrityConstraint(
                '$k must be larger or equal to 0 and less than or equal to $n',
                'Provide valid $n and $k values such that 0 <= $k <= $n',
                'For $n choose $k, the values of $n and $k must satisfy the inequality 0 <= $k <= $n'
            );
        }

        if (!$n->isInt() || !$k->isInt()) {
            throw new IntegrityConstraint(
                '$k and $n must be whole numbers',
                'Provide whole numbers for $n and $k',
                'For $n choose $k, the values $n and $k must be whole numbers'
            );
        }

        return $n->factorial()->divide($k->factorial()->multiply($n->subtract($k)->factorial()));

    }

}