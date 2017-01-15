<?php

namespace Samsara\Fermat\Provider\Distribution;

use RandomLib\Factory;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Values\ImmutableNumber;

class Exponential
{

    /**
     * @var ImmutableNumber
     */
    private $lambda;

    /**
     * Exponential constructor.
     *
     * @param int|float|DecimalInterface $lambda This is the *rate parameter* not the *scale parameter*
     *
     * @throws IntegrityConstraint
     */
    public function __construct($lambda)
    {
        $lambda = Numbers::makeOrDont(Numbers::IMMUTABLE, $lambda);

        if (!$lambda->isPositive()) {
            throw new IntegrityConstraint(
                'Lambda must be positive',
                'Provide a positive lambda',
                'Exponential distributions work on time to occurrence; the mean time to occurrence (lambda) must be positive'
            );
        }

        $this->lambda = $lambda;
    }

    /**
     * @param int|float|DecimalInterface $x
     *
     * @return ImmutableNumber
     * @throws IntegrityConstraint
     */
    public function cdf($x)
    {

        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);
        /** @var ImmutableNumber $one */
        $one = Numbers::makeOne();

        if (!$x->isPositive()) {
            throw new IntegrityConstraint(
                'X must be positive',
                'Provide a positive x',
                'Exponential distributions work on time to occurrence; the time to occurrence (x) must be positive'
            );
        }

        if (
            function_exists('stats_cdf_exponential') &&
            $x->isLessThanOrEqualTo(PHP_INT_MAX) &&
            $this->lambda->isLessThanOrEqualTo(PHP_INT_MAX)
        ) {
            return Numbers::make(Numbers::IMMUTABLE, stats_cdf_exponential($x->getValue(), $one->divide($this->lambda)->getValue(), 1));
        }

        /** @var ImmutableNumber $e */
        $e = Numbers::makeE();

        /** @var ImmutableNumber $cdf */
        $cdf = $one->subtract($e->pow($x->multiply($this->lambda)->multiply(-1)));

        return $cdf;

    }

    /**
     * @param $x
     *
     * @return ImmutableNumber
     * @throws IntegrityConstraint
     */
    public function pdf($x)
    {

        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);
        $one = Numbers::makeOne();

        if (!$x->isPositive()) {
            throw new IntegrityConstraint(
                'X must be positive',
                'Provide a positive x',
                'Exponential distributions work on time to occurrence; the time to occurrence (x) must be positive'
            );
        }

        if (
            function_exists('stats_dens_exponential') &&
            $x->isLessThanOrEqualTo(PHP_INT_MAX) &&
            $this->lambda->isLessThanOrEqualTo(PHP_INT_MAX)
        ) {
            return Numbers::make(Numbers::IMMUTABLE, stats_dens_exponential($x->getValue(), $one->divide($this->lambda)->getValue()));
        }

        /** @var ImmutableNumber $e */
        $e = Numbers::makeE();

        /** @var ImmutableNumber $pdf */
        $pdf = $this->lambda->multiply($e->pow($this->lambda->multiply(-1)->multiply($x)));

        return $pdf;

    }

    /**
     * @param $x1
     * @param $x2
     *
     * @return ImmutableNumber
     * @throws IntegrityConstraint
     */
    public function rangePdf($x1, $x2)
    {
        $x1 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x1);
        $x2 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x2);
        $one = Numbers::makeOne();

        if (!$x1->isPositive() || !$x2->isPositive()) {
            throw new IntegrityConstraint(
                'X must be positive',
                'Provide a positive x',
                'Exponential distributions work on time to occurrence; the time to occurrence (x) must be positive'
            );
        }

        if (
            function_exists('stats_dens_exponential') &&
            $x1->isLessThanOrEqualTo(PHP_INT_MAX) &&
            $x2->isLessThanOrEqualTo(PHP_INT_MAX) &&
            $this->lambda->isLessThanOrEqualTo(PHP_INT_MAX)
        ) {
            /** @var ImmutableNumber $pdf1 */
            $pdf1 = Numbers::make(Numbers::IMMUTABLE, stats_dens_exponential($x1->getValue(), $one->divide($this->lambda)->getValue()));
            /** @var ImmutableNumber $pdf2 */
            $pdf2 = Numbers::make(Numbers::IMMUTABLE, stats_dens_exponential($x2->getValue(), $one->divide($this->lambda)->getValue()));

            /** @var ImmutableNumber $rangePdf */
            $rangePdf = $pdf1->subtract($pdf2)->abs();

            return $rangePdf;
        }

        /** @var ImmutableNumber $rangePdf */
        $rangePdf = $this->pdf($x2)->subtract($this->pdf($x1))->abs();

        return $rangePdf;
    }

    /**
     * @return ImmutableNumber
     */
    public function random()
    {

        $randFactory = new Factory();
        $generator = $randFactory->getMediumStrengthGenerator();
        $one = Numbers::makeOne();
        $u = $generator->generateInt() / PHP_INT_MAX;

        /** @var ImmutableNumber $random */
        $random = $one->subtract($u)->ln()->divide($this->lambda->multiply(-1));

        return $random;

    }

    /**
     * @param int|float|DecimalInterface $min
     * @param int|float|DecimalInterface $max
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