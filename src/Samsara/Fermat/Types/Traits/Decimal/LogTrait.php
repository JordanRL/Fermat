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

    public function exp($precision = null): DecimalInterface
    {
        $precision = $precision ?? $this->getPrecision();

        $value = SeriesProvider::maclaurinSeries(
            Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision),
            function() use ($precision) {
                return Numbers::makeOne($precision);
            },
            function($n) use ($precision) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, $precision);

                return $n;
            },
            function($n) use ($precision) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, $precision);

                return $n->factorial();
            },
            0,
            $precision
        );

        return $this->setValue($value->getAsBaseTenRealNumber(), $precision, $this->getBase());
    }

    /**
     * @param int|null $precision The number of digits which should be accurate
     * @param bool $round Whether or not to round to the $precision value. If true, will round. If false, will truncate.
     *
     * @return DecimalInterface|NumberInterface
     */
    public function ln($precision = null, $round = true): DecimalInterface
    {
        /*
        if ($this->isGreaterThanOrEqualTo(PHP_INT_MIN) && $this->isLessThanOrEqualTo(PHP_INT_MAX) && $precision <= 10) {
            return $this->setValue(log($this->getValue()));
        }
        */

        $internalPrecision = $precision ?? $this->getPrecision();
        $internalPrecision += 5;

        $oldPrecision = $this->getPrecision();
        $this->precision = $internalPrecision;

        $num = new ImmutableDecimal($this->getAsBaseTenRealNumber(), $internalPrecision);

        $e = Numbers::makeE($internalPrecision);
        $e2 = $e->multiply(2);
        $adjustedNum = Numbers::makeZero($internalPrecision);

        $eExp = 0;

        while ($num->isGreaterThan($e2)) {
            $num = $num->divide($e, $internalPrecision);
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
                $adjustedNum = $adjustedNum->add($num->subtract(1)->divide($num->add(1), $internalPrecision)->multiply(2));
            } else {
                $adjustedNum = $adjustedNum->add(
                    $num->subtract($expComponent)->divide($num->add($expComponent), $internalPrecision)->multiply(2)
                );
            }

            $expComponent = $adjustedNum->exp($internalPrecision);

            $diff = $expComponent->subtract($num)->truncateToPrecision($internalPrecision-4);

            //echo 'Internal Precision: '.$internalPrecision.' | Object Precision: '.$this->getPrecision().' Num Precision: '.$num->getPrecision().' | Adjusted Num Precision: '.$adjustedNum->getPrecision().PHP_EOL;
            //$count++;
        } while (!$diff->isEqual(0) && $count < 15);

        $answer = $adjustedNum->add($eExp);

        if ($round) {
            $answer = $answer->roundToPrecision($internalPrecision-5);
        } else {
            $answer = $answer->truncateToPrecision($internalPrecision-5);
        }

        $this->precision = $oldPrecision;

        return $this->setValue($answer->getAsBaseTenRealNumber(), $precision, $this->getBase());
    }

    /**
     * @param null $precision
     * @param bool $round
     * @return mixed
     * @throws IntegrityConstraint
     */
    public function log10($precision = null, $round = true): DecimalInterface
    {
        $log10 = Numbers::makeNaturalLog10();

        $internalPrecision = $precision ?? $this->precision;
        $internalPrecision += 1;

        $value = $this->ln($internalPrecision)->divide($log10);

        if ($round) {
            $value = $value->roundToPrecision($internalPrecision-1);
        } else {
            $value = $value->truncateToPrecision($internalPrecision-1);
        }

        return $this->setValue($value->getAsBaseTenRealNumber(), $precision, $this->getBase());
    }

}