<?php

namespace Samsara\Fermat\Provider;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\NumberInterface;

class SequenceProvider
{

    /**
     * OEIS: A005408
     *
     * @param $n
     *
     * @return NumberInterface
     */
    public static function nthOddNumber($n)
    {

        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

        return $n->multiply(2)->add(1);

    }

    /**
     * OEIS: A005843
     *
     * @param $n
     *
     * @return NumberInterface
     */
    public static function nthEvenNumber($n)
    {

        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

        return $n->multiply(2);

    }

    public static function nthPowerNegativeOne($n)
    {

        $negOne = Numbers::makeOrDont(Numbers::IMMUTABLE, -1);

        return $negOne->pow($n);

    }

    /**
     * WARNING: This function is VERY unoptimized. Be careful of large m values.
     *
     * @param $m
     * @param $n
     *
     * @return NumberInterface
     */
    public static function nthBernoulliNumber($m, $n)
    {

        $m = Numbers::makeOrDont(Numbers::IMMUTABLE, $m);
        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

        if ($m->equals(0)) {
            return Numbers::makeOne();
        }

        $t = Numbers::makeZero();

        for ($k = 0;$m->isGreaterThanOrEqualTo($k);$k++) {
            $kNum = Numbers::make(Numbers::IMMUTABLE, $k);
            $combination = $m->factorial()->divide($kNum->factorial()->multiply($m->subtract($k)->factorial()));
            $t = $t->add($combination->multiply(self::nthBernoulliNumber($k, $n))->divide($m->subtract($k)->add(1)));
        }

        return $n->pow($m)->subtract($t);

    }

}