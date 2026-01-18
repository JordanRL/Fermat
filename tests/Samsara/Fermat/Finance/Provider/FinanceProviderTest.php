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

}
