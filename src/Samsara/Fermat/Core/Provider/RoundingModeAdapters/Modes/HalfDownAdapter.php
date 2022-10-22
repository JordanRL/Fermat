<?php

namespace Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes;

/**
 * @package Samsara\Fermat\Core
 */
class HalfDownAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        $negative = $this->isNegative;
        $remainder = $this->remainderCheck();

        if ($negative) {
            return $digit > 4 ? 1 : 0;
        } else {
            return $digit > 5 || ($digit == 5 && $remainder) ? 1 : 0;
        }
    }
}