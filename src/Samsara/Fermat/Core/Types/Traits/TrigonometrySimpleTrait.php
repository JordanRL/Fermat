<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Traits\Trigonometry\TrigonometryScaleTrait;
use Samsara\Fermat\Core\Types\Traits\Trigonometry\TrigonometryNativeTrait;
use Samsara\Fermat\Core\Types\Traits\Trigonometry\TrigonometrySelectionTrait;

/**
 * @package Samsara\Fermat\Core
 */
trait TrigonometrySimpleTrait
{

    use TrigonometrySelectionTrait;
    use TrigonometryScaleTrait;
    use TrigonometryNativeTrait;

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function sin(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Sin);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function cos(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Cos);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function tan(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Tan);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function sec(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Sec);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function csc(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Csc);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function cot(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::Cot);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function sinh(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::SinH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function cosh(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::CosH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function tanh(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::TanH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function sech(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::SecH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function csch(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::CscH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal
     */
    public function coth(?int $scale = null, bool $round = true): Decimal
    {
        return $this->helperBasicTrigSelector($scale, $round, CalcOperation::CotH);
    }

}