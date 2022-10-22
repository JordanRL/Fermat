<?php

namespace Samsara\Fermat\Core\Provider\RoundingModeAdapters;

use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\BaseAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\CeilAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\FloorAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\HalfAlternatingAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\HalfDownAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\HalfEvenAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\HalfInfAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\HalfOddAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\HalfRandomAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\HalfUpAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\HalfZeroAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\StochasticAdapter;
use Samsara\Fermat\Core\Provider\RoundingModeAdapters\Modes\TruncateAdapter;

/**
 * @package Samsara\Fermat\Core
 */
class ModeAdapterFactory
{
    /** @var BaseAdapter[] */
    private static array $modeAdapters = [];

    /**
     * @param RoundingMode $mode
     * @param bool $isNegative
     * @param string|null $remainder
     * @return BaseAdapter
     */
    public static function getAdapter(RoundingMode $mode, bool $isNegative, ?string $remainder): BaseAdapter
    {
        if (isset(self::$modeAdapters[$mode->name])) {
            return self::$modeAdapters[$mode->name]->setNegative($isNegative)->setRemainder($remainder);
        }

        $adapter = match ($mode) {
            RoundingMode::HalfEven => new HalfEvenAdapter($isNegative, $remainder),
            RoundingMode::HalfOdd => new HalfOddAdapter($isNegative, $remainder),
            RoundingMode::HalfUp => new HalfUpAdapter($isNegative, $remainder),
            RoundingMode::HalfDown => new HalfDownAdapter($isNegative, $remainder),
            RoundingMode::HalfZero => new HalfZeroAdapter($isNegative, $remainder),
            RoundingMode::HalfInf => new HalfInfAdapter($isNegative, $remainder),
            RoundingMode::HalfRandom => new HalfRandomAdapter($isNegative, $remainder),
            RoundingMode::HalfAlternating => new HalfAlternatingAdapter($isNegative, $remainder),
            RoundingMode::Ceil => new CeilAdapter($isNegative, $remainder),
            RoundingMode::Floor => new FloorAdapter($isNegative, $remainder),
            RoundingMode::Truncate => new TruncateAdapter($isNegative, $remainder),
            RoundingMode::Stochastic => new StochasticAdapter($isNegative, $remainder)
        };

        self::$modeAdapters[$mode->name] = $adapter;

        return $adapter;
    }
}