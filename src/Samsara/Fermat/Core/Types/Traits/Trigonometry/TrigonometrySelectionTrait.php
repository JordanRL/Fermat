<?php

namespace Samsara\Fermat\Core\Types\Traits\Trigonometry;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcMode;

/**
 * @package Samsara\Fermat\Core
 */
trait TrigonometrySelectionTrait
{

    /**
     * @param int|null $scale
     * @return string
     */
    protected function sinSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->sinNative(),
            default => $this->sinScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function cosSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->cosNative(),
            default => $this->cosScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function tanSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->tanNative(),
            default => $this->tanScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function secSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->secNative(),
            default => $this->secScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function cscSelector(?int $scale = null): string
    {
        if ($this->isEqual(0)) {
            return self::INFINITY;
        }

        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->cscNative(),
            default => $this->cscScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function cotSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->cotNative(),
            default => $this->cotScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function sinhSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->sinhNative(),
            default => $this->sinhScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function coshSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->coshNative(),
            default => $this->coshScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function tanhSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->tanhNative(),
            default => $this->tanhScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function sechSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->sechNative(),
            default => $this->sechScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     */
    protected function cschSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->cschNative(),
            default => $this->cschScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function cothSelector(?int $scale = null): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->cothNative(),
            default => $this->cothScale($scale)
        };
    }

}