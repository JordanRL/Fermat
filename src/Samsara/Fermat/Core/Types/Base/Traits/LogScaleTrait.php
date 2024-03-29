<?php

namespace Samsara\Fermat\Core\Types\Base\Traits;

use Decimal\Decimal;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\ConstantProvider;
use Samsara\Fermat\Core\Provider\SeriesProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
trait LogScaleTrait
{

    /**
     * @param int|null $scale
     *
     * @return string
     * @throws IntegrityConstraint
     */
    protected function expScale(int $scale = null): string
    {
        $scale = $scale ?? $this->getScale();

        if (extension_loaded('decimal')) {
            $decimalScale = $scale + $this->numberOfTotalDigits() + 2;
            $num = new Decimal($this->getValue(NumberBase::Ten), $decimalScale);

            $num = $num->exp();

            return $num->toFixed($scale + 2);
        }

        if ($this->isInt()) {
            $e = Numbers::makeE($scale + $this->numberOfTotalDigits() + 2);
            $value = $e->pow($this);
        } else {
            $intScale = ($this->numberOfIntDigits()) ? ($scale + 2) * $this->numberOfIntDigits() : ($scale + 2);
            $intScale = ($this->numberOfLeadingZeros()) ? $intScale * $this->numberOfLeadingZeros() : $intScale;
            $x = $this instanceof ImmutableDecimal ? $this : new ImmutableDecimal($this->getValue(NumberBase::Ten), $intScale);
            $x2 = $x->pow(2);
            $terms = $scale;
            $six = new ImmutableDecimal(6, $intScale);

            /**
             * @package Samsara\Fermat\Core
             */
            $aPart = new class($x2, $x) implements ContinuedFractionTermInterface {
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
                 *
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

            /**
             * @package Samsara\Fermat\Core
             */
            $bPart = new class($x, $six, $intScale) implements ContinuedFractionTermInterface {
                private ImmutableDecimal $x;
                private ImmutableDecimal $six;
                private int $intScale;

                /**
                 * @param ImmutableDecimal $x
                 * @param ImmutableDecimal $six
                 */
                public function __construct(ImmutableDecimal $x, ImmutableDecimal $six, int $intScale)
                {
                    $this->x = $x;
                    $this->six = $six;
                    $this->intScale = $intScale;
                }

                /**
                 * @param int $n
                 *
                 * @return ImmutableDecimal
                 */
                public function __invoke(int $n): ImmutableDecimal
                {
                    if ($n == 0) {
                        return new ImmutableDecimal(1, $this->intScale);
                    } elseif ($n == 1) {
                        return (new ImmutableDecimal(2, $this->intScale))->subtract($this->x);
                    } elseif ($n == 2) {
                        return $this->six;
                    }

                    return $this->six->add(4 * ($n - 2));
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
     */
    protected function lnScale(int $scale = null): string
    {
        $internalScale = $scale ?? $this->getScale();
        $internalScale += 3 + $this->numberOfLeadingZeros();

        if (extension_loaded('decimal')) {
            $decimalScale = max($internalScale + 2, $this->numberOfTotalDigits() + 2);
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


        //$right = $num->toImmutable()->subtract($one)->divide($num->toImmutable()->add($one), $internalScale);
        $right = $num->subtract($one)->divide($num->add($one), $internalScale);
        $k = 0;
        do {
            $diff = $two->divide(2 * $k + 1, $internalScale)->multiply($right->pow(2 * $k + 1));

            $adjustedNum = $adjustedNum->add($diff);

            $k++;
        } while ($diff->numberOfLeadingZeros() < $internalScale - 1 && !$diff->isEqual(0));

        $answer = $adjustedNum;

        if ($exp2) {
            $answer = $answer->add(Numbers::makeNaturalLog2($internalScale + abs($exp2))->multiply($exp2));
        }

        if ($exp1p1) {
            $answer = $answer->add(
                Numbers::make(Numbers::IMMUTABLE, ConstantProvider::makeLn1p1($internalScale + $exp1p1), $internalScale + $exp1p1)
                    ->multiply($exp1p1)
            );
        }

        return $answer->getAsBaseTenRealNumber();
    }

    /**
     * @param int|null $scale
     *
     * @return string
     * @throws IntegrityConstraint
     */
    protected function log10Scale(int $scale = null): string
    {

        $internalScale = $scale ?? $this->scale;
        $internalScale += 1;

        if (extension_loaded('decimal')) {
            $decimalScale = max($internalScale + 2, $this->numberOfTotalDigits() + 2);
            $num = new Decimal($this->getValue(NumberBase::Ten), $decimalScale);
            $num = $num->log10();
            return $num->toFixed($internalScale);
        }

        $log10 = Numbers::makeNaturalLog10($internalScale + 1);

        $value = $this->ln($internalScale)->divide($log10);

        return $value->getAsBaseTenRealNumber();
    }

}