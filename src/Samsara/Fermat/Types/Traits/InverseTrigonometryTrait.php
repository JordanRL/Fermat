<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

trait InverseTrigonometryTrait
{

    public function arcsin($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        if ($this->isEqual(1) || $this->isEqual(-1)) {
            $pi = Numbers::makePi();
            $answer = $pi->divide(2);
            if ($this->isNegative()) {
                $answer = $answer->multiply(-1);
            }
        } elseif ($this->isEqual(0)) {
            $answer = Numbers::makeZero();
        } else {
            $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision + 2);
            $one = Numbers::makeOne($precision+2);

            if ($z->abs()->isGreaterThan(1)) {
                throw new IntegrityConstraint(
                    'The input for arcsin must have an absolute value of 1 or smaller',
                    'Only calculate arcsin for values of 1 or smaller',
                    'The arcsin function only has real values for inputs which have an absolute value of 1 or smaller'
                );
            }

            $answer = $z->divide($one->subtract($z->pow(2))->sqrt($precision + 2), $precision + 2)->arctan($precision + 2, false);
        }
        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer);

    }

    public function arccos($precision = null, $round = true): DecimalInterface
    {

        $precision = $precision ?? $this->getPrecision();

        if ($this->isEqual(-1)) {
            $answer = Numbers::makePi($precision+1);
        } elseif ($this->isEqual(0)) {
            $answer = Numbers::makePi($precision+2);
            $answer = $answer->divide(2, $precision+2);
        } elseif ($this->isEqual(1)) {
            $answer = Numbers::makeZero();
        } else {
            $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision + 2);
            $one = Numbers::makeOne($precision + 2);

            if ($z->abs()->isGreaterThan(1)) {
                throw new IntegrityConstraint(
                    'The input for arccos must have an absolute value of 1 or smaller',
                    'Only calculate arccos for values of 1 or smaller',
                    'The arccos function only has real values for inputs which have an absolute value of 1 or smaller'
                );
            }

            $answer = $one->subtract($z->pow(2))
                ->sqrt($precision + 2)
                ->divide($z, $precision + 2)
                ->arctan($precision + 2, false);
        }

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer);

    }

    public function arctan($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        $one = Numbers::makeOne($precision + 2);

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision + 2);

        if ($z->isEqual(1)) {
            $answer = Numbers::makePi($precision + 2)->divide(4, $precision + 2);
        } elseif ($z->isEqual(-1)) {
            $answer = Numbers::makePi($precision + 2)->divide(4, $precision +2)->multiply(-1);
        } else {

            if ($z->abs()->isGreaterThan(1)) {
                $rangeAdjust = Numbers::makePi($precision + 2)->divide(2, $precision + 2);

                if ($z->isNegative()) {
                    $rangeAdjust = $rangeAdjust->multiply(-1);
                }

                $z = $one->divide($z, $precision + 2);
            }

            $y = $z->pow(2)->divide($z->pow(2)->add(1));
            $coef = $y->divide($z);

            $answer = SeriesProvider::maclaurinSeries(
                $y,
                function ($n) {
                    $nthOdd = SequenceProvider::nthOddNumber($n)->subtract(1);

                    return $nthOdd->doubleFactorial();
                },
                function ($n) {
                    return $n;
                },
                function ($n) {
                    $nthOdd = SequenceProvider::nthOddNumber($n);

                    return $nthOdd->doubleFactorial();
                },
                0,
                $precision + 1
            );

            $answer = $answer->add(1);
            $answer = $answer->multiply($coef);

            if (isset($rangeAdjust)) {
                $answer = $rangeAdjust->subtract($answer);
            }
        }

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer);

    }

    public function arccot($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        $piDivTwo = Numbers::makePi($precision + 2)->divide(2, $precision + 2);

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision + 2);

        $arctan = $z->arctan($precision+2, false);

        $answer = $piDivTwo->subtract($arctan);

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer);

    }

    public function arcsec($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        $one = Numbers::makeOne($precision + 2);
        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision+2);

        if ($z->abs()->isLessThan(1)) {
            throw new IntegrityConstraint(
                'The input for arcsec must have an absolute value greater than 1',
                'Only calculate arcsec for values greater than 1',
                'The arcsec function only has real values for inputs which have an absolute value greater than 1'
            );
        }

        $answer = $one->divide($z, $precision + 2)->arccos($precision + 2);

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer);

    }

    public function arccsc($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        $one = Numbers::makeOne($precision + 2);
        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision+2);

        if ($z->abs()->isLessThan(1)) {
            throw new IntegrityConstraint(
                'The input for arccsc must have an absolute value greater than 1',
                'Only calculate arccsc for values greater than 1',
                'The arccsc function only has real values for inputs which have an absolute value greater than 1'
            );
        }

        $answer = $one->divide($z, $precision + 2)->arcsin($precision + 2);

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer);

    }

    abstract public function roundToPrecision($precision);

    abstract public function truncateToPrecision($precision);

    abstract public function getPrecision();

}