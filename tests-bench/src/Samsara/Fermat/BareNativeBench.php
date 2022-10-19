<?php

namespace Samsara\Fermat\Core\Bench;

use PhpBench\Attributes\Groups;
use PhpBench\Attributes\Revs;

class BareNativeBench
{

    #[Groups(['arithmetic', 'bare-native'])]
    #[Revs(1000000)]
    public function benchBareNativeBinaryOp()
    {
        3 + 2;
    }

    #[Groups(['arithmetic', 'bare-native'])]
    #[Revs(1000000)]
    public function benchBareNativePow()
    {
        3**2;
    }

    #[Groups(['arithmetic', 'bare-native'])]
    #[Revs(1000000)]
    public function benchBareNativeSqrt()
    {
        sqrt(3);
    }

    #[Groups(['trig', 'basic-trig', 'bare-native'])]
    #[Revs(1000000)]
    public function benchBareNativeTrig()
    {
        sin(1);
    }

    #[Groups(['trig', 'hyperbolic-trig', 'bare-native'])]
    #[Revs(1000000)]
    public function benchBareNativeTrigH()
    {
        sinh(1);
    }

    #[Groups(['trig', 'inverse-trig', 'bare-native'])]
    #[Revs(1000000)]
    public function benchBareNativeInvTrig()
    {
        asin(1);
    }

    #[Groups(['logs', 'bare-native'])]
    #[Revs(1000000)]
    public function benchBareNativeLn()
    {
        log(5);
    }

    #[Groups(['logs', 'bare-native'])]
    #[Revs(1000000)]
    public function benchBareNativeLog10()
    {
        log10(5);
    }

    #[Groups(['logs', 'bare-native'])]
    #[Revs(1000000)]
    public function benchBareNativeExp()
    {
        exp(5);
    }

}