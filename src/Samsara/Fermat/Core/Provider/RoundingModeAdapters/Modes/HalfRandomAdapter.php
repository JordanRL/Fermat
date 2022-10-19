<?php

namespace Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes;

use Samsara\Fermat\Core\Enums\RandomMode;
use Samsara\Fermat\Core\Provider\RandomProvider;

/**
 *
 */
class HalfRandomAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        $early = static::nonHalfEarlyReturn($digit);
        $remainder = $this->remainderCheck();

        if ($early == 0 && !$remainder) {
            return RandomProvider::randomInt(0, 1, RandomMode::Speed)->asInt();
        } else {
            return (($early == 1 || $remainder) ? 1 : 0);
        }
    }
}