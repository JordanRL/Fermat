<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Provider\CalculationModeProvider;

/**
 * @package Samsara\Fermat\Core
 */
trait CalculationModeTrait
{
    /** @var CalcMode|null */
    protected ?CalcMode $calcMode = null;

    /**
     * @return CalcMode
     */
    public function getResolvedMode(): CalcMode
    {

        return $this->calcMode ?? CalculationModeProvider::getCurrentMode();

    }

    /**
     * @return CalcMode|null
     */
    public function getMode(): ?CalcMode
    {

        return $this->calcMode;

    }

    /**
     * Allows you to set a mode on a number to select the calculation methods.
     *
     * @param CalcMode|null $mode
     * @return static
     */
    public function setMode(?CalcMode $mode): static
    {
        $this->calcMode = $mode;

        return $this;
    }
}