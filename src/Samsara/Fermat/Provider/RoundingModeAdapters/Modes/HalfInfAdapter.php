<?php

namespace Samsara\Fermat\Provider\RoundingModeAdapters\Modes;

/**
 *
 */
class HalfInfAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        return $digit > 4 ? 1 : 0;
    }
}