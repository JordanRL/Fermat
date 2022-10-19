<?php

namespace Samsara\Fermat\Core\Values;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Groups;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;

class DecimalIntBench
{
    public ImmutableDecimal $valueA;
    public ImmutableDecimal $valueB;
    public ImmutableDecimal $valueC;

    #[Groups(['integer-math'])]
    #[Revs(2000)]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers'])]
    public function benchFactorial()
    {
        $this->valueA->factorial();
    }

    #[Groups(['integer-math'])]
    #[Revs(500)]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers'])]
    public function benchSubFactorial()
    {
        $this->valueA->subFactorial();
    }

    #[Groups(['integer-math'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers'])]
    public function benchDoubleFactorial()
    {
        $this->valueA->doubleFactorial();
    }

    #[Groups(['integer-math'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers'])]
    public function benchSemiFactorial()
    {
        $this->valueA->semiFactorial();
    }

    #[Groups(['integer-math'])]
    #[Revs(3000)]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers'])]
    public function benchLCM()
    {
        $this->valueA->getLeastCommonMultiple($this->valueB);
    }

    #[Groups(['integer-math'])]
    #[Revs(1000)]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers'])]
    public function benchGCD()
    {
        $this->valueA->getGreatestCommonDivisor($this->valueB);
    }

    #[Groups(['integer-math', 'prime-numbers'])]
    #[Revs(100)]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers'])]
    public function benchIsPrime()
    {
        $this->valueC->isPrime();
    }

    public function provideNumbers()
    {
        return [
            'small' => ['valueA' => 6, 'valueB' => 8, 'valueC' => 41],
            'medium' => ['valueA' => 16, 'valueB' => 24, 'valueC' => 1009],
            'large' => ['valueA' => 160, 'valueB' => 240, 'valueC' => 5915587277],
        ];
    }

    public function setUp(array $params)
    {
        $this->valueA = new ImmutableDecimal($params['valueA']);
        $this->valueB = new ImmutableDecimal($params['valueB']);
        $this->valueC = new ImmutableDecimal($params['valueC']);
    }

}