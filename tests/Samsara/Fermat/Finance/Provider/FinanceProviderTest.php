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

}
