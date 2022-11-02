<?php

namespace Samsara\Fermat\Stats\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

abstract class ContinuousDistribution extends Distribution
{

    /**
     * @param int|float|string|Decimal $x1
     * @param int|float|string|Decimal $x2
     * @param int|null                 $scale
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public function percentBetween(int|float|string|Decimal $x1, int|float|string|Decimal $x2, ?int $scale = null): ImmutableDecimal
    {
        $x1 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x1);
        $x2 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x2);

        $inputsScale = ($x1->getScale() > $x2->getScale()) ? $x1->getScale() : $x2->getScale();
        $scale = $scale ?? $inputsScale;
        $internalScale = $scale + 2;

        /** @var ImmutableDecimal $rangePdf */
        $rangePdf =
            $this->cdf(
                $x2,
                $internalScale
            )->subtract(
                $this->cdf(
                    $x1,
                    $internalScale)
            )->abs()
                ->truncateToScale($scale);

        return $rangePdf;
    }

    abstract public function pdf(int|float|string|Decimal $x, ?int $scale = null): ImmutableDecimal;

}