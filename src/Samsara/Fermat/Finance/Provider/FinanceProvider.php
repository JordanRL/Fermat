<?php

namespace Samsara\Fermat\Finance\Provider;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Finance
 */
class FinanceProvider
{

    /**
     * Calculate simple interest.
     *
     * Formula: I = P * r * t
     *
     * @param int|float|string|Decimal $principal The principal amount
     * @param int|float|string|Decimal $rate The interest rate (as decimal, e.g., 0.05 for 5%)
     * @param int|float|string|Decimal $time The time period
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function simpleInterest(
        int|float|string|Decimal $principal,
        int|float|string|Decimal $rate,
        int|float|string|Decimal $time
    ): ImmutableDecimal
    {
        /** @var ImmutableDecimal $principal */
        $principal = Numbers::makeOrDont(Numbers::IMMUTABLE, $principal);
        /** @var ImmutableDecimal $rate */
        $rate = Numbers::makeOrDont(Numbers::IMMUTABLE, $rate);
        /** @var ImmutableDecimal $time */
        $time = Numbers::makeOrDont(Numbers::IMMUTABLE, $time);

        return $principal->multiply($rate)->multiply($time);
    }

}
