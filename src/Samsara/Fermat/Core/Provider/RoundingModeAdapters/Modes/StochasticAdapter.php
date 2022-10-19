<?php

namespace Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes;

use Samsara\Fermat\Core\Enums\RandomMode;
use Samsara\Fermat\Core\Provider\RandomProvider;

/**
 *
 */
class StochasticAdapter extends BaseAdapter
{

    /**
     * @inheritDoc
     */
    public function determineCarry(int $digit, int $nextDigit): int
    {
        $remainder = $this->remainder;

        if (is_null($remainder)) {
            $target = $digit;
            $rangeMin = 0;
            $rangeMax = 9;
        } else {
            $remainder = substr($remainder, 0, 3);
            $target = (int)($digit.$remainder);
            $rangeMin = 0;
            $rangeMax = (int)str_repeat('9', strlen($remainder) + 1);
        }

        $random = RandomProvider::randomInt($rangeMin, $rangeMax, RandomMode::Speed)->asInt();

        if ($random < $target) {
            return 1;
        } else {
            return 0;
        }
    }
}