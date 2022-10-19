<?php

namespace Samsara\Fermat\Core\Bench\Providers;

use PhpBench\Attributes\Groups;
use PhpBench\Attributes\Revs;
use Samsara\Fermat\Core\Provider\ConstantProvider;

class ConstantProviderBench
{

    #[Groups(['logs', 'constants', 'providers', 'easy'])]
    #[Revs(1000)]
    public function benchMakeLn2Easy()
    {
        ConstantProvider::makeLn2(5);
    }

    #[Groups(['logs', 'constants', 'providers', 'medium'])]
    #[Revs(500)]
    public function benchMakeLn2Medium()
    {
        ConstantProvider::makeLn2(50);
    }

    #[Groups(['logs', 'constants', 'providers', 'hard'])]
    #[Revs(500)]
    public function benchMakeLn2Hard()
    {
        ConstantProvider::makeLn2(500);
    }

    #[Groups(['logs', 'constants', 'providers', 'easy'])]
    #[Revs(2000)]
    public function benchMakeLn1p1Easy()
    {
        ConstantProvider::makeLn1p1(5);
    }

    #[Groups(['logs', 'constants', 'providers', 'medium'])]
    #[Revs(2000)]
    public function benchMakeLn1p1Medium()
    {
        ConstantProvider::makeLn1p1(50);
    }

    #[Groups(['logs', 'constants', 'providers', 'hard'])]
    #[Revs(2000)]
    public function benchMakeLn1p1Hard()
    {
        ConstantProvider::makeLn1p1(500);
    }

    #[Groups(['logs', 'constants', 'providers', 'easy'])]
    #[Revs(1000)]
    public function benchMakeLn10Easy()
    {
        ConstantProvider::makeLn10(5);
    }

    #[Groups(['logs', 'constants', 'providers', 'medium'])]
    #[Revs(500)]
    public function benchMakeLn10Medium()
    {
        ConstantProvider::makeLn10(50);
    }

    #[Groups(['logs', 'constants', 'providers', 'hard'])]
    #[Revs(50)]
    public function benchMakeLn10Hard()
    {
        ConstantProvider::makeLn10(200);
    }

    #[Groups(['trig', 'constants', 'providers', 'easy'])]
    #[Revs(500)]
    public function benchMakePiEasy()
    {
        ConstantProvider::makePi(5);
    }

    #[Groups(['trig', 'constants', 'providers', 'medium'])]
    #[Revs(500)]
    public function benchMakePiMedium()
    {
        ConstantProvider::makePi(50);
    }

    #[Groups(['trig', 'constants', 'providers', 'hard'])]
    #[Revs(50)]
    public function benchMakePiHard()
    {
        ConstantProvider::makePi(500);
    }

    #[Groups(['logs', 'constants', 'providers', 'easy'])]
    #[Revs(500)]
    public function benchMakeEEasy()
    {
        ConstantProvider::makeE(5);
    }

    #[Groups(['logs', 'constants', 'providers', 'medium'])]
    #[Revs(500)]
    public function benchMakeEMedium()
    {
        ConstantProvider::makeE(50);
    }

    #[Groups(['logs', 'constants', 'providers', 'hard'])]
    #[Revs(50)]
    public function benchMakeEHard()
    {
        ConstantProvider::makeE(500);
    }

    #[Groups(['constants', 'providers', 'easy'])]
    #[Revs(500)]
    public function benchMakeGoldenRatioEasy()
    {
        ConstantProvider::makeGoldenRatio(5);
    }

    #[Groups(['constants', 'providers', 'medium'])]
    #[Revs(500)]
    public function benchMakeGoldenRatioMedium()
    {
        ConstantProvider::makeGoldenRatio(50);
    }

    #[Groups(['constants', 'providers', 'hard'])]
    #[Revs(50)]
    public function benchMakeGoldenRatioHard()
    {
        ConstantProvider::makeGoldenRatio(500);
    }

    #[Groups(['logs', 'constants', 'providers', 'easy'])]
    #[Revs(500)]
    public function benchMakeIPowIEasy()
    {
        ConstantProvider::makeIPowI(5);
    }

    #[Groups(['logs', 'constants', 'providers', 'medium'])]
    #[Revs(500)]
    public function benchMakeIPowIMedium()
    {
        ConstantProvider::makeIPowI(50);
    }

    #[Groups(['logs', 'constants', 'providers', 'hard'])]
    #[Revs(50)]
    public function benchMakeIPowIHard()
    {
        ConstantProvider::makeIPowI(500);
    }

}