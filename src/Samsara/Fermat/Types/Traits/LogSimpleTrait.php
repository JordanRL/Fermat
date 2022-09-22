<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

trait LogSimpleTrait
{

    public function exp(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->expSelector($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function ln(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->lnSelector($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    public function log10(?int $scale = null, bool $round = true): DecimalInterface
    {
        $answer = $this->log10Selector($scale);

        $finalScale = $scale ?? $this->getScale();

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

}