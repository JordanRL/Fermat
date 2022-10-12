<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Traits\Trigonometry\InverseTrigonometryNativeTrait;
use Samsara\Fermat\Types\Traits\Trigonometry\InverseTrigonometryScaleTrait;
use Samsara\Fermat\Types\Traits\Trigonometry\InverseTrigonometrySelectionTrait;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 *
 */
trait InverseTrigonometrySimpleTrait
{

    use InverseTrigonometryNativeTrait;
    use InverseTrigonometryScaleTrait;
    use InverseTrigonometrySelectionTrait;

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     * @throws IntegrityConstraint
     */
    public function arcsin(?int $scale = null, bool $round = true): DecimalInterface
    {
        $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
        if ($abs->isGreaterThan(1)) {
            throw new IntegrityConstraint(
                'The input for arcsin must have an absolute value of 1 or smaller',
                'Only calculate arcsin for values of 1 or smaller',
                'The arcsin function only has real values for inputs which have an absolute value of 1 or smaller'
            );
        }

        $answer = $this->arcsinSelector($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     * @throws IntegrityConstraint
     */
    public function arccos(?int $scale = null, bool $round = true): DecimalInterface
    {
        $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
        if ($abs->isGreaterThan(1)) {
            throw new IntegrityConstraint(
                'The input for arccos must have an absolute value of 1 or smaller',
                'Only calculate arccos for values of 1 or smaller',
                'The arccos function only has real values for inputs which have an absolute value of 1 or smaller'
            );
        }

        $answer = $this->arccosSelector($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function arctan(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->arctanSelector($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     * @throws IntegrityConstraint
     */
    public function arcsec(?int $scale = null, bool $round = true): DecimalInterface
    {
        $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
        if ($abs->isLessThan(1)) {
            throw new IntegrityConstraint(
                'The input for arcsec must have an absolute value greater than or equal to 1',
                'Only calculate arcsec for values greater than 1',
                'The arcsec function only has real values for inputs which have an absolute value greater than or equal to 1'
            );
        }

        if ($this->isEqual(1)) {
            $answer = '0';
        } elseif ($this->isEqual(-1)) {
            $answer = Numbers::makePi(($scale ?? $this->getScale())+1)->getValue(NumberBase::Ten);
        } else {
            $answer = $this->arcsecSelector($scale);
        }

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     * @throws IntegrityConstraint
     */
    public function arccsc(?int $scale = null, bool $round = true): DecimalInterface
    {
        $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
        if ($abs->isLessThan(1)) {
            throw new IntegrityConstraint(
                'The input for arccsc must have an absolute value greater than or equal to 1',
                'Only calculate arccsc for values greater than 1',
                'The arccsc function only has real values for inputs which have an absolute value greater than or equal to 1'
            );
        }

        if ($this->isEqual(1)) {
            $answer = Numbers::makePi(($scale ?? $this->getScale())+2)->divide(2, ($scale ?? $this->getScale())+2)->getValue();
        } elseif ($this->isEqual(-1)) {
            $answer = Numbers::makePi(($scale ?? $this->getScale())+2)->divide(2, ($scale ?? $this->getScale())+2)->multiply(-1)->getValue();
        } else {
            $answer = $this->arccscSelector($scale);
        }

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function arccot(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->arccotSelector($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

}