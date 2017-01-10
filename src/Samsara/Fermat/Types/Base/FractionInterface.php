<?php

namespace Samsara\Fermat\Types\Base;

use Samsara\Fermat\Types\Fraction;

interface FractionInterface
{

    /**
     * @return NumberInterface|FractionInterface
     */
    public function simplify();

    /**
     * @return NumberInterface|DecimalInterface
     */
    public function getNumerator();

    /**
     * @return NumberInterface|DecimalInterface
     */
    public function getDenominator();

    /**
     * @param FractionInterface $fraction
     *
     * @return NumberInterface|FractionInterface
     */
    public function getSmallestCommonDenominator(FractionInterface $fraction);

}