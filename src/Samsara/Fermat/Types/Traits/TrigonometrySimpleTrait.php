<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Traits\Trigonometry\TrigonometryScaleTrait;
use Samsara\Fermat\Types\Traits\Trigonometry\TrigonometryNativeTrait;
use Samsara\Fermat\Types\Traits\Trigonometry\TrigonometrySelectionTrait;

trait TrigonometrySimpleTrait
{

    use TrigonometrySelectionTrait;
    use TrigonometryScaleTrait;
    use TrigonometryNativeTrait;

    public function sin(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->sinSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function cos(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->cosSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function tan(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->tanSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function sec(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->secSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function csc(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->cscSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function cot(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->cotSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function sinh(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->sinhSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function cosh(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->coshSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function tanh(?int $scale = null, bool $round = true): DecimalInterface
    {
        $finalScale = $scale ?? $this->getScale();

        $answer = $this->tanhSelection($scale);

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function sech(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->sechSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function csch(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->cschSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function coth(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->cothSelection($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

}