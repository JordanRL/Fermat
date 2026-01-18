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

}
