<?php

namespace Samsara\Fermat\Values;

class DecimalArithmeticBench
{

    public function benchAddSimple()
    {
        $obj = new ImmutableDecimal(1);
        $obj->add(2);
    }

    public function benchSubtractSimple()
    {
        $obj = new ImmutableDecimal(1);
        $obj->subtract(2);
    }

    public function benchMultiplySimple()
    {
        $obj = new ImmutableDecimal(1);
        $obj->multiply(2);
    }

    public function benchDivideSimple()
    {
        $obj = new ImmutableDecimal(1);
        $obj->divide(2);
    }

    public function benchPowSimple()
    {
        $obj = new ImmutableDecimal(3);
        $obj->pow(2);
    }

    public function benchSqrtSimple()
    {
        $obj = new ImmutableDecimal(2);
        $obj->sqrt();
    }

}