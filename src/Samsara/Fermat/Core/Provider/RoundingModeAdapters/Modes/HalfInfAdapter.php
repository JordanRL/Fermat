<?php

namespace Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes;

/**
 * @package Samsara\Fermat\Core
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