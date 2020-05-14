<?php


namespace Samsara\Fermat\Provider;


use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Selectable;
use Samsara\Fermat\Values\ImmutableDecimal;

class ConstantProvider
{

    public static function makePi(int $digits): string
    {

        // TODO: Implement https://en.wikipedia.org/wiki/Chudnovsky_algorithm

    }

    /**
     * @param int $digits
     * @return string
     * @throws IntegrityConstraint
     */
    public static function makeE(int $digits): string
    {

        $internalPrecision = $digits + 5;

        $one = Numbers::makeOne($internalPrecision)->setMode(Selectable::CALC_MODE_PRECISION);
        $denominator = Numbers::make(Numbers::MUTABLE, '1', $internalPrecision)->setMode(Selectable::CALC_MODE_PRECISION);
        $e = Numbers::make(NUmbers::MUTABLE, '2', $internalPrecision)->setMode(Selectable::CALC_MODE_PRECISION);
        $n = Numbers::make(Numbers::MUTABLE, '2', $internalPrecision)->setMode(Selectable::CALC_MODE_PRECISION);

        $continue = true;

        while ($continue) {
            $denominator->multiply($n);
            $n->add($one);
            $term = $one->divide($denominator);

            if ($term->numberOfLeadingZeros() > $internalPrecision) {
                $continue = false;
            }

            $e->add($term);
        }

        return $e->truncateToPrecision($digits)->getValue();

    }

}