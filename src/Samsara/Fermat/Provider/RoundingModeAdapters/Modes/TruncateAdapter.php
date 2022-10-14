<?php

namespace Samsara\Fermat\Provider\RoundingModeAdapters\Modes;

/**
 *
 */
class TruncateAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        return 0;
    }
}