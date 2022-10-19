<?php

namespace Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes;

/**
 *
 */
class HalfAlternatingAdapter extends BaseAdapter
{
    private int $alt = 1;

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        $early = static::nonHalfEarlyReturn($digit);
        $remainder = $this->remainderCheck();

        if ($early == 0 && !$remainder) {
            $val = $this->alt;
            $this->alt = (int)!$val;

            return $val;
        } else {
            return (($early == 1 || $remainder) ? 1 : 0);
        }
    }
}