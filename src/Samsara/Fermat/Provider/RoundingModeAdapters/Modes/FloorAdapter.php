<?php

namespace Samsara\Fermat\Provider\RoundingModeAdapters\Modes;

/**
 *
 */
class FloorAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        if ($this->isNegative) {
            return $digit == 0 ? 0 : 1;
        } else {
            return 0;
        }
    }
}