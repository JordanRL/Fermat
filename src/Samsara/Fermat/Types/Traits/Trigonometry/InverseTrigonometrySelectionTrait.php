<?php

namespace Samsara\Fermat\Types\Traits\Trigonometry;

use Samsara\Fermat\Enums\CalcMode;

trait InverseTrigonometrySelectionTrait
{

    protected function arcsinSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arcsinNative(),
            default => $this->arcsinScale($scale)
        };
    }

    protected function arccosSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arccosNative(),
            default => $this->arccosScale($scale)
        };
    }

    protected function arctanSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arctanNative(),
            default => $this->arctanScale($scale)
        };
    }

    protected function arcsecSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arcsecNative(),
            default => $this->arcsecScale($scale)
        };
    }

    protected function arccscSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arccscNative(),
            default => $this->arccscScale($scale)
        };
    }

    protected function arccotSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arccotNative(),
            default => $this->arccotScale($scale)
        };
    }

}