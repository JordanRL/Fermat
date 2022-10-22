<?php

namespace Samsara\Fermat\Core\Types\Traits\Decimal;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcMode;

/**
 *
 */
trait LogSelectionTrait
{

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function expSelector(?int $scale): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->expNative(),
            default => $this->expScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function lnSelector(?int $scale): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->lnNative(),
            default => $this->lnScale($scale)
        };
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function log10Selector(?int $scale): string
    {
        $calcMode = $this->getResolvedMode();

        return match ($calcMode) {
            CalcMode::Native => (string)$this->log10Native(),
            default => $this->log10Scale($scale)
        };
    }

}