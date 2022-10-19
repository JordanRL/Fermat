<?php

namespace Samsara\Fermat\Core\Bench\Arithmetic;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Groups;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

class DecimalArithmeticBench
{
    public ImmutableDecimal $valueA;
    public ImmutableDecimal $valueB;

    #[Groups(['arithmetic'])]
    #[Revs(500)]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchAddInt()
    {
        $this->valueA->add($this->valueB);
    }

    #[Groups(['arithmetic'])]
    #[Revs(500)]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchSubtractInt()
    {
        $this->valueA->subtract($this->valueB);
    }

    #[Groups(['arithmetic'])]
    #[Revs(500)]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchMultiplyInt()
    {
        $this->valueA->multiply($this->valueB);
    }

    #[Groups(['arithmetic'])]
    #[Revs(500)]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchDivideInt()
    {
        $this->valueA->divide($this->valueB);
    }

    #[Groups(['arithmetic', 'arithmetic-pow'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchPowInt()
    {
        $this->valueA->pow($this->valueB);
    }

    #[Groups(['arithmetic'])]
    #[Revs(500)]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchSqrtInt()
    {
        $this->valueB->sqrt();
    }

    public function provideNumbers()
    {
        return [
            'int' => ['valueA' => 3, 'valueB' => 2],
            'dec' => ['valueA' => '1.5', 'valueB' => '2.6'],
            'hard' => ['valueA' => '1.5832947568392048757878954329086890732456748409342578978903245', 'valueB' => '2.65832947568392048757878954329086890732456748409342578978903245'],
        ];
    }

    public function provideModes()
    {
        return [
            'auto-mode' => ['mode' => CalcMode::Auto],
            'native-mode' => ['mode' => CalcMode::Native],
            'precision-mode' => ['mode' => CalcMode::Precision]
        ];
    }

    public function setUp(array $params)
    {
        $this->valueA = (new ImmutableDecimal($params['valueA']))->setMode($params['mode']);
        $this->valueB = (new ImmutableDecimal($params['valueB']))->setMode($params['mode']);
    }

}