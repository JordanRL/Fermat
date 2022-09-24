<?php

namespace Samsara\Fermat\Values;

use PhpBench\Attributes\Groups;
use Samsara\Fermat\Enums\CalcMode;
use Samsara\Fermat\Types\Decimal;

class DecimalLogBench
{

    #[Groups(['testing', 'logs'])]
    public function benchLn()
    {
        $ten = new ImmutableDecimal(10);
        $ten->ln();
    }

    #[Groups(['testing', 'logs'])]
    public function benchLnNative()
    {
        $ten = (new ImmutableDecimal(10))->setMode(CalcMode::Native);
        $ten->ln();
    }

    #[Groups(['testing', 'logs'])]
    public function benchLnPrecision()
    {
        $ten = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $ten->ln();
    }

    #[Groups(['testing', 'logs'])]
    public function benchLog10()
    {
        $ten = new ImmutableDecimal(50);
        $ten->log10();
    }

    #[Groups(['testing', 'logs'])]
    public function benchLog10Native()
    {
        $ten = (new ImmutableDecimal(50))->setMode(CalcMode::Native);
        $ten->log10();
    }

    #[Groups(['testing', 'logs'])]
    public function benchLog10Precision()
    {
        $ten = (new ImmutableDecimal(50))->setMode(CalcMode::Precision);
        $ten->log10();
    }

    #[Groups(['testing', 'logs'])]
    public function benchExp()
    {
        $ten = new ImmutableDecimal(10);
        $ten->exp();
    }

    #[Groups(['testing', 'logs'])]
    public function benchExpNative()
    {
        $ten = (new ImmutableDecimal(10))->setMode(CalcMode::Native);
        $ten->exp();
    }

    #[Groups(['testing', 'logs'])]
    public function benchExpPrecision()
    {
        $ten = (new ImmutableDecimal(10))->setMode(CalcMode::Precision);
        $ten->exp();
    }

    #[Groups(['testing', 'logs'])]
    public function benchExpFloat()
    {
        $ten = new ImmutableDecimal('12.5');
        $ten->exp();
    }

    #[Groups(['testing', 'logs'])]
    public function benchExpFloatNative()
    {
        $ten = (new ImmutableDecimal('12.5'))->setMode(CalcMode::Native);
        $ten->exp();
    }

    #[Groups(['testing', 'logs'])]
    public function benchExpFloatPrecision()
    {
        $ten = (new ImmutableDecimal('12.5'))->setMode(CalcMode::Precision);
        $ten->exp();
    }

}