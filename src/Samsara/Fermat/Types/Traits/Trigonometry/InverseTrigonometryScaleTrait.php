<?php

namespace Samsara\Fermat\Types\Traits\Trigonometry;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\RoundingMode;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

trait InverseTrigonometryScaleTrait
{

    public function arcsin(int $scale = null, bool $round = true): DecimalInterface
    {

        $scale = $scale ?? $this->getScale();

        if ($this->isEqual(1) || $this->isEqual(-1)) {
            $pi = Numbers::makePi();
            $answer = $pi->divide(2, $scale+2);
            if ($this->isNegative()) {
                $answer = $answer->multiply(-1);
            }
        } elseif ($this->isEqual(0)) {
            $answer = Numbers::makeZero();
        } else {
            $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
            $addScale = $abs->asInt() > $abs->getScale() ? $abs->asInt() : $abs->getScale();
            $intScale = $scale + $addScale;
            $x = new ImmutableDecimal($this->getValue(), $intScale);
            $x2 = $x->pow(2);
            $one = new ImmutableDecimal(1, $intScale);

            if ($abs->isGreaterThan(1)) {
                throw new IntegrityConstraint(
                    'The input for arcsin must have an absolute value of 1 or smaller',
                    'Only calculate arcsin for values of 1 or smaller',
                    'The arcsin function only has real values for inputs which have an absolute value of 1 or smaller'
                );
            }

            $aPart = new class($x2, $intScale) implements ContinuedFractionTermInterface{
                private ImmutableDecimal $x2;

                public function __construct(ImmutableDecimal $x2, int $intScale)
                {
                    $this->x2 = $x2;
                    $this->negTwo = new ImmutableDecimal(-2, $intScale);
                }

                public function __invoke(int $n): ImmutableDecimal
                {
                    $subterm = floor(($n+1)/2);

                    return $this->negTwo->multiply(
                        2*$subterm-1
                    )->multiply($subterm)->multiply($this->x2);
                }
            };

            $bPart = new class() implements ContinuedFractionTermInterface{
                public function __invoke(int $n): ImmutableDecimal
                {
                    return SequenceProvider::nthOddNumber($n);
                }
            };

            $answer = SeriesProvider::generalizedContinuedFraction($aPart, $bPart, $intScale, $intScale, SeriesProvider::SUM_MODE_ADD);

            $answer = $x->multiply($one->subtract($x2)->sqrt($intScale))->divide($answer, $intScale);

        }
        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
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
        $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
        $mulScale = $abs->asInt() > $abs->getScale() ? $abs->asInt() : $abs->getScale();
        $intScale = $scale * $mulScale;
        $x = new ImmutableDecimal($this->getValue(), $intScale);

        $one = Numbers::makeOne($intScale);

        $answer = $x->divide($one->add($x->pow(2))->sqrt($intScale), $intScale)->arcsin($intScale);

        if ($round) {
            $answer = $answer->roundToScale($scale);
        } else {
            $answer = $answer->truncateToScale($scale);
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

    abstract public function roundToScale(int $scale, ?RoundingMode $mode = null): DecimalInterface;

    abstract public function truncateToScale(int $scale): DecimalInterface;

    abstract public function getScale(): ?int;

}