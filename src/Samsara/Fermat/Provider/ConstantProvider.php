<?php


namespace Samsara\Fermat\Provider;


use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Selectable;

class ConstantProvider
{

    public static function makePi(int $digits): string
    {

        $internalPrecision = $digits + 10;

        $C = Numbers::make(Numbers::IMMUTABLE, '10005', $internalPrecision)->sqrt($internalPrecision)->multiply(426880);
        $M = Numbers::make(Numbers::IMMUTABLE, '1', $internalPrecision);
        $L = Numbers::make(Numbers::IMMUTABLE, '13591409', $internalPrecision);
        $K = Numbers::make(Numbers::IMMUTABLE, '6', $internalPrecision);
        $X = Numbers::make(Numbers::IMMUTABLE, '1');
        $sum = Numbers::make(Numbers::MUTABLE,'0', $internalPrecision + 2);
        $termNum = 0;
        $one = Numbers::makeOne($internalPrecision);

        $continue = true;

        while ($continue) {
            $term = $M->multiply($L)->divide($X);

            if ($term->numberOfLeadingZeros() > $internalPrecision || $term->isEqual(0)) {
                $continue = false;
            }

            $sum->add($term);

            $M = $M->multiply($K->pow(3)->subtract($K->multiply(16))->divide($one->add($termNum)->pow(3), $internalPrecision));
            $L = $L->add(545140134);
            $X = $X->multiply('-262537412640768000');
            $K = $K->add(12);
            $termNum++;
        }

        $pi = $C->divide($sum, $internalPrecision);

        return $pi->truncateToPrecision($digits)->getValue();

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

        $internalPrecision = $digits + 3;

        $one = Numbers::makeOne($internalPrecision+5)->setMode(Selectable::CALC_MODE_PRECISION);
        $denominator = Numbers::make(Numbers::MUTABLE, '1', $internalPrecision)->setMode(Selectable::CALC_MODE_PRECISION);
        $e = Numbers::make(NUmbers::MUTABLE, '2', $internalPrecision)->setMode(Selectable::CALC_MODE_PRECISION);
        $n = Numbers::make(Numbers::MUTABLE, '2', $internalPrecision)->setMode(Selectable::CALC_MODE_PRECISION);

        $continue = true;

        while ($continue) {
            $denominator->multiply($n);
            $n->add($one);
            $term = $one->divide($denominator);

            if ($term->numberOfLeadingZeros() > $internalPrecision || $term->isEqual(0)) {
                $continue = false;
            }

            $e->add($term);
        }

        return $e->truncateToPrecision($digits)->getValue();

    }

}