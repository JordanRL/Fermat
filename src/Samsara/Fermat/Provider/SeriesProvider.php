<?php

namespace Samsara\Fermat\Provider;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\Base\NumberInterface;

class SeriesProvider
{
    
    public static function maclaurinSeries(NumberInterface $input, callable $derivative, $startTermAt = 0, $precision = 10)
    {
        $x = Numbers::make(Numbers::IMMUTABLE, 0);
        $value = Numbers::make(Numbers::IMMUTABLE, $input->getValue());
        
        $continue = true;
        $termNumber = $startTermAt;
        
        while ($continue) {
            if ($derivative($termNumber) == 0) {
                continue;
            }
            
            $term = Numbers::make(Numbers::MUTABLE, 1);
            
            $term->multiply($derivative($termNumber))
                ->divide(BCProvider::factorial($termNumber))
                ->multiply($value->pow($termNumber));
            
            if ($term->numberOfLeadingZeros() >= $precision) {
                $continue = false;
            }
            
            $x = $x->add($term);
        }
        
        return $x->roundToPrecision($precision);
    }
    
}