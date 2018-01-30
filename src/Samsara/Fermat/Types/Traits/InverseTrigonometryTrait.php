<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Provider\StatsProvider;
use Samsara\Fermat\Values\ImmutableNumber;

trait InverseTrigonometryTrait
{

    public function arcsin($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        if ($precision > 99) {
            $precision = 99;
        }

        $one = Numbers::makeOne();

        $oldBase = $this->convertForModification();

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision+1);

        if ($z->abs()->isGreaterThan(1)) {
            throw new IntegrityConstraint(
                'The input for arcsin must have an absolute value of 1 or smaller',
                'Only calculate arcsin for values of 1 or smaller',
                'The arcsin function only has real values for inputs which have an absolute value of 1 or smaller'
            );
        }

        $answer = SeriesProvider::maclaurinSeries(
            $z,
            function($n) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

                return StatsProvider::binomialCoefficient($n->multiply(2), $n);
            },
            function($n) {
                return SequenceProvider::nthOddNumber($n);
            },
            function($n) {
                $four = Numbers::make(Numbers::IMMUTABLE, 4);

                return $four->pow($n)->multiply(SequenceProvider::nthOddNumber($n));
            },
            0,
            $precision+1
        );

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);

    }

    public function arccos($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        if ($precision > 99) {
            $precision = 99;
        }

        $piDivTwo = Numbers::makePi()->divide(2);

        $one = Numbers::makeOne();

        $oldBase = $this->convertForModification();

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision+1);

        if ($z->abs()->isGreaterThan(1)) {
            throw new IntegrityConstraint(
                'The input for arccos must have an absolute value of 1 or smaller',
                'Only calculate arccos for values of 1 or smaller',
                'The arccos function only has real values for inputs which have an absolute value of 1 or smaller'
            );
        }

        $answer = $piDivTwo->subtract($z->arcsin($precision, false));

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);

    }

    public function arctan($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        if ($precision > 99) {
            $precision = 99;
        }

        $one = Numbers::makeOne();

        $oldBase = $this->convertForModification();

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision+1);

        if ($z->abs()->isGreaterThan(1)) {
            $rangeAdjust = Numbers::makePi()->divide(2);

            if ($z->isNegative()) {
                $rangeAdjust = $rangeAdjust->multiply(-1);
            }

            $z = $one->divide($z, $precision+1);
        }

        $answer = SeriesProvider::maclaurinSeries(
            $z,
            function($n) {
                return SequenceProvider::nthPowerNegativeOne($n);
            },
            function($n) {
                return SequenceProvider::nthOddNumber($n);
            },
            function($n) {
                return SequenceProvider::nthOddNumber($n);
            },
            0,
            $precision+1
        );

        if (isset($rangeAdjust)) {
            $answer = $rangeAdjust->subtract($answer);
        }

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);

    }

    public function arccot($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        if ($precision > 99) {
            $precision = 99;
        }

        $one = Numbers::makeOne();
        $piDivTwo = Numbers::makePi()->divide(2);

        $oldBase = $this->convertForModification();

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision+1);

        if ($z->abs()->isGreaterThan(1)) {
            $rangeAdjust = Numbers::makePi()->divide(2);

            if ($z->isNegative()) {
                $rangeAdjust = $rangeAdjust->multiply(3);
            }

            $z = $one->divide($z, $precision+1);
        }

        $answer = SeriesProvider::maclaurinSeries(
            $z,
            function($n) {
                return SequenceProvider::nthPowerNegativeOne($n);
            },
            function($n) {
                return SequenceProvider::nthOddNumber($n);
            },
            function($n) {
                return SequenceProvider::nthOddNumber($n);
            },
            0,
            $precision+1
        );

        $answer = $piDivTwo->subtract($answer);

        if (isset($rangeAdjust)) {
            $answer = $rangeAdjust->subtract($answer);
        }

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);

    }

    public function arcsec($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        if ($precision > 99) {
            $precision = 99;
        }

        $piDivTwo = Numbers::makePi()->divide(2);

        $oldBase = $this->convertForModification();

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision+1);

        if ($z->abs()->isLessThan(1)) {
            throw new IntegrityConstraint(
                'The input for arcsec must have an absolute value of 1 or larger',
                'Only calculate arcsec for values of 1 or larger',
                'The arcsec function only has real values for inputs which have an absolute value of 1 or larger'
            );
        }

        $answer = SeriesProvider::maclaurinSeries(
            $z,
            function($n) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

                return StatsProvider::binomialCoefficient($n->multiply(2), $n);
            },
            function($n) {
                return SequenceProvider::nthOddNumber($n)->multiply(-1);
            },
            function($n) {
                $four = Numbers::make(Numbers::IMMUTABLE, 4);

                return $four->pow($n)->multiply(SequenceProvider::nthOddNumber($n));
            },
            0,
            $precision+1
        );

        $answer = $piDivTwo->subtract($answer);

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);

    }

    public function arccsc($precision = null, $round = true)
    {

        $precision = $precision ?? $this->getPrecision();

        if ($precision > 99) {
            $precision = 99;
        }

        $oldBase = $this->convertForModification();

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision+1);

        if ($z->abs()->isLessThan(1)) {
            throw new IntegrityConstraint(
                'The input for arccsc must have an absolute value of 1 or larger',
                'Only calculate arccsc for values of 1 or larger',
                'The arccsc function only has real values for inputs which have an absolute value of 1 or larger'
            );
        }

        /** @var ImmutableNumber $answer */
        $answer = SeriesProvider::maclaurinSeries(
            $z,
            function($n) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

                return StatsProvider::binomialCoefficient($n->multiply(2), $n);
            },
            function($n) {
                return SequenceProvider::nthOddNumber($n)->multiply(-1);
            },
            function($n) {
                $four = Numbers::make(Numbers::IMMUTABLE, 4);

                return $four->pow($n)->multiply(SequenceProvider::nthOddNumber($n));
            },
            0,
            $precision+1
        );

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);

    }

    abstract public function roundToPrecision($precision);

    abstract public function truncateToPrecision($precision);

}