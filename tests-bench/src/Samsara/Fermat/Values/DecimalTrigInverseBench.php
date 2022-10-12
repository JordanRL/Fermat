<?php


namespace Samsara\Fermat\Values;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Groups;
use PhpBench\Attributes\ParamProviders;
use Samsara\Fermat\Enums\CalcMode;
use Samsara\Fermat\Types\Decimal;

class DecimalTrigInverseBench
{
    public ImmutableDecimal $valueA;
    public ImmutableDecimal $valueB;

    #[Groups(['trig', 'inverse-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcSin()
    {
        $this->valueB->arcsin();
    }

    #[Groups(['trig', 'inverse-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcCos()
    {
        $this->valueB->arccos();
    }

    #[Groups(['trig', 'inverse-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcTan()
    {
        $this->valueB->arctan();
    }

    #[Groups(['trig', 'inverse-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcSec()
    {
        $this->valueA->arcsec();
    }

    #[Groups(['trig', 'inverse-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcCsc()
    {
        $this->valueA->arccsc();
    }

    #[Groups(['trig', 'inverse-trig'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcCot()
    {
        $this->valueB->arccot();
    }

    public function provideNumbers()
    {
        return [
            'near-limit' => ['valueA' => '1.1', 'valueB' => '0.9'],
            'away-from-limit' => ['valueA' => '10', 'valueB' => '0.001'],
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