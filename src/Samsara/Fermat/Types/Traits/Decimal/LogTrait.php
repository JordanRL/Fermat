<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

trait LogTrait
{

    public function exp($scale = null): DecimalInterface
    {
        $scale = $scale ?? $this->getScale();

        $value = SeriesProvider::maclaurinSeries(
            Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale),
            function() use ($scale) {
                return Numbers::makeOne($scale);
            },
            function($n) use ($scale) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, $scale);

                return $n;
            },
            function($n) use ($scale) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, $scale);

                return $n->factorial();
            },
            0,
            $scale
        );

        return $this->setValue($value->getAsBaseTenRealNumber(), $scale, $this->getBase());
    }

    /**
     * @param int|null $scale The number of digits which should be accurate
     * @param bool $round Whether or not to round to the $scale value. If true, will round. If false, will truncate.
     *
     * @return DecimalInterface|NumberInterface
     */
    public function ln($scale = null, $round = true): DecimalInterface
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
     * @param null $scale
     * @param bool $round
     * @return mixed
     * @throws IntegrityConstraint
     */
    public function log10($scale = null, $round = true): DecimalInterface
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