<?php

namespace Samsara\Fermat\Types\Traits\Trigonometry;

use Samsara\Fermat\Enums\CalcMode;

trait TrigonometrySelectionTrait
{

    protected function sinSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->sinNative(),
            default => $this->sinScale($scale)
        };
    }

    protected function cosSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->cosNative(),
            default => $this->cosScale($scale)
        };
    }

    protected function tanSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->tanNative(),
            default => $this->tanScale($scale)
        };
    }

    protected function secSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->secNative(),
            default => $this->secScale($scale)
        };
    }

    protected function cscSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->cscNative(),
            default => $this->cscScale($scale)
        };
    }

    protected function cotSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->cotNative(),
            default => $this->cotScale($scale)
        };
    }

    protected function sinhSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->sinhNative(),
            default => $this->sinhScale($scale)
        };
    }

    protected function coshSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->coshNative(),
            default => $this->coshScale($scale)
        };
    }

    protected function tanhSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->tanhNative(),
            default => $this->tanhScale($scale)
        };
    }

    protected function sechSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->sechNative(),
            default => $this->sechScale($scale)
        };
    }

    protected function cschSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->cschNative(),
            default => $this->cschScale($scale)
        };
    }

    protected function cothSelection(?int $scale = null): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->cothNative(),
            default => $this->cothScale($scale)
        };
    }

}