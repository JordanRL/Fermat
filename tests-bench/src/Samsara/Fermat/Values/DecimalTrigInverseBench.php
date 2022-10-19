<?php


namespace Samsara\Fermat\Core\Values;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Groups;
use PhpBench\Attributes\ParamProviders;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Types\Decimal;

class DecimalTrigInverseBench
{
    public ImmutableDecimal $valueA;
    public ImmutableDecimal $valueB;

    #[Groups(['trig', 'inverse-trig', 'arcsin'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcSin()
    {
        $this->valueB->arcsin();
    }

    #[Groups(['trig', 'inverse-trig', 'arccos'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcCos()
    {
        $this->valueB->arccos();
    }

    #[Groups(['trig', 'inverse-trig', 'arctan'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbersArctan', 'provideModes'])]
    public function benchArcTan()
    {
        $this->valueB->arctan();
    }

    #[Groups(['trig', 'inverse-trig', 'arcsec'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcSec()
    {
        $this->valueA->arcsec();
    }

    #[Groups(['trig', 'inverse-trig', 'arccsc'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcCsc()
    {
        $this->valueA->arccsc();
    }

    #[Groups(['trig', 'inverse-trig', 'arccot'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchArcCot()
    {
        $this->valueB->arccot();
    }

    public function provideNumbers()
    {
        return [
            'near-limit' => ['valueA' => '1.0000000001', 'valueB' => '0.9999999999'],
            'far-from-limit' => ['valueA' => '100000000000', 'valueB' => '0.0000000001'],
        ];
    }

    public function provideNumbersArctan()
    {
        return [
            'near-one' => ['valueA' => '1.001', 'valueB' => '0.9999999999'],
            'near-zero' => ['valueA' => '10', 'valueB' => '0.0000000001'],
            'near-ten-thousand' => ['valueA' => '1', 'valueB' => '10000']
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