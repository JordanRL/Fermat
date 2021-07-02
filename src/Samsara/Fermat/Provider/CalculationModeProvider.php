<?php

namespace Samsara\Fermat\Provider;

use JetBrains\PhpStorm\ExpectedValues;

class CalculationModeProvider
{

    const MODE_PRECISION = 1;
    const MODE_NATIVE = 2;

    private static int $mode;

    public static function setCalculationMode(
        #[ExpectedValues([
            self::MODE_PRECISION,
            self::MODE_NATIVE
        ])]
        int $mode
    ): void
    {
        self::$mode = $mode;
    }

    public static function getCalculationMode(): int
    {
        return self::$mode;
    }

}