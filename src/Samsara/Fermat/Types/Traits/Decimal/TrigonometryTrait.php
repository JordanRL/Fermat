<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

trait TrigonometryTrait
{

    public function sin(int $scale = null, bool $round = true): DecimalInterface
    {
        if ($this->isEqual(0)) {
            return $this;
        }

        $scale = $scale ?? $this->getScale();

        $twoPi = Numbers::make2Pi();
        $pi = Numbers::makePi();

        if ($pi->truncate($scale)->isEqual($this) || $twoPi->truncate($scale)->isEqual($this)) {
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
            $scale+1
        );

        if ($round) {
            return $this->setValue($answer->getAsBaseTenRealNumber(), $this->getBase())->roundToScale($scale);
        } else {
            return $this->setValue($answer->getAsBaseTenRealNumber(), $this->getBase())->truncateToScale($scale);
        }
    }

    public function cos(int $scale = null, bool $round = true): DecimalInterface
    {
        if ($this->isEqual(0)) {
            return $this->setValue('1');
        }

        $scale = $scale ?? $this->getScale();

        $twoPi = Numbers::make2Pi();
        $pi = Numbers::makePi();

        if ($twoPi->truncate($scale)->isEqual($this)) {
            return $this->setValue('1');
        }

        if ($pi->truncate($scale)->isEqual($this)) {
            return $this->setValue('-1');
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
            $scale+1
        );

        if ($round) {
            return $this->setValue($answer->getAsBaseTenRealNumber(), $this->getBase())->roundToScale($scale);
        } else {
            return $this->setValue($answer->getAsBaseTenRealNumber(), $this->getBase())->truncateToScale($scale);
        }
    }

    public function tan(int $scale = null, bool $round = true): DecimalInterface
    {
        $scale = $scale ?? $this->getScale();

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
            return $this->setValue(0);
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
            /** @var ImmutableDecimal $halfModTan */
            $halfModTan = $modulo->divide(2)->tan($scale+1, false);
            $answer = $two->multiply($halfModTan)->divide($one->subtract($halfModTan->pow(2)));
        } else {
            $answer = SeriesProvider::maclaurinSeries(
                $modulo,
                function ($n) {
                    $nthOddNumber = SequenceProvider::nthOddNumber($n);

                    return SequenceProvider::nthEulerZigzag($nthOddNumber->asInt());
                },
                function ($n) {

                    return SequenceProvider::nthOddNumber($n);
                },
                function ($n) {
                    return SequenceProvider::nthOddNumber($n)->factorial();
                },
                0,
                $scale + 1
            );
        }

        if ($reciprocal) {
            $answer = $one->divide($answer);
        }

        if ($round) {
            return $this->setValue($answer->getAsBaseTenRealNumber(), $this->getBase())->roundToScale($scale);
        } else {
            return $this->setValue($answer->getAsBaseTenRealNumber(), $this->getBase())->truncateToScale($scale);
        }

    }

    public function cot(int $scale = null, bool $round = true): DecimalInterface
    {

        $pi = Numbers::makePi();
        $twoPi = Numbers::make2Pi();
        $one = Numbers::makeOne();
        $piDivTwo = $pi->divide(2);

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale+1);

        $modPi = $num->continuousModulo($pi)->truncate($scale);
        $mod2Pi = $num->continuousModulo($twoPi)->truncate($scale);

        if ($mod2Pi->isEqual(0)) {
            return $this->setValue(static::INFINITY);
        } elseif($modPi->isEqual(0)) {
            return $this->setValue(static::NEG_INFINITY);
        }

        $modPiDiv2 = $num->continuousModulo($piDivTwo)->truncate($scale);

        if ($modPiDiv2->isEqual(0)) {
            return $this->setValue(0, $this->getBase());
        }

        $tan = $num->tan($scale+2, $round);

        $answer = $one->divide($tan, $scale+2);

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer, $this->getBase());

    }

    public function sec(int $scale = null, bool $round = true): DecimalInterface
    {

        $one = Numbers::makeOne();

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale+1);

        $cos = $num->cos($scale+2, $round);

        if ($cos->isEqual(0)) {
            return $this->setValue(static::INFINITY);
        }

        $answer = $one->divide($cos, $scale+2);

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer, $this->getBase());

    }

    public function csc(int $scale = null, bool $round = true): DecimalInterface
    {

        $one = Numbers::makeOne();

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $sin = $num->sin($scale+2, $round);

        if ($sin->isEqual(0)) {
            return $this->setValue(static::INFINITY);
        }

        $answer = $one->divide($sin, $scale+2);

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer, $this->getBase());

    }

    public function sinh(int $scale = null, bool $round = true): DecimalInterface
    {

        $two = Numbers::make(Numbers::IMMUTABLE, 2);

        $scale = $scale ?? $this->getScale();

        $this->scale = $scale;

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale+2);

        $answer = $num->multiply(2)->exp($scale+2)->subtract(1)->divide($two->multiply($num->exp($scale+2)), $scale+2);

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer, $this->getBase());

    }

    public function cosh(int $scale = null, bool $round = true): DecimalInterface
    {

        $two = Numbers::make(Numbers::IMMUTABLE, 2);

        $scale = $scale ?? $this->getScale();

        $this->scale = $scale;

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $answer = $num->multiply(2)->exp()->add(1)->divide($two->multiply($num->exp()));

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer, $this->getBase());

    }

    public function tanh(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $answer = $num->sinh($scale+1, false)->divide($num->cosh($scale+1, false));

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer, $this->getBase());

    }

    public function coth(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale+2);

        $answer = $num->cosh($scale+1, false)->divide($num->sinh($scale+1, false), $scale+2);

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer, $this->getBase());

    }

    public function sech(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();

        $one = Numbers::makeOne();
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $answer = $one->divide($num->cosh($scale+2, false), $scale+2);

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer, $this->getBase());

    }

    public function csch(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();

        $one = Numbers::makeOne();
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $answer = $one->divide($num->sinh($scale+1, false));

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer, $this->getBase());

    }

}