<?php

namespace Samsara\Fermat\Stats\Distribution\Continuous;

use ReflectionException;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\RandomProvider;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\NumberCollection;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Stats\Provider\StatsProvider;
use Samsara\Fermat\Stats\Types\ContinuousDistribution;

/**
 * @package Samsara\Fermat\Stats
 */
class Normal extends ContinuousDistribution
{

    private ImmutableDecimal $mean;
    private ImmutableDecimal $sd;

    /**
     * Normal constructor.
     *
     * @param int|float|string|Decimal $mean
     * @param int|float|string|Decimal $standardDeviation
     *
     * @throws IntegrityConstraint
     */
    public function __construct(int|float|string|Decimal $mean, int|float|string|Decimal $standardDeviation)
    {
        /** @var ImmutableDecimal $mean */
        $mean = Numbers::makeOrDont(Numbers::IMMUTABLE, $mean);
        /** @var ImmutableDecimal $standardDeviation */
        $standardDeviation = Numbers::makeOrDont(Numbers::IMMUTABLE, $standardDeviation);

        $this->mean = $mean;
        $this->sd = $standardDeviation;
    }

    /**
     * @param int|float|string|Decimal $p
     * @param int|float|string|Decimal $x
     * @param int|float|string|Decimal $mean
     *
     * @return Normal
     * @throws IntegrityConstraint
     * @throws OptionalExit
     * @throws ReflectionException
     */
    public static function makeFromMean(
        int|float|string|Decimal $p,
        int|float|string|Decimal $x,
        int|float|string|Decimal $mean
    ): Normal
    {
        [$x, $mean, $z] = self::prepMake($p, $x, $mean);

        $sd = $x->subtract($mean)
            ->divide(
                $z,
                $z->getScale()
            )->abs()
            ->truncateToScale($z->getScale() - 2 - $mean->numberOfIntDigits());

        return new Normal($mean, $sd);
    }

    /**
     * @param int|float|string|Decimal $p
     * @param int|float|string|Decimal $x
     * @param int|float|string|Decimal $sd
     *
     * @return Normal
     * @throws IntegrityConstraint
     * @throws OptionalExit
     * @throws ReflectionException
     */
    public static function makeFromSd(
        int|float|string|Decimal $p,
        int|float|string|Decimal $x,
        int|float|string|Decimal $sd
    ): Normal
    {
        [$x, $sd, $z] = self::prepMake($p, $x, $sd);

        $mean = $x->add(
            $z->multiply($sd)
        )->truncateToScale($z->getScale() - 3);

        return new Normal($mean, $sd);
    }

    /**
     * @return ImmutableDecimal
     */
    public function getMean(): ImmutableDecimal
    {
        return $this->mean;
    }

    /**
     * @return ImmutableDecimal
     */
    public function getMedian(): ImmutableDecimal
    {
        return $this->getMean();
    }

    /**
     * @return ImmutableDecimal
     */
    public function getMode(): ImmutableDecimal
    {
        return $this->getMean();
    }

    /**
     * @return ImmutableDecimal
     */
    public function getSD(): ImmutableDecimal
    {
        return $this->sd;
    }

    /**
     * @return ImmutableDecimal
     */
    public function getVariance(): ImmutableDecimal
    {
        return $this->getSD()->pow(2);
    }

    /**
     * @param int|float|string|Decimal $x
     * @param int|null                 $scale
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws OptionalExit
     */
    public function cdf(int|float|string|Decimal $x, ?int $scale = null): ImmutableDecimal
    {
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        $scale = $scale ?? $x->getScale();
        $internalScale = $scale + 2;

        $oneHalf = Numbers::make(Numbers::IMMUTABLE, '0.5', $internalScale);
        $one = Numbers::makeOne();
        $sqrtTwo = Numbers::make(Numbers::IMMUTABLE, 2, $internalScale)->sqrt();

        /** @var ImmutableDecimal $cdf */
        $cdf = $oneHalf->multiply(
            $one->add(
                StatsProvider::gaussErrorFunction(
                    $x->subtract($this->mean)
                        ->divide(
                            $this->sd->multiply($sqrtTwo),
                            $internalScale
                        ),
                    $internalScale
                )
            )
        );

        return $cdf->roundToScale($scale);
    }

    /**
     * @param int|float|string|Decimal $x
     * @param int|null                 $scale
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function pdf(int|float|string|Decimal $x, ?int $scale = null): ImmutableDecimal
    {

        $one = Numbers::makeOne($scale);
        $twoPi = Numbers::make2Pi($scale);
        $e = Numbers::makeE($scale);
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        $scale = $scale ?? $x->getScale();
        $internalScale = $scale + 2;

        // $left = 1 / ( sqrt(2pi * SD^2) )
        $left =
            $one->divide(
                $twoPi->multiply(
                    $this->getSD()
                        ->pow(2)
                )->sqrt($internalScale),
                $internalScale
            );
        // $right = e^( -1*((x - SD)^2)/(2*SD^2) )
        $right =
            $e->pow(
                $x->subtract(
                    $this->getMean()
                )->pow(2)
                    ->divide(
                        $this->getSD()
                            ->pow(2)
                            ->multiply(2),
                        $internalScale
                    )->multiply(-1)
            );

        // Return value is not inlined to ensure proper return type for IDE
        /** @var ImmutableDecimal $value */
        $value = $left->multiply($right)->truncateToScale($scale);

        return $value;

    }

    /**
     * @param int|float|string|Decimal $x
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public function percentAboveX(int|float|string|Decimal $x): ImmutableDecimal
    {
        $one = Numbers::makeOne();

        /** @var ImmutableDecimal $perc */
        $perc = $one->subtract($this->cdf($x));

        return $perc;
    }

    /**
     * @param int|float|string|Decimal $x
     *
     * @return ImmutableDecimal
     */
    public function percentBelowX(int|float|string|Decimal $x): ImmutableDecimal
    {
        return $this->cdf($x);
    }

    /**
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     *
     * @codeCoverageIgnore
     */
    public function random(): ImmutableDecimal
    {
        $rand1 = RandomProvider::randomDecimal(20);
        $rand2 = RandomProvider::randomDecimal(20);

        $randomNumber =
            $rand1->ln()
                ->multiply(-2)
                ->sqrt()
                ->multiply(
                    $rand2->multiply(Numbers::TAU)
                        ->cos(1, false)
                );
        /** @var ImmutableDecimal $randomNumber */
        $randomNumber = $randomNumber->multiply($this->sd)->add($this->mean);

        return $randomNumber;
    }

    /**
     * @param int|float|string|Decimal $z
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public function xFromZScore(int|float|string|Decimal $z): ImmutableDecimal
    {
        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $z);

        /** @var ImmutableDecimal $x */
        $x = $z->multiply($this->sd)->add($this->mean);

        return $x->roundToScale(10);
    }

    /**
     * @param int|float|string|Decimal $x
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public function zScoreOfX(int|float|string|Decimal $x): ImmutableDecimal
    {
        /** @var ImmutableDecimal $x */
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        /** @var ImmutableDecimal $z */
        $z = $x->subtract($this->mean)->divide($this->sd);

        return $z;
    }

    /**
     * @param int|float|string|Decimal $p
     * @param int|float|string|Decimal $x
     * @param int|float|string|Decimal $input
     *
     * @return ImmutableDecimal[]
     * @throws IntegrityConstraint
     * @throws OptionalExit
     * @throws ReflectionException
     */
    private static function prepMake(
        int|float|string|Decimal $p,
        int|float|string|Decimal $x,
        int|float|string|Decimal $input
    ): array
    {
        $one = Numbers::makeOne(10);
        /** @var ImmutableDecimal $p */
        $p = Numbers::makeOrDont(Numbers::IMMUTABLE, $p);
        /** @var ImmutableDecimal $x */
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);
        /** @var ImmutableDecimal $input */
        $input = Numbers::makeOrDont(Numbers::IMMUTABLE, $input);

        $internalScale = (new NumberCollection([$one, $p, $x, $input]))->selectScale();

        $internalScale += 2;

        $z = StatsProvider::inverseNormalCDF($one->subtract($p), $internalScale);

        return [
            $x,
            $input,
            $z,
        ];
    }
}