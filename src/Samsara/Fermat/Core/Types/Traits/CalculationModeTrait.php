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
     * Returns the mode that this object would use at the moment, accounting for all values and defaults.
     *
     * @return CalcMode
     */
    public function getResolvedMode(): CalcMode
    {

        return $this->calcMode ?? CalculationModeProvider::getCurrentMode();

    }

    /**
     * Returns the enum setting for this object's calculation mode. If this is null, then the default mode in the
     * CalculationModeProvider at the time a calculation is performed will be used.
     *
     * @return CalcMode|null
     */
    public function getMode(): ?CalcMode
    {

        return $this->calcMode;

    }

    /**
     * Allows you to set a mode on a number to select the calculation methods. If this is null, then the default mode in the
     * CalculationModeProvider at the time a calculation is performed will be used.
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