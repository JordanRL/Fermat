<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Numbers;

use Samsara\Fermat\Values\ImmutableDecimal;

/**
 *
 */
interface FractionInterface extends SimpleNumberInterface
{

    /**
     * @return FractionInterface
     */
    public function simplify();

    /**
     * @return DecimalInterface
     */
    public function getNumerator();

    /**
     * @return DecimalInterface
     */
    public function getDenominator();

    /**
     * @param FractionInterface $fraction
     *
     * @return DecimalInterface
     */
    public function getSmallestCommonDenominator(FractionInterface $fraction);

    /**
     * @return ImmutableDecimal
     */
    public function asDecimal(): ImmutableDecimal;

}