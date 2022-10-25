<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Traits\Trigonometry\InverseTrigonometryNativeTrait;
use Samsara\Fermat\Core\Types\Traits\Trigonometry\InverseTrigonometryScaleTrait;
use Samsara\Fermat\Core\Types\Traits\Trigonometry\InverseTrigonometrySelectionTrait;

/**
 * @package Samsara\Fermat\Core
 */
trait InverseTrigonometrySimpleTrait
{

    use InverseTrigonometryNativeTrait;
    use InverseTrigonometryScaleTrait;
    use InverseTrigonometrySelectionTrait;

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function arcsin(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperArcsinArccosSimple(CalcOperation::ArcSin, $scale, $round);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function arccos(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperArcsinArccosSimple(CalcOperation::ArcCos, $scale, $round);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function arctan(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperArcBasicSimple(CalcOperation::ArcTan, $scale, $round);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function arccot(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperArcBasicSimple(CalcOperation::ArcCot, $scale, $round);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function arcsec(?int $scale = null, bool $round = true): Decimal
    {
        $oneInputVal = '0';
        $negOneInputVal = Numbers::makePi(($scale ?? $this->getScale())+1)->getValue(NumberBase::Ten);

        return $this->helperArcsecArccscSimple(CalcOperation::ArcSec, $scale, $oneInputVal, $negOneInputVal, $round);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function arccsc(?int $scale = null, bool $round = true): Decimal
    {
        $oneInputVal = Numbers::makePi(($scale ?? $this->getScale())+2)->divide(2, ($scale ?? $this->getScale())+2)->getValue();
        $negOneInputVal = Numbers::makePi(($scale ?? $this->getScale())+2)->divide(2, ($scale ?? $this->getScale())+2)->multiply(-1)->getValue();
        
        return $this->helperArcsecArccscSimple(CalcOperation::ArcCsc, $scale, $oneInputVal, $negOneInputVal, $round);
    }

}