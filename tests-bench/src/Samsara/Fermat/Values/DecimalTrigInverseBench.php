<?php


namespace Samsara\Fermat\Values;

use Samsara\Fermat\Types\Decimal;

class DecimalTrigInverseBench
{

    public function benchArcSin()
    {
        $point5 = new ImmutableDecimal('0.5');
        $point5->arcsin();
    }

    public function benchArcCos()
    {
        $point5 = new ImmutableDecimal('0.5');
        $point5->arccos();
    }

    public function benchArcTan()
    {
        $point5 = new ImmutableDecimal('0.5');
        $point5->arctan();
    }

    public function benchArcSec()
    {
        $point5 = new ImmutableDecimal('10');
        $point5->arcsec();
    }

    public function benchArcCsc()
    {
        $point5 = new ImmutableDecimal('10');
        $point5->arccsc();
    }

    public function benchArcCot()
    {
        $point5 = new ImmutableDecimal('0.5');
        $point5->arccot();
    }

}