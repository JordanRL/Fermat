<?php

namespace Samsara\Fermat\Core\Types\Traits\Trigonometry;

use Samsara\Fermat\Core\Enums\CalcMode;

/**
 *
 */
trait InverseTrigonometrySelectionTrait
{

    /**
     * @param int|null $scale
     * @return string
     */
    protected function arcsinSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arcsinNative(),
            default => $this->arcsinScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function arccosSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arccosNative(),
            default => $this->arccosScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function arctanSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arctanNative(),
            default => $this->arctanScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function arcsecSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arcsecNative(),
            default => $this->arcsecScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function arccscSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arccscNative(),
            default => $this->arccscScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function arccotSelector(?int $scale): string
    {
        $calcMode = $this->getMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arccotNative(),
            default => $this->arccotScale($scale)
        };
    }

}