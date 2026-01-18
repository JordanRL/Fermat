# Samsara\Fermat\Finance\Provider > FinanceProvider

*No description available*


## Methods


### Static Methods

!!! signature "public FinanceProvider::simpleInterest(int|float|string|Decimal $principal, int|float|string|Decimal $rate, int|float|string|Decimal $time)"
    ##### simpleInterest
    **$principal**

    type
    :   int|float|string|Decimal

    description
    :   The principal amount

    **$rate**

    type
    :   int|float|string|Decimal

    description
    :   The interest rate (as decimal, e.g., 0.05 for 5%)

    **$time**

    type
    :   int|float|string|Decimal

    description
    :   The time period



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### simpleInterest() Description:

    Calculate simple interest.

    Formula: I = P * r * t

---

!!! signature "public FinanceProvider::compoundInterest(int|float|string|Decimal $principal, int|float|string|Decimal $rate, int|float|string|Decimal $time, int|float|string|Decimal $frequency)"
    ##### compoundInterest
    **$principal**

    type
    :   int|float|string|Decimal

    description
    :   The principal amount

    **$rate**

    type
    :   int|float|string|Decimal

    description
    :   The annual interest rate (as decimal, e.g., 0.05 for 5%)

    **$time**

    type
    :   int|float|string|Decimal

    description
    :   The time period in years

    **$frequency**

    type
    :   int|float|string|Decimal

    description
    :   The number of times interest is compounded per year (use 0 for continuous)



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### compoundInterest() Description:

    Calculate compound interest.

    Formula: A = P(1 + r/n)^(nt) for regular compounding
    Formula: A = Pe^(rt) for continuous compounding (when n = 0)

---

!!! signature "public FinanceProvider::presentValue(int|float|string|Decimal $futureValue, int|float|string|Decimal $rate, int|float|string|Decimal $periods)"
    ##### presentValue
    **$futureValue**

    type
    :   int|float|string|Decimal

    description
    :   The future value

    **$rate**

    type
    :   int|float|string|Decimal

    description
    :   The discount rate per period (as decimal, e.g., 0.05 for 5%)

    **$periods**

    type
    :   int|float|string|Decimal

    description
    :   The number of periods



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### presentValue() Description:

    Calculate present value.

    Formula: PV = FV / (1 + r)^n

---

!!! signature "public FinanceProvider::futureValue(int|float|string|Decimal $presentValue, int|float|string|Decimal $rate, int|float|string|Decimal $periods)"
    ##### futureValue
    **$presentValue**

    type
    :   int|float|string|Decimal

    description
    :   The present value

    **$rate**

    type
    :   int|float|string|Decimal

    description
    :   The interest rate per period (as decimal, e.g., 0.05 for 5%)

    **$periods**

    type
    :   int|float|string|Decimal

    description
    :   The number of periods



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### futureValue() Description:

    Calculate future value.

    Formula: FV = PV * (1 + r)^n

---

!!! signature "public FinanceProvider::annuityPresentValue(int|float|string|Decimal $payment, int|float|string|Decimal $rate, int|float|string|Decimal $periods)"
    ##### annuityPresentValue
    **$payment**

    type
    :   int|float|string|Decimal

    description
    :   The payment amount per period

    **$rate**

    type
    :   int|float|string|Decimal

    description
    :   The interest rate per period (as decimal, e.g., 0.05 for 5%)

    **$periods**

    type
    :   int|float|string|Decimal

    description
    :   The number of periods



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### annuityPresentValue() Description:

    Calculate the present value of an annuity (ordinary annuity).

    Formula: PV = PMT * [(1 - (1 + r)^-n) / r] for r != 0
    Formula: PV = PMT * n for r = 0

---

!!! signature "public FinanceProvider::annuityFutureValue(int|float|string|Decimal $payment, int|float|string|Decimal $rate, int|float|string|Decimal $periods)"
    ##### annuityFutureValue
    **$payment**

    type
    :   int|float|string|Decimal

    description
    :   The payment amount per period

    **$rate**

    type
    :   int|float|string|Decimal

    description
    :   The interest rate per period (as decimal, e.g., 0.05 for 5%)

    **$periods**

    type
    :   int|float|string|Decimal

    description
    :   The number of periods



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### annuityFutureValue() Description:

    Calculate the future value of an annuity (ordinary annuity).

    Formula: FV = PMT * [((1 + r)^n - 1) / r] for r != 0
    Formula: FV = PMT * n for r = 0

---

!!! signature "public FinanceProvider::loanPayment(int|float|string|Decimal $principal, int|float|string|Decimal $rate, int|float|string|Decimal $periods)"
    ##### loanPayment
    **$principal**

    type
    :   int|float|string|Decimal

    description
    :   The principal (loan amount)

    **$rate**

    type
    :   int|float|string|Decimal

    description
    :   The interest rate per period (as decimal, e.g., 0.05 for 5%)

    **$periods**

    type
    :   int|float|string|Decimal

    description
    :   The number of periods



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### loanPayment() Description:

    Calculate loan payment amount (ordinary annuity payment).

    Formula: PMT = P * [r(1 + r)^n] / [(1 + r)^n - 1] for r != 0
    Formula: PMT = P / n for r = 0

---

!!! signature "public FinanceProvider::amortizationSchedule(int|float|string|Decimal $principal, int|float|string|Decimal $rate, int|float|string|Decimal $periods)"
    ##### amortizationSchedule
    **$principal**

    type
    :   int|float|string|Decimal

    description
    :   The principal (loan amount)

    **$rate**

    type
    :   int|float|string|Decimal

    description
    :   The interest rate per period (as decimal, e.g., 0.05 for 5%)

    **$periods**

    type
    :   int|float|string|Decimal

    description
    :   The number of periods



    **return**

    type
    :   array

    description
    :   Array of payment details for each period

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### amortizationSchedule() Description:

    Generate a loan amortization schedule.

    Returns an array of payment details for each period, including:
    - period: The payment period number
    - payment: The total payment amount
    - principal: The principal portion of the payment
    - interest: The interest portion of the payment
    - balance: The remaining loan balance after the payment

---

!!! signature "public FinanceProvider::netPresentValue(array $cashFlows, int|float|string|Decimal $rate)"
    ##### netPresentValue
    **$cashFlows**

    type
    :   array

    description
    :   Array of cash flows indexed by period (period 0 is initial investment, typically negative)

    **$rate**

    type
    :   int|float|string|Decimal

    description
    :   The discount rate per period (as decimal, e.g., 0.05 for 5%)



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### netPresentValue() Description:

    Calculate net present value (NPV) of a series of cash flows.

    Formula: NPV = Σ(CF_t / (1 + r)^t) for t from 0 to n

---

!!! signature "public FinanceProvider::internalRateOfReturn(array $cashFlows, int|float|string|Decimal $guess, int|float|string|Decimal $tolerance, int $maxIterations)"
    ##### internalRateOfReturn
    **$cashFlows**

    type
    :   array

    description
    :   Array of cash flows indexed by period (period 0 is initial investment, typically negative)

    **$guess**

    type
    :   int|float|string|Decimal

    description
    :   Initial guess for IRR (default 0.1 for 10%)

    **$tolerance**

    type
    :   int|float|string|Decimal

    description
    :   Convergence tolerance (default 0.000001)

    **$maxIterations**

    type
    :   int

    description
    :   Maximum number of iterations (default 100)



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### internalRateOfReturn() Description:

    Calculate the internal rate of return (IRR) for a series of cash flows.

    IRR is the discount rate that makes the net present value (NPV) equal to zero.
    Uses the Newton-Raphson method for iterative approximation.

    Formula: Find r such that NPV = Σ(CF_t / (1 + r)^t) = 0

---

!!! signature "public FinanceProvider::bondPrice(int|float|string|Decimal $faceValue, int|float|string|Decimal $couponRate, int|float|string|Decimal $marketRate, int|float|string|Decimal $periods)"
    ##### bondPrice
    **$faceValue**

    type
    :   int|float|string|Decimal

    description
    :   The face value (par value) of the bond

    **$couponRate**

    type
    :   int|float|string|Decimal

    description
    :   The coupon rate per period (as decimal, e.g., 0.05 for 5%)

    **$marketRate**

    type
    :   int|float|string|Decimal

    description
    :   The market interest rate (yield) per period (as decimal, e.g., 0.04 for 4%)

    **$periods**

    type
    :   int|float|string|Decimal

    description
    :   The number of periods until maturity



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### bondPrice() Description:

    Calculate the price of a bond.

    Formula: P = C * [(1 - (1 + r)^-n) / r] + F / (1 + r)^n for r != 0
    Formula: P = C * n + F for r = 0

---

!!! signature "public FinanceProvider::bondYieldToMaturity(int|float|string|Decimal $currentPrice, int|float|string|Decimal $faceValue, int|float|string|Decimal $couponRate, int|float|string|Decimal $periods, int|float|string|Decimal $guess, int|float|string|Decimal $tolerance, int $maxIterations)"
    ##### bondYieldToMaturity
    **$currentPrice**

    type
    :   int|float|string|Decimal

    description
    :   The current market price of the bond

    **$faceValue**

    type
    :   int|float|string|Decimal

    description
    :   The face value (par value) of the bond

    **$couponRate**

    type
    :   int|float|string|Decimal

    description
    :   The coupon rate per period (as decimal, e.g., 0.05 for 5%)

    **$periods**

    type
    :   int|float|string|Decimal

    description
    :   The number of periods until maturity

    **$guess**

    type
    :   int|float|string|Decimal

    description
    :   Initial guess for YTM (default 0.05 for 5%)

    **$tolerance**

    type
    :   int|float|string|Decimal

    description
    :   Convergence tolerance (default 0.000001)

    **$maxIterations**

    type
    :   int

    description
    :   Maximum number of iterations (default 100)



    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **throws**

    Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState
    :

    Samsara\Exceptions\UsageError\IntegrityConstraint
    :

    ###### bondYieldToMaturity() Description:

    Calculate the yield to maturity (YTM) of a bond.

    YTM is the internal rate of return on the bond - the discount rate that makes
    the present value of all future cash flows equal to the current bond price.
    Uses the internal rate of return method on the bond's cash flows.

    Formula: Find r such that CurrentPrice = Σ(CF_t / (1 + r)^t) where CF includes coupons and face value

---
