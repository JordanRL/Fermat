<?php

namespace Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes;

/**
 * @package Samsara\Fermat\Core
 */
class HalfEvenAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        $early = static::nonHalfEarlyReturn($digit);
        $remainder = $this->remainderCheck();

        if ($early == 0) {
            return ($nextDigit % 2 == 0 && !$remainder) ? 0 : 1;
        } else {
            return $early == 1 ? 1 : 0;
        }
    }
}