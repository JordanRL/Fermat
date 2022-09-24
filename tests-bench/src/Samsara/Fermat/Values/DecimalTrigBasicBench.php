<?php


namespace Samsara\Fermat\Values;

use PhpBench\Attributes\Groups;
use Samsara\Fermat\Enums\CalcMode;

class DecimalTrigBasicBench
{

    #[Groups(['testing', 'trig', 'basictrig'])]
    public function benchSin()
    {
        $obj = (new ImmutableDecimal(1))->setMode(CalcMode::Precision);
        $obj->sin();
    }

    #[Groups(['testing', 'trig', 'basictrig'])]
    public function benchCos()
    {
        $obj = new ImmutableDecimal(1);
        $obj->cos();
    }

    #[Groups(['testing', 'trig', 'basictrig'])]
    public function benchTan()
    {
        $obj = new ImmutableDecimal(1);
        $obj->tan();
    }

    #[Groups(['testing', 'trig', 'basictrig'])]
    public function benchSec()
    {
        $obj = new ImmutableDecimal(1);
        $obj->sec();
    }

    #[Groups(['testing', 'trig', 'basictrig'])]
    public function benchCsc()
    {
        $obj = new ImmutableDecimal(1);
        $obj->csc();
    }

    #[Groups(['testing', 'trig', 'basictrig'])]
    public function benchCot()
    {
        $obj = new ImmutableDecimal(1);
        $obj->cot();
    }

    public function benchSinh()
    {
        $obj = new ImmutableDecimal(1);
        $obj->sinh();
    }

    public function benchCosh()
    {
        $obj = new ImmutableDecimal(1);
        $obj->cosh();
    }

    public function benchTanh()
    {
        $obj = new ImmutableDecimal(1);
        $obj->tanh();
    }

    public function benchSech()
    {
        $obj = new ImmutableDecimal(1);
        $obj->sech();
    }

    public function benchCsch()
    {
        $obj = new ImmutableDecimal(1);
        $obj->csch();
    }

    public function benchCoth()
    {
        $obj = new ImmutableDecimal(1);
        $obj->coth();
    }

}