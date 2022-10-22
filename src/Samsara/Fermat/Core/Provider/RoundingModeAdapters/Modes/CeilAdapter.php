<?php

namespace Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes;

/**
 * @package Samsara\Fermat\Core
 */
class CeilAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        if ($this->isNegative) {
            return 0;
        } else {
            return $digit == 0 ? 0 : 1;
        }
    }
}