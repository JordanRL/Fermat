<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Decimal;

class DecimalIntBench
{

    public function benchFactorial()
    {
        $five = new ImmutableDecimal(5);
        $five->factorial();
    }

    public function benchSubFactorial()
    {
        $five = new ImmutableDecimal(5);
        $five->subFactorial();
    }

    public function benchDoubleFactorial()
    {
        $five = new ImmutableDecimal(5);
        $five->doubleFactorial();
    }

    public function benchSemiFactorial()
    {
        $five = new ImmutableDecimal(5);
        $five->semiFactorial();
    }

    public function benchLCM()
    {
        $num = new ImmutableDecimal(6);
        $num->getLeastCommonMultiple(8);
    }

    public function benchGCD()
    {
        $num = new ImmutableDecimal(24);
        $num->getGreatestCommonDivisor(16);
    }

    public function benchIsPrime()
    {
        $num = new ImmutableDecimal(83);
        $num->isPrime();
    }

}