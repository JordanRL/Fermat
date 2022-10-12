<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Traits\Trigonometry\TrigonometryScaleTrait;
use Samsara\Fermat\Types\Traits\Trigonometry\TrigonometryNativeTrait;
use Samsara\Fermat\Types\Traits\Trigonometry\TrigonometrySelectionTrait;

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
        $answer = $this->sinSelector($scale);

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
    public function cos(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->cosSelector($scale);

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
    public function tan(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->tanSelector($scale);

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
    public function sec(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->secSelector($scale);

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
    public function csc(?int $scale = null, bool $round = true): DecimalInterface
    {
        if ($this->isEqual(0)) {
            $answer = static::INFINITY;
        } else {
            $answer = $this->cscSelector($scale);
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
    public function cot(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->cotSelector($scale);

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
    public function sinh(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->sinhSelector($scale);

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
    public function cosh(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->coshSelector($scale);

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
    public function tanh(?int $scale = null, bool $round = true): DecimalInterface
    {
        $finalScale = $scale ?? $this->getScale();

        $answer = $this->tanhSelector($scale);

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
    public function sech(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->sechSelector($scale);

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
    public function csch(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->cschSelector($scale);

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
    public function coth(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->cothSelector($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

}