# The Fermat Finance Module

This module provides various mathematical objects and functions to help a program perform complex financial calculations, most often useful in banking, investment, accounting, and financial analysis applications. Using the time value of money calculations, it becomes easy to create applications that compute loan payments and amortization schedules. Using the investment analysis functions, it becomes straightforward to evaluate the profitability of projects using NPV and IRR.

While many programs don't see a benefit from utilizing financial mathematics operations and functions, the ones that do benefit from it immensely.

## Key Concepts

This section details the key concepts of the library to understand how to create your program using it. This does *not* cover the key mathematical concepts within finance that this program implements.

### FinanceProvider

The `FinanceProvider` contains all financial calculation static functions that allow direct arbitrary precision calculation of the most common formulas in financial mathematics. All functions accept inputs as `int`, `float`, `string`, or `Decimal` objects and return `ImmutableDecimal` results for maximum precision.

### Interest Calculations

The Finance module supports both simple and compound interest calculations:

**Simple Interest** follows the formula $`I = P \times r \times t`$, where P is the principal, r is the interest rate, and t is time. This is useful for short-term financial instruments and basic interest calculations.

**Compound Interest** is calculated using the formula $`A = P(1 + \frac{r}{n})^{nt}`$ for regular compounding, where n is the compounding frequency. For continuous compounding (when n = 0), the formula becomes $`A = Pe^{rt}`$. Compound interest is essential for modeling most real-world savings accounts, investments, and loans.

Example usage:
```php
use Samsara\Fermat\Finance\Provider\FinanceProvider;

// Calculate compound interest: $1000 at 5% annual rate for 10 years, compounded quarterly
$futureValue = FinanceProvider::compoundInterest(
    principal: 1000,
    rate: 0.05,
    time: 10,
    frequency: 4  // Quarterly compounding
);
// Returns approximately $1643.62

// Calculate continuous compounding
$continuousValue = FinanceProvider::compoundInterest(
    principal: 1000,
    rate: 0.05,
    time: 10,
    frequency: 0  // Continuous compounding
);
// Returns approximately $1648.72
```

### Time Value of Money

The core principle that money available today is worth more than the same amount in the future due to its earning potential is captured in Present Value (PV) and Future Value (FV) calculations.

**Present Value** uses the formula $`PV = \frac{FV}{(1 + r)^n}`$ to determine what a future amount is worth today. This is essential for discount calculations and investment valuation.

**Future Value** uses the formula $`FV = PV \times (1 + r)^n`$ to determine what a current amount will be worth in the future given a specific interest rate.

Example usage:
```php
use Samsara\Fermat\Finance\Provider\FinanceProvider;

// What is $10,000 in 5 years worth today at a 6% annual discount rate?
$presentValue = FinanceProvider::presentValue(
    futureValue: 10000,
    rate: 0.06,
    periods: 5
);
// Returns approximately $7472.58

// What will $5,000 be worth in 3 years at 4% annual interest?
$futureValue = FinanceProvider::futureValue(
    presentValue: 5000,
    rate: 0.04,
    periods: 3
);
// Returns approximately $5624.32
```

### Annuities

An annuity is a series of equal payments made at regular intervals. The Finance module provides calculations for both the present and future value of ordinary annuities (payments at the end of each period).

**Annuity Present Value** calculates the current worth of a stream of future payments using the formula $`PV = PMT \times \frac{1 - (1 + r)^{-n}}{r}`$ for non-zero rates. This is useful for valuing pension plans, structured settlements, and other payment streams.

**Annuity Future Value** calculates what a series of regular payments will grow to using the formula $`FV = PMT \times \frac{(1 + r)^n - 1}{r}`$. This is essential for retirement planning and savings goal calculations.

### Loan Calculations

The Finance module provides comprehensive loan analysis capabilities:

**Loan Payment** calculates the periodic payment required to amortize a loan using the formula $`PMT = P \times \frac{r(1 + r)^n}{(1 + r)^n - 1}`$. This is the foundation for mortgage calculators and loan analysis tools.

**Amortization Schedule** generates a complete payment-by-payment breakdown showing how each payment is split between principal and interest, along with the remaining balance. This returns an array where each element contains:
- `period`: The payment period number
- `payment`: The total payment amount
- `principal`: The principal portion of the payment
- `interest`: The interest portion of the payment
- `balance`: The remaining loan balance after the payment

Example usage:
```php
use Samsara\Fermat\Finance\Provider\FinanceProvider;

// Calculate monthly payment on a $200,000 loan at 4.5% annual rate for 30 years
$monthlyPayment = FinanceProvider::loanPayment(
    principal: 200000,
    rate: 0.045 / 12,  // Monthly rate
    periods: 30 * 12    // 360 months
);
// Returns approximately $1013.37

// Generate complete amortization schedule
$schedule = FinanceProvider::amortizationSchedule(
    principal: 200000,
    rate: 0.045 / 12,
    periods: 30 * 12
);
// Returns array of 360 payment details
```

### Investment Analysis

For evaluating the profitability and return of investments and projects:

**Net Present Value (NPV)** calculates the present value of all cash flows using the formula $`NPV = \sum_{t=0}^{n} \frac{CF_t}{(1 + r)^t}`$. A positive NPV indicates a profitable investment. The cash flows array should be indexed by period, with period 0 typically being the initial investment (negative value).

**Internal Rate of Return (IRR)** finds the discount rate that makes NPV equal to zero. This represents the effective return rate of an investment. The calculation uses the Newton-Raphson iterative method for precise arbitrary precision results.

Example usage:
```php
use Samsara\Fermat\Finance\Provider\FinanceProvider;

// Evaluate a project: $100,000 initial investment with returns over 5 years
$cashFlows = [
    0 => -100000,  // Initial investment
    1 => 25000,
    2 => 30000,
    3 => 35000,
    4 => 30000,
    5 => 25000
];

// Calculate NPV at 8% discount rate
$npv = FinanceProvider::netPresentValue($cashFlows, 0.08);
// Returns approximately $16308.52 (profitable project)

// Calculate IRR
$irr = FinanceProvider::internalRateOfReturn($cashFlows);
// Returns approximately 0.1436 (14.36% return)
```

### Bond Calculations

For fixed-income securities analysis:

**Bond Price** calculates the theoretical price of a bond based on its coupon rate, market interest rate, and time to maturity. The formula combines the present value of coupon payments (an annuity) with the present value of the face value returned at maturity.

**Yield to Maturity (YTM)** calculates the total return anticipated on a bond if held until maturity. This is effectively the IRR of the bond's cash flows given its current market price. The function uses a financial approximation for the initial guess and then refines it using the Newton-Raphson method.

Example usage:
```php
use Samsara\Fermat\Finance\Provider\FinanceProvider;

// Price a $1000 face value bond with 6% coupon rate,
// when market rate is 5%, with 10 periods until maturity
$bondPrice = FinanceProvider::bondPrice(
    faceValue: 1000,
    couponRate: 0.06,
    marketRate: 0.05,
    periods: 10
);
// Returns approximately $1077.22 (premium bond, trades above par)

// Calculate YTM for a bond trading at $950
$ytm = FinanceProvider::bondYieldToMaturity(
    currentPrice: 950,
    faceValue: 1000,
    couponRate: 0.06,
    periods: 10
);
// Returns approximately 0.0683 (6.83% yield)
```

## Error Handling

All Finance module functions include comprehensive validation and error handling:

- **IntegrityConstraint Exception**: Thrown when inputs violate mathematical constraints, such as:
  - Interest/discount rates of -100% or less (would cause division by zero)
  - Empty cash flows arrays
  - IRR calculations that fail to converge
  - Zero derivatives in iterative calculations

- **IncompatibleObjectState Exception**: Thrown when Decimal object operations encounter incompatible states

Always ensure your interest rates, discount rates, and other financial parameters are within valid ranges for meaningful results.

## Performance Considerations

Financial calculations, especially iterative methods like IRR and YTM, can be computationally intensive when using arbitrary precision. The scale setting on your Decimal objects has a significant impact on performance:

- For most financial applications, a scale of 2-6 decimal places is sufficient
- Bond yield and IRR calculations may require 4-6 decimal places for accurate results
- The `maxIterations` parameter in IRR and YTM functions can be adjusted to balance precision and performance
- Amortization schedules for long-term loans (e.g., 30-year mortgages) generate large arrays and should be cached when possible

## Integration with Fermat Core

All Finance module calculations seamlessly integrate with Fermat's core number types:

- Accept inputs as `int`, `float`, `string`, or any `Decimal` type
- Return `ImmutableDecimal` results for thread-safety and precision
- Preserve arbitrary precision throughout all calculations
- Support chaining with other Fermat operations

This allows you to combine financial calculations with other mathematical operations in Fermat without losing precision or having to convert between different numeric types.
