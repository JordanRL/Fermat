<?php


namespace Samsara\Fermat\Core\Values;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Groups;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;
use Samsara\Fermat\Core\Enums\CalcMode;

class DecimalTrigBasicBench
{
    public ImmutableDecimal $valueA;

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchSin()
    {
        $this->valueA->sin();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchCos()
    {
        $this->valueA->cos();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchTan()
    {
        $this->valueA->tan();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchSec()
    {
        $this->valueA->sec();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchCsc()
    {
        $this->valueA->csc();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchCot()
    {
        $this->valueA->cot();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchSinh()
    {
        $this->valueA->sinh();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchCosh()
    {
        $this->valueA->cosh();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchTanh()
    {
        $this->valueA->tanh();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchSech()
    {
        $this->valueA->sech();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchCsch()
    {
        $this->valueA->csch();
    }

    #[Groups(['trig', 'basic-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchCoth()
    {
        $this->valueA->coth();
    }

    public function provideNumbers()
    {
        return [
            'near-one' => ['valueA' => 1],
            'near-two' => ['valueA' => 2],
            'near-zero' => ['valueA' => '0.001'],
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
    }

}