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

    /**
     * Calculate present value.
     *
     * Formula: PV = FV / (1 + r)^n
     *
     * @param int|float|string|Decimal $futureValue The future value
     * @param int|float|string|Decimal $rate The discount rate per period (as decimal, e.g., 0.05 for 5%)
     * @param int|float|string|Decimal $periods The number of periods
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function presentValue(
        int|float|string|Decimal $futureValue,
        int|float|string|Decimal $rate,
        int|float|string|Decimal $periods
    ): ImmutableDecimal
    {
        /** @var ImmutableDecimal $futureValue */
        $futureValue = Numbers::makeOrDont(Numbers::IMMUTABLE, $futureValue);
        /** @var ImmutableDecimal $rate */
        $rate = Numbers::makeOrDont(Numbers::IMMUTABLE, $rate);
        /** @var ImmutableDecimal $periods */
        $periods = Numbers::makeOrDont(Numbers::IMMUTABLE, $periods);

        // Validate that rate is not -100% or less (would cause division by zero or negative base)
        /** @var ImmutableDecimal $negativeOne */
        $negativeOne = Numbers::makeOrDont(Numbers::IMMUTABLE, -1);
        if ($rate->isLessThanOrEqualTo($negativeOne)) {
            throw new IntegrityConstraint(
                'Rate cannot be -100% or less',
                'Provide a rate greater than -1',
                'Rate must be > -1 to avoid division by zero or negative base in (1 + r)'
            );
        }

        // PV = FV / (1 + r)^n
        /** @var ImmutableDecimal $one */
        $one = Numbers::makeOrDont(Numbers::IMMUTABLE, 1);
        /** @var ImmutableDecimal $base */
        $base = $one->add($rate);
        /** @var ImmutableDecimal $divisor */
        $divisor = $base->pow($periods);

        return $futureValue->divide($divisor);
    }

    /**
     * Calculate future value.
     *
     * Formula: FV = PV * (1 + r)^n
     *
     * @param int|float|string|Decimal $presentValue The present value
     * @param int|float|string|Decimal $rate The interest rate per period (as decimal, e.g., 0.05 for 5%)
     * @param int|float|string|Decimal $periods The number of periods
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function futureValue(
        int|float|string|Decimal $presentValue,
        int|float|string|Decimal $rate,
        int|float|string|Decimal $periods
    ): ImmutableDecimal
    {
        /** @var ImmutableDecimal $presentValue */
        $presentValue = Numbers::makeOrDont(Numbers::IMMUTABLE, $presentValue);
        /** @var ImmutableDecimal $rate */
        $rate = Numbers::makeOrDont(Numbers::IMMUTABLE, $rate);
        /** @var ImmutableDecimal $periods */
        $periods = Numbers::makeOrDont(Numbers::IMMUTABLE, $periods);

        // FV = PV * (1 + r)^n
        /** @var ImmutableDecimal $one */
        $one = Numbers::makeOrDont(Numbers::IMMUTABLE, 1);
        /** @var ImmutableDecimal $base */
        $base = $one->add($rate);
        /** @var ImmutableDecimal $multiplier */
        $multiplier = $base->pow($periods);

        return $presentValue->multiply($multiplier);
    }

    /**
     * Calculate the present value of an annuity (ordinary annuity).
     *
     * Formula: PV = PMT * [(1 - (1 + r)^-n) / r] for r != 0
     * Formula: PV = PMT * n for r = 0
     *
     * @param int|float|string|Decimal $payment The payment amount per period
     * @param int|float|string|Decimal $rate The interest rate per period (as decimal, e.g., 0.05 for 5%)
     * @param int|float|string|Decimal $periods The number of periods
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function annuityPresentValue(
        int|float|string|Decimal $payment,
        int|float|string|Decimal $rate,
        int|float|string|Decimal $periods
    ): ImmutableDecimal
    {
        /** @var ImmutableDecimal $payment */
        $payment = Numbers::makeOrDont(Numbers::IMMUTABLE, $payment);
        /** @var ImmutableDecimal $rate */
        $rate = Numbers::makeOrDont(Numbers::IMMUTABLE, $rate);
        /** @var ImmutableDecimal $periods */
        $periods = Numbers::makeOrDont(Numbers::IMMUTABLE, $periods);

        // Edge case: when rate is 0, PV = PMT * n
        if ($rate->isEqual(0)) {
            return $payment->multiply($periods);
        }

        // Validate that rate is not -100% or less (would cause division by zero or negative base)
        /** @var ImmutableDecimal $negativeOne */
        $negativeOne = Numbers::makeOrDont(Numbers::IMMUTABLE, -1);
        if ($rate->isLessThanOrEqualTo($negativeOne)) {
            throw new IntegrityConstraint(
                'Rate cannot be -100% or less',
                'Provide a rate greater than -1',
                'Rate must be > -1 to avoid division by zero or negative base in (1 + r)'
            );
        }

        // PV = PMT * [(1 - (1 + r)^-n) / r]
        /** @var ImmutableDecimal $one */
        $one = Numbers::makeOrDont(Numbers::IMMUTABLE, 1);
        /** @var ImmutableDecimal $base */
        $base = $one->add($rate);
        /** @var ImmutableDecimal $negativeN */
        $negativeN = $periods->multiply($negativeOne);
        /** @var ImmutableDecimal $discountFactor */
        $discountFactor = $base->pow($negativeN);
        /** @var ImmutableDecimal $numerator */
        $numerator = $one->subtract($discountFactor);
        /** @var ImmutableDecimal $annuityFactor */
        $annuityFactor = $numerator->divide($rate);

        return $payment->multiply($annuityFactor);
    }

}
