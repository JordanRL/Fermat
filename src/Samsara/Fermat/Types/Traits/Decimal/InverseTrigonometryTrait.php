<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

trait InverseTrigonometryTrait
{

    public function arcsin(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();
        $scale += 2;
        $pi = Numbers::makePi();
        $piDivTwo = $pi->divide(2, $scale+2);

        if ($this->isEqual(1) || $this->isEqual(-1)) {
            $answer = $piDivTwo;
            if ($this->isNegative()) {
                $answer = $answer->multiply(-1);
            }
        } elseif ($this->isEqual(0)) {
            $answer = Numbers::makeZero();
        } else {
            $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale + 2);
            $one = Numbers::makeOne($scale+2);

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
                    $answer->sin($scale)->subtract($z)->divide($answer->cos($scale), $scale)
                );
                $diff = $answer->subtract($prevAnswer)->abs();
                $prevAnswer = $answer;
                $count++;
            } while ($diff->numberOfLeadingZeros() <= $scale && $count < 15);


        }
        if ($round) {
            $answer = $answer->roundToScale($scale-2);
        } else {
            $answer = $answer->truncateToScale($scale-2);
        }

        return $this->setValue($answer);

    }

    public function arccos(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();
        $pi = Numbers::makePi($scale+2);
        $piDivTwo = $pi->divide(2, $scale+2);

        if ($this->isEqual(-1)) {
            $answer = Numbers::makePi($scale+1);
        } elseif ($this->isEqual(0)) {
            $answer = $piDivTwo;
        } elseif ($this->isEqual(1)) {
            $answer = Numbers::makeZero();
        } else {
            $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale + 2);
            $one = Numbers::makeOne($scale + 2);

            if ($z->abs()->isGreaterThan(1)) {
                throw new IntegrityConstraint(
                    'The input for arccos must have an absolute value of 1 or smaller',
                    'Only calculate arccos for values of 1 or smaller',
                    'The arccos function only has real values for inputs which have an absolute value of 1 or smaller'
                );
            }

            $answer = $piDivTwo->subtract($z->arcsin($scale+2));
        }

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer);

    }

    public function arctan(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();
        $scale += 5;

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $one = Numbers::makeOne();

        $answer = $z->divide($one->add($z->pow(2))->sqrt($scale), $scale)->arcsin($scale);

        if ($round) {
            $answer = $answer->roundToScale($scale-5);
        } else {
            $answer = $answer->truncateToScale($scale-5);
        }

        return $this->setValue($answer);

    }

    public function arccot(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();

        $piDivTwo = Numbers::makePi($scale + 2)->divide(2, $scale + 2);

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale + 2);

        $arctan = $z->arctan($scale+2, false);

        $answer = $piDivTwo->subtract($arctan);

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer);

    }

    public function arcsec(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();

        $one = Numbers::makeOne($scale + 2);
        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale+2);

        if ($z->abs()->isLessThan(1)) {
            throw new IntegrityConstraint(
                'The input for arcsec must have an absolute value greater than 1',
                'Only calculate arcsec for values greater than 1',
                'The arcsec function only has real values for inputs which have an absolute value greater than 1'
            );
        }

        $answer = $one->divide($z, $scale + 2)->arccos($scale + 2);

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer);

    }

    public function arccsc(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();

        $one = Numbers::makeOne($scale + 2);
        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale+2);

        if ($z->abs()->isLessThan(1)) {
            throw new IntegrityConstraint(
                'The input for arccsc must have an absolute value greater than 1',
                'Only calculate arccsc for values greater than 1',
                'The arccsc function only has real values for inputs which have an absolute value greater than 1'
            );
        }

        $answer = $one->divide($z, $scale + 2)->arcsin($scale + 2);

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
        }

        return $this->setValue($answer);

    }

    abstract public function roundToScale(int $scale, ?int $mode = null): DecimalInterface;

    abstract public function truncateToScale(int $scale): DecimalInterface;

    abstract public function getScale(): ?int;

}