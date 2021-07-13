<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

trait LogTrait
{

    public function exp(int $scale = null, bool $round = true): DecimalInterface
    {
        $scale = $scale ?? $this->getScale();

        if ($scale <= 98 && $this->isInt()) {
            $e = Numbers::makeE($scale+1);
            $value = $e->pow($this);
        } else {
            $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
            $x = $this instanceof ImmutableDecimal ? $this : new ImmutableDecimal($this->getValue());
            $x2 = $x->pow(2);
            $intScale = $scale + $abs->asInt();

            $one = new ImmutableDecimal(1);
            $two = new ImmutableDecimal(2);
            $six = new ImmutableDecimal(6);
            $prevDenom = new ImmutableDecimal(0);

            for ($i = $intScale; $i >= 0; $i--) {
                $thisDenom = $six->add(4 * $i)->add($prevDenom);
                $prevDenom = $x2->divide($thisDenom, $intScale);
            }
            $value = $one->add($x->multiply(2)->divide($two->subtract($x)->add($prevDenom), $intScale));
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
        $log10 = Numbers::makeNaturalLog10();

        $internalScale = $scale ?? $this->scale;
        $internalScale += 1;

        $value = $this->ln($internalScale)->divide($log10);

        if ($round) {
            $value = $value->roundToScale($internalScale-1);
        } else {
            $value = $value->truncateToScale($internalScale-1);
        }

        return $this->setValue($value->getAsBaseTenRealNumber(), $scale, $this->getBase());
    }

}