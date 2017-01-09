<?php

namespace Samsara\Fermat\Provider\Stats;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\NumberInterface;

class Stats
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

        if ($p->lessThan(0.5)) {
            return $xCur->multiply(-1);
        } else {
            return $xCur;
        }
    }

}