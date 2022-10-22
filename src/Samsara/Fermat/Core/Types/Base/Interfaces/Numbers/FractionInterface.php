<?php

namespace Samsara\Fermat\Core\Types\Base\Interfaces\Numbers;

use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Core
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