<?php

namespace Samsara\Fermat\Complex\Types\Base\Interfaces\Numbers;


use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Complex
 */
interface ComplexNumberInterface
{

    /**
     * @return ImmutableDecimal|ImmutableFraction
     */
    public function getImaginaryPart(): ImmutableDecimal|ImmutableFraction;

    /**
     * @return ImmutableDecimal|ImmutableFraction
     */
    public function getRealPart(): ImmutableDecimal|ImmutableFraction;

    /**
     * @return string
     */
    public function getValue(): string;

}