<?php

namespace Samsara\Fermat\Core\Provider;

use Samsara\Fermat\Core\Enums\CalcMode;

/**
 * @package Samsara\Fermat\Core
 */
class CalculationModeProvider
{

    public const PHP_INT_MAX_HALF = PHP_INT_MAX/2;
    public const PHP_INT_MIN_HALF = PHP_INT_MIN/2;

    private static CalcMode $currentMode = CalcMode::Auto;

    /**
     * @return CalcMode
     */
    public static function getCurrentMode(): CalcMode
    {
        return self::$currentMode;
    }

    /**
     * @param CalcMode $currentMode
     */
    public static function setCurrentMode(CalcMode $currentMode): void
    {
        self::$currentMode = $currentMode;
    }

}