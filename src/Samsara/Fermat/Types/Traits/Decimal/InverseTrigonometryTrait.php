<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

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
        $precision += 2;
        $pi = Numbers::makePi();
        $piDivTwo = $pi->divide(2, $precision+2);

        if ($this->isEqual(1) || $this->isEqual(-1)) {
            $answer = $piDivTwo;
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

            $prevAnswer = $z;
            $answer = $z;
            $count = 0;

            do {
                $answer = $answer->subtract(
                    $answer->sin($precision)->subtract($z)->divide($answer->cos($precision), $precision)
                );
                $diff = $answer->subtract($prevAnswer)->abs();
                $prevAnswer = $answer;
                $count++;
            } while ($diff->numberOfLeadingZeros() <= $precision && $count < 15);


        }
        if ($round) {
            $answer = $answer->roundToPrecision($precision-2);
        } else {
            $answer = $answer->truncateToPrecision($precision-2);
        }

        return $this->setValue($answer);

    }

    public function arccos($precision = null, $round = true): DecimalInterface
    {

        $precision = $precision ?? $this->getPrecision();
        $pi = Numbers::makePi($precision+2);
        $piDivTwo = $pi->divide(2, $precision+2);

        if ($this->isEqual(-1)) {
            $answer = Numbers::makePi($precision+1);
        } elseif ($this->isEqual(0)) {
            $answer = $piDivTwo;
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

            $answer = $piDivTwo->subtract($z->arcsin($precision+2));
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
        $precision += 5;

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $precision);

        $one = Numbers::makeOne();

        $answer = $z->divide($one->add($z->pow(2))->sqrt($precision), $precision)->arcsin($precision);

        if ($round) {
            $answer = $answer->roundToPrecision($precision-5);
        } else {
            $answer = $answer->truncateToPrecision($precision-5);
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