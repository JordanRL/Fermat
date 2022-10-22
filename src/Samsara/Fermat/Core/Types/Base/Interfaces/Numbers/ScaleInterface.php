<?php

namespace Samsara\Fermat\Core\Types\Base\Interfaces\Numbers;

use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 *
 */
interface ScaleInterface
{

    /**
     * @return ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function ceil(): ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static;

    /**
     * @return ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function floor(): ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static;

    /**
     * @param int $decimals
     * @param RoundingMode|null $mode
     * @return ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function round(
        int $decimals = 0,
        ?RoundingMode $mode = null
    ): ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static;

    /**
     * @param int $decimals
     *
     * @return ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function truncate(
        int $decimals = 0
    ): ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static;

    /**
     * @param int $scale
     * @param RoundingMode|null $mode
     * @return ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function roundToScale(
        int $scale,
        ?RoundingMode $mode = null
    ): ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static;

    /**
     * @param int $scale
     *
     * @return ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function truncateToScale(
        int $scale
    ): ImmutableDecimal|MutableDecimal|ImmutableComplexNumber|MutableComplexNumber|static;

}