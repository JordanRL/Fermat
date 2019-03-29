<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;

trait TrigonometryTrait
{

    public function sin($precision = null, $round = true)
    {
        if ($this->isEqual(0)) {
            return $this;
        }

        $oldBase = $this->convertForModification();

        $precision = $precision ?? $this->getPrecision();

        $twoPi = Numbers::make2Pi();
        $pi = Numbers::makePi();

        if ($pi->truncate($precision)->isEqual($this) || $twoPi->truncate($precision)->isEqual($this)) {
            return $this->setValue(0);
        }

        $modulo = $this->continuousModulo($twoPi);

        $answer = SeriesProvider::maclaurinSeries(
            $modulo,
            function ($n) {
                $negOne = Numbers::make(Numbers::IMMUTABLE, -1, 100);

                return $negOne->pow($n);
            },
            function ($n) {
                return SequenceProvider::nthOddNumber($n);
            },
            function ($n) {
                return SequenceProvider::nthOddNumber($n)->factorial();
            },
            0,
            $precision+1
        );

        if ($round) {
            return $this->setValue($answer->getValue())->roundToPrecision($precision)->convertFromModification($oldBase);
        } else {
            return $this->setValue($answer->getValue())->truncateToPrecision($precision)->convertFromModification($oldBase);
        }
    }

    public function cos($precision = null, $round = true)
    {
        if ($this->isEqual(0)) {
            return $this->setValue(1);
        }

        $oldBase = $this->convertForModification();

        $precision = $precision ?? $this->getPrecision();

        $twoPi = Numbers::make2Pi();
        $pi = Numbers::makePi();

        if ($twoPi->truncate($precision)->isEqual($this)) {
            return $this->setValue(1);
        }

        if ($pi->truncate($precision)->isEqual($this)) {
            return $this->setValue(-1);
        }

        $modulo = $this->continuousModulo($twoPi);

        $answer = SeriesProvider::maclaurinSeries(
            $modulo,
            function ($n) {
                return SequenceProvider::nthPowerNegativeOne($n);
            },
            function ($n) {
                return SequenceProvider::nthEvenNumber($n);
            },
            function ($n) {
                return SequenceProvider::nthEvenNumber($n)->factorial();
            },
            0,
            $precision+1
        );

        if ($round) {
            return $this->setValue($answer->getValue())->roundToPrecision($precision)->convertFromModification($oldBase);
        } else {
            return $this->setValue($answer->getValue())->truncateToPrecision($precision)->convertFromModification($oldBase);
        }
    }

    public function tan($precision = null, $round = true)
    {
        $oldBase = $this->convertForModification();

        $precision = $precision ?? $this->getPrecision();

        $pi = Numbers::makePi();
        $piDivTwo = Numbers::makePi()->divide(2);
        $piDivFour = Numbers::makePi()->divide(4);
        $piDivEight = Numbers::makePi()->divide(8);
        $threePiDivTwo = Numbers::makePi()->multiply(3)->divide(2);
        $twoPi = Numbers::make2Pi();
        $two = Numbers::make(Numbers::IMMUTABLE, 2, 100);
        $one = Numbers::make(Numbers::IMMUTABLE, 1, 100);

        $exitModulo = $this->continuousModulo($pi);

        if ($exitModulo->truncate(99)->isEqual(0)) {
            return $this->setValue(0)->convertFromModification($oldBase);
        }

        $modulo = $this->continuousModulo($twoPi);

        if (
            $modulo->truncate(99)->isEqual($piDivTwo->truncate(99)) ||
            ($this->isNegative() && $modulo->subtract($pi)->abs()->truncate(99)->isEqual($piDivTwo->truncate(99)))
        ) {
            return $this->setValue(static::INFINITY);
        }

        if (
            $modulo->subtract($pi)->truncate(99)->isEqual($piDivTwo->truncate(99)) ||
            ($this->isNegative() && $modulo->truncate(99)->abs()->isEqual($piDivTwo->truncate(99)))
        ) {
            return $this->setValue(static::NEG_INFINITY);
        }

        if ($modulo->abs()->isGreaterThan($piDivTwo)) {
            if ($this->isNegative()) {
                if ($modulo->abs()->isGreaterThan($threePiDivTwo)) {
                    $modulo = $modulo->add($twoPi);
                } else {
                    $modulo = $modulo->add($pi);
                }
            } else {
                if ($modulo->isGreaterThan($threePiDivTwo)) {
                    $modulo = $modulo->subtract($twoPi);
                } else {
                    $modulo = $modulo->subtract($pi);
                }
            }
        }

        $reciprocal = false;

        if ($modulo->abs()->isGreaterThan($piDivFour)) {
            $modulo = $piDivTwo->subtract($modulo);
            $reciprocal = true;
        }

        if ($modulo->abs()->isGreaterThan($piDivEight)) {
            /** @var ImmutableNumber $halfModTan */
            $halfModTan = $modulo->divide(2)->tan($precision+1, false);
            $answer = $two->multiply($halfModTan)->divide($one->subtract($halfModTan->pow(2)));
        } else {
            $answer = SeriesProvider::maclaurinSeries(
                $modulo,
                function ($n) {
                    $nthOddNumber = SequenceProvider::nthOddNumber($n);

                    return SequenceProvider::nthEulerZigzag($nthOddNumber);
                },
                function ($n) {

                    return SequenceProvider::nthOddNumber($n);
                },
                function ($n) {
                    return SequenceProvider::nthOddNumber($n)->factorial();
                },
                0,
                $precision + 1
            );
        }

        if ($reciprocal) {
            $answer = $one->divide($answer);
        }

        if ($round) {
            return $this->setValue($answer->getValue())->roundToPrecision($precision)->convertFromModification($oldBase);
        } else {
            return $this->setValue($answer->getValue())->truncateToPrecision($precision)->convertFromModification($oldBase);
        }

    }

    public function cot($precision = null, $round = true)
    {

        $pi = Numbers::makePi();
        $twoPi = Numbers::make2Pi();
        $one = Numbers::makeOne();
        $piDivTwo = $pi->divide(2);

        $oldBase = $this->convertForModification();

        $precision = $precision ?? $this->getPrecision();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision+1);

        $modPi = $num->continuousModulo($pi)->truncate($precision);
        $mod2Pi = $num->continuousModulo($twoPi)->truncate($precision);

        if ($mod2Pi->isEqual(0)) {
            return $this->setValue(static::INFINITY);
        } elseif($modPi->isEqual(0)) {
            return $this->setValue(static::NEG_INFINITY);
        }

        $modPiDiv2 = $num->continuousModulo($piDivTwo)->truncate($precision);

        if ($modPiDiv2->isEqual(0)) {
            return $this->setValue(0)->convertFromModification($oldBase);
        }

        $tan = $num->tan($precision+2, $round);

        $answer = $one->divide($tan, $precision+2);

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);

    }

    public function sec($precision = null, $round = true)
    {

        $one = Numbers::makeOne();

        $oldBase = $this->convertForModification();

        $precision = $precision ?? $this->getPrecision();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision+1);

        $cos = $num->cos($precision+2, $round);

        if ($cos->isEqual(0)) {
            return $this->setValue(static::INFINITY);
        }

        $answer = $one->divide($cos, $precision+2);

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);

    }

    public function csc($precision = null, $round = true)
    {

        $one = Numbers::makeOne();

        $oldBase = $this->convertForModification();

        $precision = $precision ?? $this->getPrecision();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision);

        $sin = $num->sin($precision+2, $round);

        if ($sin->isEqual(0)) {
            return $this->setValue(static::INFINITY);
        }

        $answer = $one->divide($sin, $precision+2);

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);

    }

}