<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
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

        $num = new ImmutableDecimal($this->getAsBaseTenRealNumber(), $internalScale);

        $e = Numbers::makeE($internalScale);
        $e2 = $e->multiply(2);
        $adjustedNum = Numbers::makeZero($internalScale);

        $eExp = 0;

        while ($num->isLessThanOrEqualTo($e2)) {
            $num = $num->multiply($e);
            $eExp--;
        }

        while ($num->isGreaterThan($e2)) {
            $num = $num->divide($e, $internalScale);
            $eExp++;
        }

        $expComponent = Numbers::makeZero();

        do {
            if ($adjustedNum->isEqual(0)) {
                $adjustedNum = $adjustedNum->add($num->subtract(1)->divide($num->add(1), $internalScale)->multiply(2));
            } else {
                $adjustedNum = $adjustedNum->add(
                    $num->subtract($expComponent)->divide($num->add($expComponent), $internalScale)->multiply(2)
                );
            }

            $expComponent = $adjustedNum->exp($internalScale);

            $diff = $expComponent->subtract($num)->truncateToScale($internalScale-2);
        } while (!$diff->isEqual(0));

        $answer = $adjustedNum->add($eExp);

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

        $log10 = Numbers::makeNaturalLog10($internalScale+1);

        $value = $this->ln($internalScale)->divide($log10);

        return $value->getAsBaseTenRealNumber();
    }

}