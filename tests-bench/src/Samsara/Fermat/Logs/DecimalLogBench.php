<?php

namespace Samsara\Fermat\Core\Bench\Logs;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Groups;
use PhpBench\Attributes\ParamProviders;
use PhpBench\Attributes\Revs;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

class DecimalLogBench
{
    public ImmutableDecimal $valueA;

    #[Groups(['logs'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchLn()
    {
        $this->valueA->ln();
    }

    #[Groups(['logs'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchLog10()
    {
        $this->valueA->log10();
    }

    #[Groups(['logs'])]
    #[BeforeMethods('setUp')]
    #[ParamProviders(['provideNumbers', 'provideModes'])]
    public function benchExp()
    {
        $this->valueA->exp();
    }

    public function provideNumbers()
    {
        return [
            'int' => ['valueA' => 5],
            'dec' => ['valueA' => '1.5'],
            'hard' => ['valueA' => '1.5832947568392048757878954329086890732456748409342578978903245'],
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