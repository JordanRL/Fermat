<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Values\ImmutableNumber;

trait LogTrait
{

    public function exp()
    {
        $oldBase = $this->convertForModification();

        $value = SeriesProvider::maclaurinSeries(
            Numbers::makeOrDont(Numbers::IMMUTABLE, $this),
            function() {
                return Numbers::makeOne();
            },
            function($n) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

                return $n;
            },
            function($n) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

                return $n->factorial();
            },
            0,
            $this->getPrecision()
        );

        return $this->setValue($value)->convertFromModification($oldBase);
    }

    /**
     * @param int $precision The number of digits which should be accurate
     * @param bool $round Whether or not to round to the $precision value. If true, will round. If false, will truncate.
     *
     * @return DecimalInterface|NumberInterface
     */
    public function ln($precision = 10, $round = true)
    {
        $oldBase = $this->convertForModification();

        if ($this->isGreaterThanOrEqualTo(PHP_INT_MIN) && $this->isLessThanOrEqualTo(PHP_INT_MAX) && $precision <= 10) {
            return $this->setValue(log($this->getValue()))->convertFromModification($oldBase);
        }

        $internalPrecision = ($precision+1 > strlen($this->value)) ? $precision+1 : strlen($this->value);

        $this->precision = $internalPrecision;

        $ePow = 0;
        $eDiv = 1;
        $e = Numbers::makeE();

        if ($this->isGreaterThan(10)) {
            $continue = true;
            do {
                $prevDiv = $eDiv;
                $eDiv = $e->pow($ePow);

                if ($eDiv->isGreaterThan($this)) {
                    $continue = false;
                } else {
                    $ePow++;
                }
            } while ($continue);

            $ePow--;
            $eDiv = $prevDiv;
        }

        $adjustedNum = $this->divide($eDiv, $internalPrecision);

        /** @var ImmutableNumber $y */
        $y = Numbers::makeOne($internalPrecision);
        $y = $y->multiply($adjustedNum->subtract(1))->divide($adjustedNum->add(1), $internalPrecision);

        $answer = SeriesProvider::genericTwoPartSeries(
            function($term) use ($y, $internalPrecision) {
                $two = Numbers::make(Numbers::IMMUTABLE, 2, $internalPrecision);
                $odd = SequenceProvider::nthOddNumber($term);

                return $two->divide($odd, $internalPrecision);
            },
            function($term) use ($y) {
                return $y;
            },
            function($term) {
                return SequenceProvider::nthOddNumber($term);
            },
            0,
            $internalPrecision
        );

        $answer = $answer->add($ePow);

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);
    }

    public function log10($precision = 10, $round = true)
    {
        $log10 = Numbers::makeNaturalLog10();

        $value = $this->ln($precision+1)->divide($log10, $precision+1);

        if ($round) {
            $value = $value->roundToPrecision($precision);
        } else {
            $value = $value->truncateToPrecision($precision);
        }

        return $this->setValue($value);
    }

}