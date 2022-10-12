<?php

namespace Samsara\Fermat\Provider\RoundingModeAdapters\Modes;

/**
 *
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