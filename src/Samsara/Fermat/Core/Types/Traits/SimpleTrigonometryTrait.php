<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Types\Base\Traits\TrigonometryNativeTrait;
use Samsara\Fermat\Core\Types\Base\Traits\TrigonometryScaleTrait;
use Samsara\Fermat\Core\Types\Base\Traits\TrigonometrySelectionTrait;
use Samsara\Fermat\Core\Types\Decimal;

/**
 * @package Samsara\Fermat\Core
 */
trait SimpleTrigonometryTrait
{

    use TrigonometrySelectionTrait;
    use TrigonometryScaleTrait;
    use TrigonometryNativeTrait;

    /**
     * Returns the cosine of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function cos(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Cos);
    }

    /**
     * Returns the hyperbolic cosine of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function cosh(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::CosH);
    }

    /**
     * Returns the cotangent of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function cot(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Cot);
    }

    /**
     * Returns the hyperbolic cotangent of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function coth(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::CotH);
    }

    /**
     * Returns the cosecant of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function csc(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Csc);
    }

    /**
     * Returns the hyperbolic cosecant of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function csch(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::CscH);
    }

    /**
     * Returns the secant of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function sec(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Sec);
    }

    /**
     * Returns the hyperbolic secant of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function sech(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::SecH);
    }

    /**
     * Returns the sine of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function sin(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Sin);
    }

    /**
     * Returns the hyperbolic sine of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function sinh(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::SinH);
    }

    /**
     * Returns the tangent of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function tan(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Tan);
    }

    /**
     * Returns the hyperbolic tangent of this number.
     *
     * @param int|null $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     * @param bool     $round If true, use the current rounding mode to round the result. If false, truncate the result.
     *
     * @return Decimal
     */
    public function tanh(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::TanH);
    }

}