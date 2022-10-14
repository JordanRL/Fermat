<?php

namespace Samsara\Fermat\Provider\RoundingModeAdapters\Modes;

/**
 *
 */
class HalfOddAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        $early = static::nonHalfEarlyReturn($digit);
        $remainder = $this->remainderCheck();

        if ($early == 0) {
            return ($nextDigit % 2 == 1 && !$remainder) ? 0 : 1;
        } else {
            return $early == 1 ? 1 : 0;
        }
    }
}