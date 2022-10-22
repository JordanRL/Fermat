<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Traits\Trigonometry\TrigonometryScaleTrait;
use Samsara\Fermat\Core\Types\Traits\Trigonometry\TrigonometryNativeTrait;
use Samsara\Fermat\Core\Types\Traits\Trigonometry\TrigonometrySelectionTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 *
 */
trait TrigonometrySimpleTrait
{

    use TrigonometrySelectionTrait;
    use TrigonometryScaleTrait;
    use TrigonometryNativeTrait;

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function sin(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::Sin);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function cos(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::Cos);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function tan(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::Tan);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function sec(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::Sec);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function csc(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::Csc);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function cot(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::Cot);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function sinh(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::SinH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function cosh(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::CosH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function tanh(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::TanH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function sech(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::SecH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function csch(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::CscH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return DecimalInterface
     */
    public function coth(?int $scale = null, bool $round = true): DecimalInterface
    {
        return $this->helperBasicTrig($scale, $round, CalcOperation::CotH);
    }

    /**
     * @param int|null $scale
     * @param bool $round
     * @return Decimal|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    protected function helperBasicTrig(?int $scale, bool $round, CalcOperation $operation): ImmutableDecimal|MutableDecimal|Decimal
    {
        $finalScale = $scale ?? $this->getScale();

        $answer = match ($operation) {
            CalcOperation::Sin => $this->sinSelector($scale),
            CalcOperation::Cos => $this->cosSelector($scale),
            CalcOperation::Tan => $this->tanSelector($scale),
            CalcOperation::Sec => $this->secSelector($scale),
            CalcOperation::Csc => $this->cscSelector($scale),
            CalcOperation::Cot => $this->cotSelector($scale),
            CalcOperation::SinH => $this->sinhSelector($scale),
            CalcOperation::CosH => $this->coshSelector($scale),
            CalcOperation::TanH => $this->tanhSelector($scale),
            CalcOperation::SecH => $this->sechSelector($scale),
            CalcOperation::CscH => $this->cschSelector($scale),
            CalcOperation::CotH => $this->cothSelector($scale),
        };

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

}