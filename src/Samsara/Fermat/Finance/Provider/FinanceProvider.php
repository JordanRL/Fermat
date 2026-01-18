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

    /**
     * Calculate compound interest.
     *
     * Formula: A = P(1 + r/n)^(nt) for regular compounding
     * Formula: A = Pe^(rt) for continuous compounding (when n = 0)
     *
     * @param int|float|string|Decimal $principal The principal amount
     * @param int|float|string|Decimal $rate The annual interest rate (as decimal, e.g., 0.05 for 5%)
     * @param int|float|string|Decimal $time The time period in years
     * @param int|float|string|Decimal $frequency The number of times interest is compounded per year (use 0 for continuous)
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function compoundInterest(
        int|float|string|Decimal $principal,
        int|float|string|Decimal $rate,
        int|float|string|Decimal $time,
        int|float|string|Decimal $frequency = 1
    ): ImmutableDecimal
    {
        /** @var ImmutableDecimal $principal */
        $principal = Numbers::makeOrDont(Numbers::IMMUTABLE, $principal);
        /** @var ImmutableDecimal $rate */
        $rate = Numbers::makeOrDont(Numbers::IMMUTABLE, $rate);
        /** @var ImmutableDecimal $time */
        $time = Numbers::makeOrDont(Numbers::IMMUTABLE, $time);
        /** @var ImmutableDecimal $frequency */
        $frequency = Numbers::makeOrDont(Numbers::IMMUTABLE, $frequency);

        // Continuous compounding: A = Pe^(rt)
        if ($frequency->isEqual(0)) {
            /** @var ImmutableDecimal $exponent */
            $exponent = $rate->multiply($time);
            return $principal->multiply($exponent->exp());
        }

        // Regular compounding: A = P(1 + r/n)^(nt)
        /** @var ImmutableDecimal $one */
        $one = Numbers::makeOrDont(Numbers::IMMUTABLE, 1);
        /** @var ImmutableDecimal $ratePerPeriod */
        $ratePerPeriod = $rate->divide($frequency);
        /** @var ImmutableDecimal $base */
        $base = $one->add($ratePerPeriod);
        /** @var ImmutableDecimal $exponent */
        $exponent = $frequency->multiply($time);

        return $principal->multiply($base->pow($exponent));
    }

}
