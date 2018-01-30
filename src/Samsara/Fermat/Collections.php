<?php

namespace Samsara\Fermat;

use Samsara\Fermat\Types\NumberCollection;

class Collections
{

    /**
     * Returns a NumberCollection object with the first $terms terms of the Fibonacci sequence (OEIS: A000045)
     *
     * NOTE: If what you need is JUST the nth term of this sequence, use the SequenceProvider::nthFibonacciNumber()
     * function which is much faster at calculating only that term, especially much later in the sequence.
     *
     * @param int $terms
     * @return NumberCollection
     */
    public static function fibonacciCollection(int $terms): NumberCollection
    {
        $collection = new NumberCollection();

        $k1 = Numbers::makeZero();
        $k2 = Numbers::makeOne();

        if ($terms == 1) {
            $collection->push($k1);
        } else {
            $collection->push($k1)->push($k2);
        }

        if ($terms > 2) {
            for ($n = 2;$n < $terms;$n++) {
                $kN = $k1->add($k2);
                $collection->push($kN);

                $k1 = $k2;
                $k2 = $kN;
            }
        }

        return $collection;
    }

}