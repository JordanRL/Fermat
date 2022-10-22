<?php

namespace Samsara\Fermat\Complex\Types\Traits;

use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 *
 */
trait ComplexScaleTrait
{

    /** @var int */
    protected int $scale;

    public function getScale(): int
    {
        return $this->scale;
    }

    /**
     * @param int $decimals
     *
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function truncate(
        int $decimals = 0
    ): ImmutableComplexNumber|MutableComplexNumber|static
    {
        $roundedReal = $this->realPart->truncate($decimals);
        $roundedImaginary = $this->imaginaryPart->truncate($decimals);

        return $this->setValue($roundedReal, $roundedImaginary);
    }

    /**
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function floor(): ImmutableComplexNumber|MutableComplexNumber|static
    {
        return $this->round(0, RoundingMode::Floor);
    }

    /**
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function ceil(): ImmutableComplexNumber|MutableComplexNumber|static
    {
        return $this->round(0, RoundingMode::Ceil);
    }

    /**
     * @param int $scale
     *
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function truncateToScale(
        int $scale
    ): ImmutableComplexNumber|MutableComplexNumber|static
    {
        $roundedReal = $this->realPart->truncate($scale);
        $roundedImaginary = $this->imaginaryPart->truncate($scale);

        return $this->setValue($roundedReal, $roundedImaginary, $scale);
    }

    /**
     * @param int $decimals
     * @param RoundingMode|null $mode
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function round(
        int           $decimals = 0,
        ?RoundingMode $mode = null
    ): ImmutableComplexNumber|MutableComplexNumber|static
    {
        $roundedReal = $this->realPart->round($decimals, $mode);
        $roundedImaginary = $this->imaginaryPart->round($decimals, $mode);

        return $this->setValue($roundedReal, $roundedImaginary);
    }

    /**
     * @param int $scale
     * @param RoundingMode|null $mode
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    public function roundToScale(
        int           $scale,
        ?RoundingMode $mode = null
    ): ImmutableComplexNumber|MutableComplexNumber|static
    {
        $roundedReal = $this->realPart->round($scale, $mode);
        $roundedImaginary = $this->imaginaryPart->round($scale, $mode);

        return $this->setValue($roundedReal, $roundedImaginary, $scale);
    }

    /**
     * @param ImmutableDecimal|ImmutableFraction $realPart
     * @param ImmutableDecimal|ImmutableFraction $imaginaryPart
     * @param int|null $scale
     * @return ImmutableComplexNumber|MutableComplexNumber|static
     */
    abstract protected function setValue(
        ImmutableDecimal|ImmutableFraction $realPart,
        ImmutableDecimal|ImmutableFraction $imaginaryPart,
        ?int $scale = null
    ): ImmutableComplexNumber|MutableComplexNumber|static;

}