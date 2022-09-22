<?php

namespace Samsara\Fermat\Types\Traits\Decimal;

use Samsara\Fermat\Enums\CalcMode;

trait LogSelectionTrait
{

    protected function expSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->expNative(),
            default => $this->expScale($scale)
        };
    }

    protected function lnSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->lnNative(),
            default => $this->lnScale($scale)
        };
    }

    protected function log10Selector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->log10Native(),
            default => $this->log10Scale($scale)
        };
    }

}