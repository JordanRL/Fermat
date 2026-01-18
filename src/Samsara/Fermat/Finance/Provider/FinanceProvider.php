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

    /**
     * Calculate the future value of an annuity (ordinary annuity).
     *
     * Formula: FV = PMT * [((1 + r)^n - 1) / r] for r != 0
     * Formula: FV = PMT * n for r = 0
     *
     * @param int|float|string|Decimal $payment The payment amount per period
     * @param int|float|string|Decimal $rate The interest rate per period (as decimal, e.g., 0.05 for 5%)
     * @param int|float|string|Decimal $periods The number of periods
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function annuityFutureValue(
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

        // Edge case: when rate is 0, FV = PMT * n
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

        // FV = PMT * [((1 + r)^n - 1) / r]
        /** @var ImmutableDecimal $one */
        $one = Numbers::makeOrDont(Numbers::IMMUTABLE, 1);
        /** @var ImmutableDecimal $base */
        $base = $one->add($rate);
        /** @var ImmutableDecimal $growthFactor */
        $growthFactor = $base->pow($periods);
        /** @var ImmutableDecimal $numerator */
        $numerator = $growthFactor->subtract($one);
        /** @var ImmutableDecimal $annuityFactor */
        $annuityFactor = $numerator->divide($rate);

        return $payment->multiply($annuityFactor);
    }

    /**
     * Calculate loan payment amount (ordinary annuity payment).
     *
     * Formula: PMT = P * [r(1 + r)^n] / [(1 + r)^n - 1] for r != 0
     * Formula: PMT = P / n for r = 0
     *
     * @param int|float|string|Decimal $principal The principal (loan amount)
     * @param int|float|string|Decimal $rate The interest rate per period (as decimal, e.g., 0.05 for 5%)
     * @param int|float|string|Decimal $periods The number of periods
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function loanPayment(
        int|float|string|Decimal $principal,
        int|float|string|Decimal $rate,
        int|float|string|Decimal $periods
    ): ImmutableDecimal
    {
        /** @var ImmutableDecimal $principal */
        $principal = Numbers::makeOrDont(Numbers::IMMUTABLE, $principal);
        /** @var ImmutableDecimal $rate */
        $rate = Numbers::makeOrDont(Numbers::IMMUTABLE, $rate);
        /** @var ImmutableDecimal $periods */
        $periods = Numbers::makeOrDont(Numbers::IMMUTABLE, $periods);

        // Edge case: when rate is 0, PMT = P / n
        if ($rate->isEqual(0)) {
            return $principal->divide($periods);
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

        // PMT = P * [r(1 + r)^n] / [(1 + r)^n - 1]
        /** @var ImmutableDecimal $one */
        $one = Numbers::makeOrDont(Numbers::IMMUTABLE, 1);
        /** @var ImmutableDecimal $base */
        $base = $one->add($rate);
        /** @var ImmutableDecimal $growthFactor */
        $growthFactor = $base->pow($periods);
        /** @var ImmutableDecimal $numerator */
        $numerator = $rate->multiply($growthFactor);
        /** @var ImmutableDecimal $denominator */
        $denominator = $growthFactor->subtract($one);
        /** @var ImmutableDecimal $paymentFactor */
        $paymentFactor = $numerator->divide($denominator);

        return $principal->multiply($paymentFactor);
    }

    /**
     * Generate a loan amortization schedule.
     *
     * Returns an array of payment details for each period, including:
     * - period: The payment period number
     * - payment: The total payment amount
     * - principal: The principal portion of the payment
     * - interest: The interest portion of the payment
     * - balance: The remaining loan balance after the payment
     *
     * @param int|float|string|Decimal $principal The principal (loan amount)
     * @param int|float|string|Decimal $rate The interest rate per period (as decimal, e.g., 0.05 for 5%)
     * @param int|float|string|Decimal $periods The number of periods
     *
     * @return array Array of payment details for each period
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function amortizationSchedule(
        int|float|string|Decimal $principal,
        int|float|string|Decimal $rate,
        int|float|string|Decimal $periods
    ): array
    {
        /** @var ImmutableDecimal $principal */
        $principal = Numbers::makeOrDont(Numbers::IMMUTABLE, $principal);
        /** @var ImmutableDecimal $rate */
        $rate = Numbers::makeOrDont(Numbers::IMMUTABLE, $rate);
        /** @var ImmutableDecimal $periods */
        $periods = Numbers::makeOrDont(Numbers::IMMUTABLE, $periods);

        // Calculate the periodic payment
        /** @var ImmutableDecimal $payment */
        $payment = self::loanPayment($principal, $rate, $periods);

        /** @var ImmutableDecimal $balance */
        $balance = $principal;
        /** @var ImmutableDecimal $zero */
        $zero = Numbers::makeOrDont(Numbers::IMMUTABLE, 0);

        $schedule = [];
        $periodCount = (int)$periods->getValue();

        for ($i = 1; $i <= $periodCount; $i++) {
            // Calculate interest for this period
            /** @var ImmutableDecimal $interest */
            $interest = $balance->multiply($rate);

            // Calculate principal payment
            /** @var ImmutableDecimal $principalPayment */
            $principalPayment = $payment->subtract($interest);

            // Update balance
            /** @var ImmutableDecimal $newBalance */
            $newBalance = $balance->subtract($principalPayment);

            // Handle final period rounding - ensure balance is exactly zero
            if ($i === $periodCount) {
                $newBalance = $zero;
            }

            $schedule[] = [
                'period' => $i,
                'payment' => $payment,
                'principal' => $principalPayment,
                'interest' => $interest,
                'balance' => $newBalance,
            ];

            $balance = $newBalance;
        }

        return $schedule;
    }

    /**
     * Calculate net present value (NPV) of a series of cash flows.
     *
     * Formula: NPV = Σ(CF_t / (1 + r)^t) for t from 0 to n
     *
     * @param array $cashFlows Array of cash flows indexed by period (period 0 is initial investment, typically negative)
     * @param int|float|string|Decimal $rate The discount rate per period (as decimal, e.g., 0.05 for 5%)
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function netPresentValue(
        array $cashFlows,
        int|float|string|Decimal $rate
    ): ImmutableDecimal
    {
        /** @var ImmutableDecimal $rate */
        $rate = Numbers::makeOrDont(Numbers::IMMUTABLE, $rate);

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

        /** @var ImmutableDecimal $npv */
        $npv = Numbers::makeOrDont(Numbers::IMMUTABLE, 0);
        /** @var ImmutableDecimal $one */
        $one = Numbers::makeOrDont(Numbers::IMMUTABLE, 1);
        /** @var ImmutableDecimal $base */
        $base = $one->add($rate);

        // Calculate NPV by summing the present value of each cash flow
        foreach ($cashFlows as $period => $cashFlow) {
            /** @var ImmutableDecimal $cashFlow */
            $cashFlow = Numbers::makeOrDont(Numbers::IMMUTABLE, $cashFlow);
            /** @var ImmutableDecimal $periodNum */
            $periodNum = Numbers::makeOrDont(Numbers::IMMUTABLE, $period);

            // Calculate present value of this cash flow: CF / (1 + r)^t
            /** @var ImmutableDecimal $discountFactor */
            $discountFactor = $base->pow($periodNum);
            /** @var ImmutableDecimal $presentValue */
            $presentValue = $cashFlow->divide($discountFactor);

            // Add to running total
            $npv = $npv->add($presentValue);
        }

        return $npv;
    }

    /**
     * Calculate the internal rate of return (IRR) for a series of cash flows.
     *
     * IRR is the discount rate that makes the net present value (NPV) equal to zero.
     * Uses the Newton-Raphson method for iterative approximation.
     *
     * Formula: Find r such that NPV = Σ(CF_t / (1 + r)^t) = 0
     *
     * @param array $cashFlows Array of cash flows indexed by period (period 0 is initial investment, typically negative)
     * @param int|float|string|Decimal $guess Initial guess for IRR (default 0.1 for 10%)
     * @param int|float|string|Decimal $tolerance Convergence tolerance (default 0.000001)
     * @param int $maxIterations Maximum number of iterations (default 100)
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function internalRateOfReturn(
        array $cashFlows,
        int|float|string|Decimal $guess = 0.1,
        int|float|string|Decimal $tolerance = 0.000001,
        int $maxIterations = 100
    ): ImmutableDecimal
    {
        // Validate cash flows
        if (empty($cashFlows)) {
            throw new IntegrityConstraint(
                'Cash flows array cannot be empty',
                'Provide at least one cash flow',
                'IRR calculation requires at least one cash flow'
            );
        }

        /** @var ImmutableDecimal $rate */
        $rate = Numbers::makeOrDont(Numbers::IMMUTABLE, $guess);
        /** @var ImmutableDecimal $tolerance */
        $tolerance = Numbers::makeOrDont(Numbers::IMMUTABLE, $tolerance);
        /** @var ImmutableDecimal $one */
        $one = Numbers::makeOrDont(Numbers::IMMUTABLE, 1);
        /** @var ImmutableDecimal $negativeOne */
        $negativeOne = Numbers::makeOrDont(Numbers::IMMUTABLE, -1);

        // Newton-Raphson iteration
        for ($i = 0; $i < $maxIterations; $i++) {
            // Calculate NPV at current rate
            /** @var ImmutableDecimal $npv */
            $npv = Numbers::makeOrDont(Numbers::IMMUTABLE, 0);
            /** @var ImmutableDecimal $npvDerivative */
            $npvDerivative = Numbers::makeOrDont(Numbers::IMMUTABLE, 0);
            /** @var ImmutableDecimal $base */
            $base = $one->add($rate);

            // Calculate NPV and its derivative
            foreach ($cashFlows as $period => $cashFlow) {
                /** @var ImmutableDecimal $cashFlow */
                $cashFlow = Numbers::makeOrDont(Numbers::IMMUTABLE, $cashFlow);
                /** @var ImmutableDecimal $periodNum */
                $periodNum = Numbers::makeOrDont(Numbers::IMMUTABLE, $period);

                // NPV calculation: CF / (1 + r)^t
                /** @var ImmutableDecimal $discountFactor */
                $discountFactor = $base->pow($periodNum);
                /** @var ImmutableDecimal $presentValue */
                $presentValue = $cashFlow->divide($discountFactor);
                $npv = $npv->add($presentValue);

                // Derivative calculation: -t * CF / (1 + r)^(t+1)
                if ($periodNum->isGreaterThan(0)) {
                    /** @var ImmutableDecimal $derivativeTerm */
                    $derivativeTerm = $periodNum->multiply($negativeOne)->multiply($cashFlow);
                    /** @var ImmutableDecimal $derivativeDivisor */
                    $derivativeDivisor = $base->pow($periodNum->add($one));
                    /** @var ImmutableDecimal $derivativeValue */
                    $derivativeValue = $derivativeTerm->divide($derivativeDivisor);
                    $npvDerivative = $npvDerivative->add($derivativeValue);
                }
            }

            // Check for convergence
            if ($npv->abs()->isLessThan($tolerance)) {
                return $rate;
            }

            // Check for zero derivative (would cause division by zero)
            /** @var ImmutableDecimal $zero */
            $zero = Numbers::makeOrDont(Numbers::IMMUTABLE, 0);
            if ($npvDerivative->isEqual($zero)) {
                throw new IntegrityConstraint(
                    'IRR calculation failed: derivative is zero',
                    'Try a different initial guess',
                    'The Newton-Raphson method encountered a zero derivative'
                );
            }

            // Newton-Raphson update: r_new = r_old - NPV / NPV'
            /** @var ImmutableDecimal $delta */
            $delta = $npv->divide($npvDerivative);
            $rate = $rate->subtract($delta);

            // Validate that rate stays above -100%
            if ($rate->isLessThanOrEqualTo($negativeOne)) {
                throw new IntegrityConstraint(
                    'IRR calculation failed: rate converged to -100% or less',
                    'The cash flows may not have a valid IRR',
                    'IRR must be greater than -100%'
                );
            }
        }

        // Maximum iterations reached
        throw new IntegrityConstraint(
            'IRR calculation did not converge within maximum iterations',
            'Try increasing maxIterations or adjusting the initial guess',
            'The Newton-Raphson method did not converge within ' . $maxIterations . ' iterations'
        );
    }

    /**
     * Calculate the price of a bond.
     *
     * Formula: P = C * [(1 - (1 + r)^-n) / r] + F / (1 + r)^n for r != 0
     * Formula: P = C * n + F for r = 0
     *
     * @param int|float|string|Decimal $faceValue The face value (par value) of the bond
     * @param int|float|string|Decimal $couponRate The coupon rate per period (as decimal, e.g., 0.05 for 5%)
     * @param int|float|string|Decimal $marketRate The market interest rate (yield) per period (as decimal, e.g., 0.04 for 4%)
     * @param int|float|string|Decimal $periods The number of periods until maturity
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function bondPrice(
        int|float|string|Decimal $faceValue,
        int|float|string|Decimal $couponRate,
        int|float|string|Decimal $marketRate,
        int|float|string|Decimal $periods
    ): ImmutableDecimal
    {
        /** @var ImmutableDecimal $faceValue */
        $faceValue = Numbers::makeOrDont(Numbers::IMMUTABLE, $faceValue);
        /** @var ImmutableDecimal $couponRate */
        $couponRate = Numbers::makeOrDont(Numbers::IMMUTABLE, $couponRate);
        /** @var ImmutableDecimal $marketRate */
        $marketRate = Numbers::makeOrDont(Numbers::IMMUTABLE, $marketRate);
        /** @var ImmutableDecimal $periods */
        $periods = Numbers::makeOrDont(Numbers::IMMUTABLE, $periods);

        // Calculate coupon payment: C = Face Value * Coupon Rate
        /** @var ImmutableDecimal $couponPayment */
        $couponPayment = $faceValue->multiply($couponRate);

        // Edge case: when market rate is 0, P = C * n + F
        if ($marketRate->isEqual(0)) {
            /** @var ImmutableDecimal $couponsPV */
            $couponsPV = $couponPayment->multiply($periods);
            return $couponsPV->add($faceValue);
        }

        // Validate that market rate is not -100% or less (would cause division by zero or negative base)
        /** @var ImmutableDecimal $negativeOne */
        $negativeOne = Numbers::makeOrDont(Numbers::IMMUTABLE, -1);
        if ($marketRate->isLessThanOrEqualTo($negativeOne)) {
            throw new IntegrityConstraint(
                'Market rate cannot be -100% or less',
                'Provide a market rate greater than -1',
                'Market rate must be > -1 to avoid division by zero or negative base in (1 + r)'
            );
        }

        // Calculate present value of coupon payments (annuity)
        // PV of coupons = C * [(1 - (1 + r)^-n) / r]
        /** @var ImmutableDecimal $couponsPV */
        $couponsPV = self::annuityPresentValue($couponPayment, $marketRate, $periods);

        // Calculate present value of face value
        // PV of face value = F / (1 + r)^n
        /** @var ImmutableDecimal $faceValuePV */
        $faceValuePV = self::presentValue($faceValue, $marketRate, $periods);

        // Bond price = PV of coupons + PV of face value
        return $couponsPV->add($faceValuePV);
    }

    /**
     * Calculate the yield to maturity (YTM) of a bond.
     *
     * YTM is the internal rate of return on the bond - the discount rate that makes
     * the present value of all future cash flows equal to the current bond price.
     * Uses the internal rate of return method on the bond's cash flows.
     *
     * Formula: Find r such that CurrentPrice = Σ(CF_t / (1 + r)^t) where CF includes coupons and face value
     *
     * @param int|float|string|Decimal $currentPrice The current market price of the bond
     * @param int|float|string|Decimal $faceValue The face value (par value) of the bond
     * @param int|float|string|Decimal $couponRate The coupon rate per period (as decimal, e.g., 0.05 for 5%)
     * @param int|float|string|Decimal $periods The number of periods until maturity
     * @param int|float|string|Decimal $guess Initial guess for YTM (default 0.05 for 5%)
     * @param int|float|string|Decimal $tolerance Convergence tolerance (default 0.000001)
     * @param int $maxIterations Maximum number of iterations (default 100)
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public static function bondYieldToMaturity(
        int|float|string|Decimal $currentPrice,
        int|float|string|Decimal $faceValue,
        int|float|string|Decimal $couponRate,
        int|float|string|Decimal $periods,
        int|float|string|Decimal $guess = null,
        int|float|string|Decimal $tolerance = 0.000001,
        int $maxIterations = 100
    ): ImmutableDecimal
    {
        /** @var ImmutableDecimal $currentPrice */
        $currentPrice = Numbers::makeOrDont(Numbers::IMMUTABLE, $currentPrice);
        /** @var ImmutableDecimal $faceValue */
        $faceValue = Numbers::makeOrDont(Numbers::IMMUTABLE, $faceValue);
        /** @var ImmutableDecimal $couponRate */
        $couponRate = Numbers::makeOrDont(Numbers::IMMUTABLE, $couponRate);
        /** @var ImmutableDecimal $periods */
        $periods = Numbers::makeOrDont(Numbers::IMMUTABLE, $periods);

        // Calculate coupon payment: C = Face Value * Coupon Rate
        /** @var ImmutableDecimal $couponPayment */
        $couponPayment = $faceValue->multiply($couponRate);

        // If no guess provided, use a financial approximation for better initial guess
        // YTM ≈ [C + (F - P) / n] / [(F + P) / 2]
        if ($guess === null) {
            /** @var ImmutableDecimal $numerator */
            $numerator = $couponPayment->add($faceValue->subtract($currentPrice)->divide($periods));
            /** @var ImmutableDecimal $denominator */
            $denominator = $faceValue->add($currentPrice)->divide(Numbers::makeOrDont(Numbers::IMMUTABLE, 2));
            /** @var ImmutableDecimal $initialGuess */
            $initialGuess = $numerator->divide($denominator);
        } else {
            $initialGuess = Numbers::makeOrDont(Numbers::IMMUTABLE, $guess);
        }

        // Build cash flows array for IRR calculation
        // Period 0: negative of current price (initial investment)
        // Periods 1 to n-1: coupon payments
        // Period n: coupon payment + face value
        $cashFlows = [];

        // Period 0: Initial investment (negative of current price)
        /** @var ImmutableDecimal $negativePrice */
        $negativePrice = $currentPrice->multiply(Numbers::makeOrDont(Numbers::IMMUTABLE, -1));
        $cashFlows[0] = $negativePrice;

        // Periods 1 to n: Build the cash flows
        $periodCount = (int)$periods->getValue();
        for ($i = 1; $i <= $periodCount; $i++) {
            if ($i === $periodCount) {
                // Last period: coupon payment + face value
                $cashFlows[$i] = $couponPayment->add($faceValue);
            } else {
                // Regular coupon payment
                $cashFlows[$i] = $couponPayment;
            }
        }

        // Use the internal rate of return method to find YTM
        return self::internalRateOfReturn($cashFlows, $initialGuess, $tolerance, $maxIterations);
    }

}
