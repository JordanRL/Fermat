<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Decimal\Decimal;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ConstantProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 *
 */
trait LogScaleTrait
{

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public function expScale(int $scale = null): string
    {
        $scale = $scale ?? $this->getScale();

        if (extension_loaded('decimal')) {
            $decimalScale = max($scale*2, $this->numberOfTotalDigits()*2);
            $num = new Decimal($this->getValue(NumberBase::Ten), $decimalScale);

            $num = $num->exp();

            return $num->toFixed($scale+2);
        }

        if ($this->isInt()) {
            $e = Numbers::makeE($scale+1);
            $value = $e->pow($this);
        } else {
            $x = $this instanceof ImmutableDecimal ? $this : new ImmutableDecimal($this->getValue(NumberBase::Ten));
            $x2 = $x->pow(2);
            $intScale = $scale + 1;
            $terms = $scale;
            $six = new ImmutableDecimal(6);

            $aPart = new class($x2, $x) implements ContinuedFractionTermInterface{
                private ImmutableDecimal $x2;
                private ImmutableDecimal $x;

                /**
                 * @param ImmutableDecimal $x2
                 * @param ImmutableDecimal $x
                 */
                public function __construct(ImmutableDecimal $x2, ImmutableDecimal $x)
                {
                    $this->x2 = $x2;
                    $this->x = $x;
                }

                /**
                 * @param int $n
                 * @return ImmutableDecimal
                 */
                public function __invoke(int $n): ImmutableDecimal
                {
                    if ($n == 1) {
                        return $this->x->multiply(2);
                    }

                    return $this->x2;
                }
            };

            $bPart = new class($x, $six) implements ContinuedFractionTermInterface{
                private ImmutableDecimal $x;
                private ImmutableDecimal $six;

                /**
                 * @param ImmutableDecimal $x
                 * @param ImmutableDecimal $six
                 */
                public function __construct(ImmutableDecimal $x, ImmutableDecimal $six)
                {
                    $this->x = $x;
                    $this->six = $six;
                }

                /**
                 * @param int $n
                 * @return ImmutableDecimal
                 */
                public function __invoke(int $n): ImmutableDecimal
                {
                    if ($n == 0) {
                        return new ImmutableDecimal(1);
                    } elseif ($n == 1) {
                        return (new ImmutableDecimal(2))->subtract($this->x);
                    } elseif ($n == 2) {
                        return $this->six;
                    }

                    return $this->six->add(4*($n-2));
                }
            };

            $value = SeriesProvider::generalizedContinuedFraction($aPart, $bPart, $terms, $intScale);
        }

        return $value->getAsBaseTenRealNumber();
    }

    /**
     * @param int|null $scale The number of digits which should be accurate
     *
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public function lnScale(int $scale = null): string
    {
        $internalScale = $scale ?? $this->getScale();
        $internalScale += 3;

        if (extension_loaded('decimal')) {
            $decimalScale = max($internalScale*2, $this->numberOfTotalDigits()*2);
            $num = new Decimal($this->getValue(NumberBase::Ten), $decimalScale);
            $num = $num->ln();
            return $num->toFixed($internalScale);
        }

        $num = new ImmutableDecimal($this->getAsBaseTenRealNumber(), $internalScale);

        $two = Numbers::make(Numbers::IMMUTABLE, 2, $internalScale);
        $one = Numbers::makeOne($internalScale);
        $adjustedNum = Numbers::makeZero($internalScale);
        $pointFour = Numbers::make(Numbers::IMMUTABLE, '0.4', $internalScale);
        $pointNine = Numbers::make(Numbers::IMMUTABLE, '0.9', $internalScale);
        $onePointOne = Numbers::make(Numbers::IMMUTABLE, '1.1', $internalScale);
        $twoPointFive = Numbers::make(Numbers::IMMUTABLE, '1.5', $internalScale);

        $exp2 = 0;
        $exp1p1 = 0;

        if ($num->isLessThan($one)) {
            while ($num->isLessThan($pointFour)) {
                $num = $num->multiply($two);
                $exp2--;
            }

            while ($num->isLessThan($pointNine)) {
                $num = $num->multiply($onePointOne);
                $exp1p1--;
            }
        } elseif ($num->isGreaterThan($one)) {
            while ($num->isGreaterThan($twoPointFive)) {
                $num = $num->divide($two, $internalScale);
                $exp2++;
            }

            while ($num->isGreaterThan($onePointOne)) {
                $num = $num->divide($onePointOne, $internalScale);
                $exp1p1++;
            }
        }



        $right = $num->subtract(1)->divide($num->add(1), $internalScale);
        $k = 0;
        do {
            $left = $two->divide($two->multiply($k)->add(1), $internalScale);
            $diff = $left->multiply($right->pow(2*$k+1));

            $adjustedNum = $adjustedNum->add($diff);

            $k++;
        } while ($diff->numberOfLeadingZeros() < $internalScale-1 && !$diff->isEqual(0));
        /**/

        $answer = $adjustedNum;

        if ($exp2) {
            $answer = $answer->add(Numbers::makeNaturalLog2($internalScale + $exp2)->multiply($exp2));
        }

        if ($exp1p1) {
            $answer = $answer->add(
                Numbers::make(Numbers::IMMUTABLE, ConstantProvider::makeLn1p1($internalScale+$exp1p1), $internalScale+$exp1p1)
                ->multiply($exp1p1)
            );
        }

        return $answer->getAsBaseTenRealNumber();
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    public function log10Scale(int $scale = null): string
    {

        $internalScale = $scale ?? $this->scale;
        $internalScale += 1;

        if (extension_loaded('decimal')) {
            $decimalScale = max($internalScale*2, $this->numberOfTotalDigits()*2);
            $num = new Decimal($this->getValue(NumberBase::Ten), $decimalScale);
            $num = $num->log10();
            return $num->toFixed($internalScale);
        }

        $log10 = Numbers::makeNaturalLog10($internalScale+1);

        $value = $this->ln($internalScale)->divide($log10);

        return $value->getAsBaseTenRealNumber();
    }

}