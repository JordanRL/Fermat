<?php

namespace Samsara\Fermat\Core\Types\Base\Traits;

use Samsara\Fermat\Core\Enums\CalcMode;

/**
 * @package Samsara\Fermat\Core
 */
trait InverseTrigonometrySelectionTrait
{

    /**
     * @param int|null $scale
     *
     * @return string
     */
    protected function arccosSelector(?int $scale): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arccosNative(),
            default => $this->arccosScale($scale)
        };
    }

    /**
     * @param int|null $scale
     *
     * @return string
     */
    protected function arccotSelector(?int $scale): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arccotNative(),
            default => $this->arccotScale($scale)
        };
    }

    /**
     * @param int|null $scale
     *
     * @return string
     */
    protected function arccscSelector(?int $scale): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arccscNative(),
            default => $this->arccscScale($scale)
        };
    }

    /**
     * @param int|null $scale
     *
     * @return string
     */
    protected function arcsecSelector(?int $scale): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arcsecNative(),
            default => $this->arcsecScale($scale)
        };
    }

    /**
     * @param int|null $scale
     *
     * @return string
     */
    protected function arcsinSelector(?int $scale): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arcsinNative(),
            default => $this->arcsinScale($scale)
        };
    }

    /**
     * @param int|null $scale
     *
     * @return string
     */
    protected function arctanSelector(?int $scale): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->arctanNative(),
            default => $this->arctanScale($scale)
        };
    }

}