<?php


namespace Samsara\Fermat\Provider;


use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\CalcMode;
use Samsara\Fermat\Numbers;

class ConstantProvider
{

    public static function makePi(int $digits): string
    {

        $internalScale = $digits + 10;

        $C = Numbers::make(Numbers::IMMUTABLE, '10005', $internalScale)->setMode(CalcMode::Precision)->sqrt($internalScale)->multiply(426880);
        $M = Numbers::make(Numbers::IMMUTABLE, '1', $internalScale)->setMode(CalcMode::Precision);
        $L = Numbers::make(Numbers::IMMUTABLE, '13591409', $internalScale)->setMode(CalcMode::Precision);
        $K = Numbers::make(Numbers::IMMUTABLE, '6', $internalScale)->setMode(CalcMode::Precision);
        $X = Numbers::make(Numbers::IMMUTABLE, '1')->setMode(CalcMode::Precision);
        $sum = Numbers::make(Numbers::MUTABLE,'0', $internalScale + 2)->setMode(CalcMode::Precision);
        $termNum = 0;
        $one = Numbers::makeOne($internalScale)->setMode(CalcMode::Precision);

        $continue = true;

        while ($continue) {
            $term = $M->multiply($L)->divide($X);

            if ($termNum > $internalScale) {
                $continue = false;
            }

            $sum->add($term);

            $M = $M->multiply($K->pow(3)->subtract($K->multiply(16))->divide($one->add($termNum)->pow(3), $internalScale));
            $L = $L->add(545140134);
            $X = $X->multiply('-262537412640768000');
            $K = $K->add(12);
            $termNum++;
        }

        $pi = $C->divide($sum, $internalScale);

        return $pi->truncateToScale($digits)->getValue();

    }

    /**
     * Consider also: sum [0 -> INF] { (2n + 2) / (2n + 1)! }
     *
     * This converges faster (though it's unclear if the calculation is actually faster), and can be represented by this
     * set of Fermat calls:
     *
     * SequenceProvider::nthEvenNumber($n + 1)->divide(SequenceProvider::nthOddNumber($n)->factorial());
     *
     * Perhaps by substituting the nthOddNumber()->factorial() call with something tracked locally, the performance can
     * be improved. Current performance is acceptable even out past 200 digits.
     *
     * @param int $digits
     * @return string
     * @throws IntegrityConstraint
     */
    public static function makeE(int $digits): string
    {

        $internalScale = $digits + 3;

        $one = Numbers::makeOne($internalScale+5)->setMode(CalcMode::Precision);
        $denominator = Numbers::make(Numbers::MUTABLE, '1', $internalScale)->setMode(CalcMode::Precision);
        $e = Numbers::make(NUmbers::MUTABLE, '2', $internalScale)->setMode(CalcMode::Precision);
        $n = Numbers::make(Numbers::MUTABLE, '2', $internalScale)->setMode(CalcMode::Precision);

        $continue = true;

        while ($continue) {
            $denominator->multiply($n);
            $n->add($one);
            $term = $one->divide($denominator);

            if ($term->numberOfLeadingZeros() > $internalScale || $term->isEqual(0)) {
                $continue = false;
            }

            $e->add($term);
        }

        return $e->truncateToScale($digits)->getValue();

    }

}