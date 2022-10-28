<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Traits\InverseTrigonometryNativeTrait;
use Samsara\Fermat\Core\Types\Base\Traits\InverseTrigonometryScaleTrait;
use Samsara\Fermat\Core\Types\Base\Traits\InverseTrigonometrySelectionTrait;
use Samsara\Fermat\Core\Types\Decimal;

/**
 * @package Samsara\Fermat\Core
 */
trait SimpleInverseTrigonometryTrait
{

    use InverseTrigonometryNativeTrait;
    use InverseTrigonometryScaleTrait;
    use InverseTrigonometrySelectionTrait;

    /**
     * Returns the inverse sine of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool $round If true, use the current rounding mode to round the result. If false, truncate the result.
     * @return Decimal
     */
    public function arcsin(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperArcsinArccosSimple(CalcOperation::ArcSin, $scale, $round);
    }

    /**
     * Returns the inverse cosine of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool $round If true, use the current rounding mode to round the result. If false, truncate the result.
     * @return Decimal
     */
    public function arccos(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperArcsinArccosSimple(CalcOperation::ArcCos, $scale, $round);
    }

    /**
     * Returns the inverse tangent of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool $round If true, use the current rounding mode to round the result. If false, truncate the result.
     * @return Decimal
     */
    public function arctan(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperArcBasicSimple(CalcOperation::ArcTan, $scale, $round);
    }

    /**
     * Returns the inverse cotangent of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool $round If true, use the current rounding mode to round the result. If false, truncate the result.
     * @return Decimal
     */
    public function arccot(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperArcBasicSimple(CalcOperation::ArcCot, $scale, $round);
    }

    /**
     * Returns the inverse secant of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool $round If true, use the current rounding mode to round the result. If false, truncate the result.
     * @return Decimal
     */
    public function arcsec(?int $scale = null, bool $round = true): Decimal
    {
        $oneInputVal = '0';
        $negOneInputVal = Numbers::makePi(($scale ?? $this->getScale())+1)->getValue(NumberBase::Ten);

        return $this->helperArcsecArccscSimple(CalcOperation::ArcSec, $scale, $oneInputVal, $negOneInputVal, $round);
    }

    /**
     * Returns the inverse cosecant of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool $round If true, use the current rounding mode to round the result. If false, truncate the result.
     * @return Decimal
     */
    public function arccsc(?int $scale = null, bool $round = true): Decimal
    {
        $oneInputVal = Numbers::makePi(($scale ?? $this->getScale())+2)->divide(2, ($scale ?? $this->getScale())+2)->getValue();
        $negOneInputVal = Numbers::makePi(($scale ?? $this->getScale())+2)->divide(2, ($scale ?? $this->getScale())+2)->multiply(-1)->getValue();
        
        return $this->helperArcsecArccscSimple(CalcOperation::ArcCsc, $scale, $oneInputVal, $negOneInputVal, $round);
    }

}