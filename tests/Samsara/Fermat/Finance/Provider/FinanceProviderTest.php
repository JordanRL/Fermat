<?php

namespace Samsara\Fermat\Finance\Provider;

use PHPUnit\Framework\TestCase;

class FinanceProviderTest extends TestCase
{

    /**
     * @small
     */
    public function testSimpleInterest()
    {
        // Test case 1: Principal = 1000, Rate = 5% (0.05), Time = 2 years
        // Expected: 1000 * 0.05 * 2 = 100
        $answer1 = FinanceProvider::simpleInterest(1000, 0.05, 2);
        $this->assertEquals('100', $answer1->getValue());

        // Test case 2: Principal = 5000, Rate = 3.5% (0.035), Time = 3 years
        // Expected: 5000 * 0.035 * 3 = 525
        $answer2 = FinanceProvider::simpleInterest(5000, 0.035, 3);
        $this->assertEquals('525', $answer2->getValue());

        // Test case 3: Principal = 10000, Rate = 4.25% (0.0425), Time = 1 year
        // Expected: 10000 * 0.0425 * 1 = 425
        $answer3 = FinanceProvider::simpleInterest(10000, 0.0425, 1);
        $this->assertEquals('425', $answer3->getValue());
    }

    /**
     * @small
     */
    public function testCompoundInterest()
    {
        // Test case 1: Annual compounding
        // Principal = 1000, Rate = 5% (0.05), Time = 2 years, Frequency = 1 (annual)
        // Expected: 1000(1 + 0.05/1)^(1*2) = 1000 * 1.05^2 = 1102.5
        $answer1 = FinanceProvider::compoundInterest(1000, 0.05, 2, 1);
        $this->assertEquals('1102.5', $answer1->getValue());

        // Test case 2: Quarterly compounding
        // Principal = 5000, Rate = 6% (0.06), Time = 3 years, Frequency = 4 (quarterly)
        // Expected: 5000(1 + 0.06/4)^(4*3) = 5000 * 1.015^12 ≈ 5978.09
        $answer2 = FinanceProvider::compoundInterest(5000, 0.06, 3, 4);
        $this->assertEquals('5978.09', $answer2->round(2)->getValue());

        // Test case 3: Monthly compounding
        // Principal = 10000, Rate = 4% (0.04), Time = 5 years, Frequency = 12 (monthly)
        // Expected: 10000(1 + 0.04/12)^(12*5) ≈ 12209.97
        $answer3 = FinanceProvider::compoundInterest(10000, 0.04, 5, 12);
        $this->assertEquals('12209.97', $answer3->round(2)->getValue());

        // Test case 4: Continuous compounding
        // Principal = 1000, Rate = 5% (0.05), Time = 2 years, Frequency = 0 (continuous)
        // Expected: 1000 * e^(0.05*2) = 1000 * e^0.1 ≈ 1105.17
        $answer4 = FinanceProvider::compoundInterest(1000, 0.05, 2, 0);
        $this->assertEquals('1105.17', $answer4->round(2)->getValue());
    }

    /**
     * @small
     */
    public function testPresentValue()
    {
        // Test case 1: Basic present value
        // Future Value = 1102.5, Rate = 5% (0.05), Periods = 2
        // Expected: 1102.5 / (1.05)^2 = 1102.5 / 1.1025 = 1000
        $answer1 = FinanceProvider::presentValue(1102.5, 0.05, 2);
        $this->assertEquals('1000', $answer1->getValue());

        // Test case 2: Present value with different rate
        // Future Value = 5000, Rate = 8% (0.08), Periods = 3
        // Expected: 5000 / (1.08)^3 = 5000 / 1.259712 ≈ 3969.16
        $answer2 = FinanceProvider::presentValue(5000, 0.08, 3);
        $this->assertEquals('3969.16', $answer2->round(2)->getValue());

        // Test case 3: Present value with fractional rate
        // Future Value = 10000, Rate = 4.5% (0.045), Periods = 5
        // Expected: 10000 / (1.045)^5 = 10000 / 1.24618... ≈ 8024.51
        $answer3 = FinanceProvider::presentValue(10000, 0.045, 5);
        $this->assertEquals('8024.51', $answer3->round(2)->getValue());

        // Test case 4: Edge case with zero rate
        // Future Value = 1000, Rate = 0% (0), Periods = 5
        // Expected: 1000 / (1.0)^5 = 1000
        $answer4 = FinanceProvider::presentValue(1000, 0, 5);
        $this->assertEquals('1000', $answer4->getValue());
    }

    /**
     * @small
     */
    public function testFutureValue()
    {
        // Test case 1: Basic future value
        // Present Value = 1000, Rate = 5% (0.05), Periods = 2
        // Expected: 1000 * (1.05)^2 = 1000 * 1.1025 = 1102.5
        $answer1 = FinanceProvider::futureValue(1000, 0.05, 2);
        $this->assertEquals('1102.5', $answer1->getValue());

        // Test case 2: Future value with different rate
        // Present Value = 3969.16, Rate = 8% (0.08), Periods = 3
        // Expected: 3969.16 * (1.08)^3 = 3969.16 * 1.259712 ≈ 5000
        $answer2 = FinanceProvider::futureValue(3969.16, 0.08, 3);
        $this->assertEquals('5000', $answer2->round(2)->getValue());

        // Test case 3: Future value with fractional rate
        // Present Value = 8024.51, Rate = 4.5% (0.045), Periods = 5
        // Expected: 8024.51 * (1.045)^5 = 8024.51 * 1.24618... ≈ 10000
        $answer3 = FinanceProvider::futureValue(8024.51, 0.045, 5);
        $this->assertEquals('10000', $answer3->round(2)->getValue());

        // Test case 4: Edge case with zero rate
        // Present Value = 1000, Rate = 0% (0), Periods = 5
        // Expected: 1000 * (1.0)^5 = 1000
        $answer4 = FinanceProvider::futureValue(1000, 0, 5);
        $this->assertEquals('1000', $answer4->getValue());
    }

    /**
     * @small
     */
    public function testAnnuityPresentValue()
    {
        // Test case 1: Basic annuity present value
        // Payment = 100, Rate = 5% (0.05), Periods = 10
        // Expected: 100 * [(1 - (1.05)^-10) / 0.05] = 100 * 7.72173... ≈ 772.17
        $answer1 = FinanceProvider::annuityPresentValue(100, 0.05, 10);
        $this->assertEquals('772.17', $answer1->round(2)->getValue());

        // Test case 2: Annuity with different rate
        // Payment = 500, Rate = 6% (0.06), Periods = 5
        // Expected: 500 * [(1 - (1.06)^-5) / 0.06] = 500 * 4.21236... ≈ 2106.18
        $answer2 = FinanceProvider::annuityPresentValue(500, 0.06, 5);
        $this->assertEquals('2106.18', $answer2->round(2)->getValue());

        // Test case 3: Longer term annuity
        // Payment = 1000, Rate = 4% (0.04), Periods = 20
        // Expected: 1000 * [(1 - (1.04)^-20) / 0.04] = 1000 * 13.59032... ≈ 13590.33
        $answer3 = FinanceProvider::annuityPresentValue(1000, 0.04, 20);
        $this->assertEquals('13590.33', $answer3->round(2)->getValue());

        // Test case 4: Edge case with zero rate
        // Payment = 100, Rate = 0% (0), Periods = 10
        // Expected: 100 * 10 = 1000
        $answer4 = FinanceProvider::annuityPresentValue(100, 0, 10);
        $this->assertEquals('1000', $answer4->getValue());
    }

    /**
     * @small
     */
    public function testAnnuityFutureValue()
    {
        // Test case 1: Basic annuity future value
        // Payment = 100, Rate = 5% (0.05), Periods = 10
        // Expected: 100 * [((1.05)^10 - 1) / 0.05] = 100 * 12.57789... ≈ 1257.79
        $answer1 = FinanceProvider::annuityFutureValue(100, 0.05, 10);
        $this->assertEquals('1257.79', $answer1->round(2)->getValue());

        // Test case 2: Annuity with different rate
        // Payment = 500, Rate = 6% (0.06), Periods = 5
        // Expected: 500 * [((1.06)^5 - 1) / 0.06] = 500 * 5.63709... ≈ 2818.55
        $answer2 = FinanceProvider::annuityFutureValue(500, 0.06, 5);
        $this->assertEquals('2818.55', $answer2->round(2)->getValue());

        // Test case 3: Longer term annuity
        // Payment = 1000, Rate = 4% (0.04), Periods = 20
        // Expected: 1000 * [((1.04)^20 - 1) / 0.04] = 1000 * 29.77807... ≈ 29778.08
        $answer3 = FinanceProvider::annuityFutureValue(1000, 0.04, 20);
        $this->assertEquals('29778.08', $answer3->round(2)->getValue());

        // Test case 4: Edge case with zero rate
        // Payment = 100, Rate = 0% (0), Periods = 10
        // Expected: 100 * 10 = 1000
        $answer4 = FinanceProvider::annuityFutureValue(100, 0, 10);
        $this->assertEquals('1000', $answer4->getValue());
    }

    /**
     * @small
     */
    public function testLoanPayment()
    {
        // Test case 1: Basic loan payment
        // Principal = 10000, Rate = 5% (0.05), Periods = 12
        // Expected: 10000 * [0.05(1.05)^12] / [(1.05)^12 - 1] ≈ 1128.25
        $answer1 = FinanceProvider::loanPayment(10000, 0.05, 12);
        $this->assertEquals('1128.25', $answer1->round(2)->getValue());

        // Test case 2: Loan with different rate
        // Principal = 20000, Rate = 6% (0.06), Periods = 24
        // Expected: 20000 * [0.06(1.06)^24] / [(1.06)^24 - 1] ≈ 1593.58
        $answer2 = FinanceProvider::loanPayment(20000, 0.06, 24);
        $this->assertEquals('1593.58', $answer2->round(2)->getValue());

        // Test case 3: Loan with different terms
        // Principal = 5000, Rate = 3% (0.03), Periods = 10
        // Expected: 5000 * [0.03(1.03)^10] / [(1.03)^10 - 1] ≈ 586.15
        $answer3 = FinanceProvider::loanPayment(5000, 0.03, 10);
        $this->assertEquals('586.15', $answer3->round(2)->getValue());

        // Test case 4: Edge case with zero rate
        // Principal = 10000, Rate = 0% (0), Periods = 10
        // Expected: 10000 / 10 = 1000
        $answer4 = FinanceProvider::loanPayment(10000, 0, 10);
        $this->assertEquals('1000', $answer4->getValue());
    }

    /**
     * @small
     */
    public function testAmortizationSchedule()
    {
        // Test case 1: Simple loan amortization
        // Principal = 10000, Rate = 5% (0.05), Periods = 12
        // Payment ≈ 1128.25 per period
        $schedule = FinanceProvider::amortizationSchedule(10000, 0.05, 12);

        // Verify we have 12 periods
        $this->assertCount(12, $schedule);

        // Verify first payment details
        // Period 1: Balance = 10000, Interest = 10000 * 0.05 = 500
        // Principal = 1128.25 - 500 = 628.25, New Balance = 10000 - 628.25 = 9371.75
        $this->assertEquals(1, $schedule[0]['period']);
        $this->assertEquals('1128.25', $schedule[0]['payment']->round(2)->getValue());
        $this->assertEquals('500', $schedule[0]['interest']->round(2)->getValue());
        $this->assertEquals('628.25', $schedule[0]['principal']->round(2)->getValue());
        $this->assertEquals('9371.75', $schedule[0]['balance']->round(2)->getValue());

        // Verify last payment
        $this->assertEquals(12, $schedule[11]['period']);
        $this->assertEquals('0', $schedule[11]['balance']->getValue());

        // Verify sum of principal payments equals original principal
        $totalPrincipal = array_reduce($schedule, function($carry, $item) {
            return $carry->add($item['principal']);
        }, \Samsara\Fermat\Core\Numbers::makeOrDont(\Samsara\Fermat\Core\Numbers::IMMUTABLE, 0));
        $this->assertEquals('10000', $totalPrincipal->round(2)->getValue());

        // Test case 2: Loan with zero interest
        // Principal = 1000, Rate = 0% (0), Periods = 10
        // Payment = 1000 / 10 = 100 per period
        $schedule2 = FinanceProvider::amortizationSchedule(1000, 0, 10);

        $this->assertCount(10, $schedule2);
        $this->assertEquals('100', $schedule2[0]['payment']->getValue());
        $this->assertEquals('0', $schedule2[0]['interest']->getValue());
        $this->assertEquals('100', $schedule2[0]['principal']->getValue());
        $this->assertEquals('900', $schedule2[0]['balance']->getValue());
        $this->assertEquals('0', $schedule2[9]['balance']->getValue());
    }

    /**
     * @small
     */
    public function testNetPresentValue()
    {
        // Test case 1: Basic NPV calculation
        // Cash flows: [-1000, 300, 300, 300, 300], Rate = 10% (0.1)
        // Expected: -1000 + 300/1.1 + 300/1.1^2 + 300/1.1^3 + 300/1.1^4
        // = -1000 + 272.727272 + 247.933884 + 225.394440 + 204.904036 ≈ -49.04
        $cashFlows1 = [0 => -1000, 1 => 300, 2 => 300, 3 => 300, 4 => 300];
        $answer1 = FinanceProvider::netPresentValue($cashFlows1, 0.1);
        $this->assertEquals('-49.04', $answer1->round(2)->getValue());

        // Test case 2: Positive NPV (profitable project)
        // Cash flows: [-1000, 400, 400, 400, 400], Rate = 10% (0.1)
        // Expected: -1000 + 400/1.1 + 400/1.1^2 + 400/1.1^3 + 400/1.1^4
        // = -1000 + 363.636363 + 330.578512 + 300.526102 + 273.205547 ≈ 267.95
        $cashFlows2 = [0 => -1000, 1 => 400, 2 => 400, 3 => 400, 4 => 400];
        $answer2 = FinanceProvider::netPresentValue($cashFlows2, 0.1);
        $this->assertEquals('267.95', $answer2->round(2)->getValue());

        // Test case 3: NPV with varying cash flows
        // Cash flows: [-5000, 1000, 2000, 3000, 2000], Rate = 8% (0.08)
        // Expected: -5000 + 1000/1.08 + 2000/1.08^2 + 3000/1.08^3 + 2000/1.08^4
        // = -5000 + 925.92592 + 1714.67764 + 2381.49672 + 1470.05971 ≈ 1492.16
        $cashFlows3 = [0 => -5000, 1 => 1000, 2 => 2000, 3 => 3000, 4 => 2000];
        $answer3 = FinanceProvider::netPresentValue($cashFlows3, 0.08);
        $this->assertEquals('1492.16', $answer3->round(2)->getValue());

        // Test case 4: Edge case with zero rate
        // Cash flows: [-1000, 300, 300, 300, 300], Rate = 0% (0)
        // Expected: -1000 + 300 + 300 + 300 + 300 = 200
        $cashFlows4 = [0 => -1000, 1 => 300, 2 => 300, 3 => 300, 4 => 300];
        $answer4 = FinanceProvider::netPresentValue($cashFlows4, 0);
        $this->assertEquals('200', $answer4->getValue());

        // Test case 5: Single period NPV
        // Cash flows: [-1000, 1100], Rate = 10% (0.1)
        // Expected: -1000 + 1100/1.1 = -1000 + 1000 = 0
        $cashFlows5 = [0 => -1000, 1 => 1100];
        $answer5 = FinanceProvider::netPresentValue($cashFlows5, 0.1);
        $this->assertEquals('0', $answer5->getValue());
    }

    /**
     * @small
     */
    public function testInternalRateOfReturn()
    {
        // Test case 1: Basic IRR calculation
        // Cash flows: [-1000, 1100], should have IRR = 10% (0.1)
        // Verification: -1000 + 1100/(1.1)^1 = -1000 + 1000 = 0
        $cashFlows1 = [0 => -1000, 1 => 1100];
        $answer1 = FinanceProvider::internalRateOfReturn($cashFlows1);
        $this->assertEquals('0.1', $answer1->round(4)->getValue());

        // Test case 2: Multi-period IRR
        // Cash flows: [-1000, 300, 400, 500, 200]
        // IRR should be approximately 11.79%
        $cashFlows2 = [0 => -1000, 1 => 300, 2 => 400, 3 => 500, 4 => 200];
        $answer2 = FinanceProvider::internalRateOfReturn($cashFlows2);
        // Verify that NPV at this rate is approximately zero
        $npv2 = FinanceProvider::netPresentValue($cashFlows2, $answer2);
        $this->assertEquals('0', $npv2->round(2)->getValue());

        // Test case 3: Standard project cash flows
        // Cash flows: [-5000, 1000, 2000, 2000, 3000]
        // This represents a project with initial investment and increasing returns
        $cashFlows3 = [0 => -5000, 1 => 1000, 2 => 2000, 3 => 2000, 4 => 3000];
        $answer3 = FinanceProvider::internalRateOfReturn($cashFlows3);
        // Verify that NPV at this rate is approximately zero
        $npv3 = FinanceProvider::netPresentValue($cashFlows3, $answer3);
        $this->assertEquals('0', $npv3->round(2)->getValue());

        // Test case 4: IRR with different initial guess
        // Cash flows: [-1000, 400, 400, 400, 400]
        // Using a different initial guess should still converge
        $cashFlows4 = [0 => -1000, 1 => 400, 2 => 400, 3 => 400, 4 => 400];
        $answer4 = FinanceProvider::internalRateOfReturn($cashFlows4, 0.2);
        // Verify that NPV at this rate is approximately zero
        $npv4 = FinanceProvider::netPresentValue($cashFlows4, $answer4);
        $this->assertEquals('0', $npv4->round(2)->getValue());

        // Test case 5: Simple two-period case with known IRR
        // Cash flows: [-2000, 2200], IRR = 10% (0.1)
        // Verification: -2000 + 2200/(1.1)^1 = -2000 + 2000 = 0
        $cashFlows5 = [0 => -2000, 1 => 2200];
        $answer5 = FinanceProvider::internalRateOfReturn($cashFlows5);
        $this->assertEquals('0.1', $answer5->round(4)->getValue());
    }

}
