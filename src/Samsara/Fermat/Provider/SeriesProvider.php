<?php

namespace Samsara\Fermat\Provider;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\Base\NumberInterface;

class SeriesProvider
{

    public static function maclaurinSeries(
        NumberInterface $input, // x value in series
        callable $signSwitch, // a function determining what the sign (+/-) at the nth term
        callable $exponent, // a function determining the exponent of x at the nth term
        callable $divisor, // a function determining the denominator at the nth term
        $startTermAt = 0,
        $precision = 10)
    {

        $x = Numbers::makeZero();
        $value = Numbers::make(Numbers::IMMUTABLE, $input->getValue());

        $continue = true;
        $termNumber = $startTermAt;

        while ($continue) {
            $term = Numbers::makeOne();

            $term = $term->multiply($value->pow($exponent($termNumber)))
                ->divide($divisor($termNumber))
                ->multiply($signSwitch($termNumber));

            if ($term->numberOfLeadingZeros() >= $precision) {
                $continue = false;
            }

            $x = $x->add($term);

            $termNumber++;
        }

        return $x->roundToPrecision($precision);

    }
    
}