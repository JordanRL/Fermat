<?php

namespace Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes;

/**
 * @package Samsara\Fermat\Core
 */
class HalfZeroAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        $remainder = $this->remainderCheck();

        return $digit > 5 || ($digit == 5 && $remainder) ? 1 : 0;
    }
}