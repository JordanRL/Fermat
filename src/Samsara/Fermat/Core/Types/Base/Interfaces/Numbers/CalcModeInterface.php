<?php

namespace Samsara\Fermat\Core\Types\Base\Interfaces\Numbers;

use Samsara\Fermat\Core\Enums\CalcMode;

/**
 *
 */
interface CalcModeInterface
{
    /**
     * @return ?CalcMode
     */
    public function getMode(): ?CalcMode;

    /**
     * @return CalcMode
     */
    public function getResolvedMode(): CalcMode;

    /**
     * Allows you to set a mode on a number to select the calculation methods.
     *
     * @param ?CalcMode $mode
     * @return $this
     */
    public function setMode(?CalcMode $mode): self;
}