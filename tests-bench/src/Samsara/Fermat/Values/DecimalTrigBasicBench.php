<?php


namespace Samsara\Fermat\Values;

class DecimalTrigBasicBench
{

    public function benchSin()
    {
        $obj = new ImmutableDecimal(1);
        $obj->sin();
    }

    public function benchCos()
    {
        $obj = new ImmutableDecimal(1);
        $obj->cos();
    }

    public function benchTan()
    {
        $obj = new ImmutableDecimal(1);
        $obj->tan();
    }

    public function benchSec()
    {
        $obj = new ImmutableDecimal(1);
        $obj->sec();
    }

    public function benchCsc()
    {
        $obj = new ImmutableDecimal(1);
        $obj->csc();
    }

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