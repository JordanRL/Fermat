<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

trait LogTrait
{

    public function exp(int $scale = null, bool $round = true): DecimalInterface
    {
        $scale = $scale ?? $this->getScale();

        if ($scale <= 98 && $this->isInt()) {
            $e = Numbers::makeE($scale+$this->asInt());
            $value = $e->pow($this);
        } else {
            $x = $this instanceof ImmutableDecimal ? $this : new ImmutableDecimal($this->getValue());
            $x2 = $x->pow(2);
            $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
            $intScale = $scale + $abs->asInt();
            $six = new ImmutableDecimal(6);

            $aPart = new class($x2, $x) implements ContinuedFractionTermInterface{
                private ImmutableDecimal $x2;
                private ImmutableDecimal $x;

                public function __construct(ImmutableDecimal $x2, ImmutableDecimal $x)
                {
                    $this->x2 = $x2;
                    $this->x = $x;
                }

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

                public function __construct(ImmutableDecimal $x, ImmutableDecimal $six)
                {
                    $this->x = $x;
                    $this->six = $six;
                }

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

            $value = SeriesProvider::generalizedContinuedFraction($aPart, $bPart, $intScale, $intScale);
        }

        $value = ($round ? $value->roundToScale($scale) : $value->truncateToScale($scale));

        return $this->setValue($value->getAsBaseTenRealNumber(), $scale, $this->getBase());
    }

    /**
     * @param int|null $scale The number of digits which should be accurate
     *
     * @return DecimalInterface
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public function ln(int $scale = null, bool $round = true): DecimalInterface
    {
        /*
        if ($this->isGreaterThanOrEqualTo(PHP_INT_MIN) && $this->isLessThanOrEqualTo(PHP_INT_MAX) && $scale <= 10) {
            return $this->setValue(log($this->getValue()));
        }
        */

        $internalScale = $scale ?? $this->getScale();
        $internalScale += 5;

        $oldScale = $this->getScale();
        $this->scale = $internalScale;

        $num = new ImmutableDecimal($this->getAsBaseTenRealNumber(), $internalScale);

        $e = Numbers::makeE($internalScale);
        $e2 = $e->multiply(2);
        $adjustedNum = Numbers::makeZero($internalScale);

        $eExp = 0;

        while ($num->isGreaterThan($e2)) {
            $num = $num->divide($e, $internalScale);
            $eExp++;
        }

        while ($num->isLessThan($e)) {
            $num = $num->multiply($e);
            $eExp--;
        }

        $count = 0;
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

            $diff = $expComponent->subtract($num)->truncateToScale($internalScale-4);

            //echo 'Internal Scale: '.$internalScale.' | Object Scale: '.$this->getScale().' Num Scale: '.$num->getScale().' | Adjusted Num Scale: '.$adjustedNum->getScale().PHP_EOL;
            //$count++;
        } while (!$diff->isEqual(0) && $count < 15);

        $answer = $adjustedNum->add($eExp);

        if ($round) {
            $answer = $answer->roundToScale($internalScale-5);
        } else {
            $answer = $answer->truncateToScale($internalScale-5);
        }

        $this->scale = $oldScale;

        return $this->setValue($answer->getAsBaseTenRealNumber(), $scale, $this->getBase());
    }

    /**
     * @param int|null $scale
     * @return mixed
     * @throws IntegrityConstraint|MissingPackage
     */
    public function log10(int $scale = null, bool $round = true): DecimalInterface
    {

        $internalScale = $scale ?? $this->scale;
        $internalScale += 2;

        $log10 = Numbers::makeNaturalLog10($internalScale);

        $value = $this->ln($internalScale)->divide($log10);

        if ($round) {
            $value = $value->roundToScale($internalScale-2);
        } else {
            $value = $value->truncateToScale($internalScale-2);
        }

        return $this->setValue($value->getAsBaseTenRealNumber(), $scale, $this->getBase());
    }

}