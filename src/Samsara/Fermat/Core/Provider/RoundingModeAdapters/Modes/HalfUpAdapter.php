<?php

namespace Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes;

/**
 *
 */
class HalfUpAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        if ($this->isNegative) {
            return $digit > 5 || ($digit == 5 && $this->remainderCheck()) ? 1 : 0;
        } else {
            return $digit > 4 ? 1 : 0;
        }
    }
}