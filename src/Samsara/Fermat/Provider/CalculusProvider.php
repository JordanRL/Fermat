<?php

namespace Samsara\Fermat\Provider;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\ImmutableNumber;

class CalculusProvider
{
    /**
     * @param          $left
     * @param          $right
     * @param callable $integral
     * @param int      $precision
     *
     * @return ImmutableNumber
     * @throws IntegrityConstraint
     */
    public static function definiteIntegral($left, $right, callable $integral, int $precision = 10): ImmutableNumber
    {
        $left = Numbers::makeOrDont(Numbers::IMMUTABLE, $left);
        $right = Numbers::makeOrDont(Numbers::IMMUTABLE, $right);
        $diff = $left->subtract($right)->abs();

        $continue = true;

        $loop = Numbers::makeOne();
        $prevArea = Numbers::makeZero();
        $safetyCount = Numbers::makeZero();

        while ($continue) {
            $q = Numbers::makeZero();
            $loopDiff = $diff->divide($loop);
            $calculate = true;
            $pos = $left;

            while ($calculate) {
                $a = $pos;
                $b = $pos->add($loopDiff);

                $resultA = Numbers::makeOrDont(Numbers::IMMUTABLE, $integral($a));
                $resultB = Numbers::makeOrDont(Numbers::IMMUTABLE, $integral($b));

                $slant = $resultA->add($resultB)->divide(2);

                $area = $slant->multiply($loopDiff);
                $q = $q->add($area);

                $pos = $b;

                if ($pos->isEqual($right)) {
                    $calculate = false;
                }
            }

            if ($prevArea->subtract($q)->abs()->numberOfLeadingZeros() > $precision) {
                $safetyCount = $safetyCount->add(1);
            } else {
                $safetyCount = Numbers::makeZero();
            }

            if ($safetyCount->isEqual(3)) {
                $continue = false;
            }

            $prevArea = $q;
            $loop = $loop->add(1);
        }

        return $q;
    }

}