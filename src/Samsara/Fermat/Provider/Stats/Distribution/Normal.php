<?php

namespace Samsara\Fermat\Provider\Stats\Distribution;

use RandomLib\Factory;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\Stats\Stats;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Values\ImmutableNumber;

class Normal
{

    /**
     * @var ImmutableNumber
     */
    private $mean;

    /**
     * @var ImmutableNumber
     */
    private $sd;

    /**
     * Normal constructor.
     *
     * @param int|float|DecimalInterface $mean
     * @param int|float|DecimalInterface $sd
     */
    public function __construct($mean, $sd)
    {
        $mean = Numbers::makeOrDont(Numbers::IMMUTABLE, $mean);
        $sd = Numbers::makeOrDont(Numbers::IMMUTABLE, $sd);

        $this->mean = $mean;
        $this->sd = $sd;
    }

    /**
     * @param int|float|DecimalInterface $p
     * @param int|float|DecimalInterface $x
     * @param int|float|DecimalInterface $mean
     *
     * @return Normal
     */
    public static function makeFromMean($p, $x, $mean)
    {
        $one = Numbers::makeOne();
        $p = Numbers::makeOrDont(Numbers::IMMUTABLE, $p);
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);
        $mean = Numbers::makeOrDont(Numbers::IMMUTABLE, $mean);

        $z = Stats::inverseNormalCDF($one->subtract($p));

        $sd = $x->subtract($mean)->divide($z);

        return new Normal($mean, $sd);
    }

    /**
     * @param int|float|DecimalInterface $p
     * @param int|float|DecimalInterface $x
     * @param int|float|DecimalInterface $sd
     *
     * @return Normal
     */
    public static function makeFromSd($p, $x, $sd)
    {
        $one = Numbers::makeOne();
        $p = Numbers::makeOrDont(Numbers::IMMUTABLE, $p);
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);
        $sd = Numbers::makeOrDont(Numbers::IMMUTABLE, $sd);

        $z = Stats::inverseNormalCDF($one->subtract($p));

        $mean = $x->subtract($z->multiply($sd));

        return new Normal($mean, $sd);
    }

    /**
     * @param int|float|DecimalInterface $x
     *
     * @return ImmutableNumber
     */
    public function cdf($x)
    {
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        if (
            function_exists('stats_cdf_normal') &&
            $x->isLessThanOrEqualTo(PHP_INT_MAX) &&
            $x->isGreaterThanOrEqualTo(PHP_INT_MIN) &&
            $this->mean->isLessThanOrEqualTo(PHP_INT_MAX) &&
            $this->mean->isGreaterThanOrEqualTo(PHP_INT_MIN) &&
            $this->sd->isLessThanOrEqualTo(PHP_INT_MAX) &&
            $this->sd->isGreaterThanOrEqualTo(PHP_INT_MIN)
        ) {
            return Numbers::make(Numbers::IMMUTABLE, stats_cdf_normal($x->getValue(), $this->mean, $this->sd));
        }

        $oneHalf = Numbers::make(Numbers::IMMUTABLE, '0.5');
        $one = Numbers::makeOne();
        $sqrtTwo = Numbers::make(Numbers::IMMUTABLE, 2)->sqrt();

        return $oneHalf->multiply($one->add(Stats::gaussErrorFunction(
            $x->subtract($this->mean)->divide($this->sd->multiply($sqrtTwo))
        )));
    }

    /**
     * @param int|float|DecimalInterface $x1
     * @param null|int|float|DecimalInterface $x2
     *
     * @return ImmutableNumber
     */
    public function pdf($x1, $x2 = null)
    {

        $x1 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x1);

        if (is_null($x2)) {
            $separation = $x1->subtract($this->mean)->multiply(2)->abs();

            if ($this->mean->isLessThan($x1)) {
                $x2 = $x1->subtract($separation);
            } else {
                $x2 = $x1->add($separation);
            }
        }

        /** @var ImmutableNumber $pdf */
        $pdf = $this->cdf($x1)->subtract($this->cdf($x2))->abs();

        return $pdf;
    }

    /**
     * @param int|float|DecimalInterface $x
     *
     * @return ImmutableNumber
     */
    public function percentBelowX($x)
    {
        return $this->cdf($x);
    }

    /**
     * @param int|float|DecimalInterface $x
     *
     * @return ImmutableNumber
     */
    public function percentAboveX($x)
    {
        $one = Numbers::makeOne();

        return $one->subtract($this->cdf($x));
    }

    /**
     * @param $x
     *
     * @return ImmutableNumber
     */
    public function zScoreOfX($x)
    {
        /** @var ImmutableNumber $x */
        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        /** @var ImmutableNumber $z */
        $z = $x->subtract($this->mean)->divide($this->sd);

        return $z;
    }

    /**
     * @param int|float|DecimalInterface $z
     *
     * @return ImmutableNumber
     */
    public function xFromZScore($z)
    {
        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $z);

        /** @var ImmutableNumber $x */
        $x = $z->multiply($this->sd)->add($this->mean);

        return $x;
    }

    /**
     * @return ImmutableNumber
     */
    public function random()
    {
        if (
            function_exists('stats_rand_gen_normal') &&
            $this->mean->isLessThanOrEqualTo(PHP_INT_MAX) &&
            $this->mean->isGreaterThanOrEqualTo(PHP_INT_MIN) &&
            $this->sd->isLessThanOrEqualTo(PHP_INT_MAX) &&
            $this->sd->isGreaterThanOrEqualTo(PHP_INT_MIN)
        ) {
            return Numbers::make(Numbers::IMMUTABLE, stats_rand_gen_normal($this->mean, $this->sd), 20);
        } else {
            $randFactory = new Factory();
            $generator = $randFactory->getMediumStrengthGenerator();

            $rand1 = Numbers::make(Numbers::IMMUTABLE, $generator->generateInt(), 20);
            $rand1 = $rand1->divide(PHP_INT_MAX);
            $rand2 = Numbers::make(Numbers::IMMUTABLE, $generator->generateInt(), 20);
            $rand2 = $rand2->divide(PHP_INT_MAX);

            $randomNumber = $rand1->ln()->multiply(-2)->sqrt()->multiply($rand2->multiply(Numbers::TAU)->cos(1, 2, 20));
            $randomNumber = $randomNumber->multiply($this->sd)->add($this->mean);

            return $randomNumber;
        }
    }

    /**
     * @param int|float|NumberInterface $min
     * @param int|float|NumberInterface $max
     * @param int $maxIterations
     *
     * @return ImmutableNumber
     * @throws OptionalExit
     */
    public function rangeRandom($min = 0, $max = PHP_INT_MAX, $maxIterations = 20)
    {
        $i = 0;

        do {
            $randomNumber = $this->random();
            $i++;
        } while (($randomNumber->isGreaterThanOrEqualTo($max) || $randomNumber->isLessThanOrEqualTo($min)) && $i < $maxIterations);

        if ($randomNumber->isGreaterThan($max) || $randomNumber->isLessThan($min)) {
            throw new OptionalExit(
                'All random numbers generated were outside of the requested range',
                'A suitable random number, restricted by the $max ('.$max.') and $min ('.$min.'), could not be found within '.$maxIterations.' iterations'
            );
        } else {
            return $randomNumber;
        }
    }

}