<?php

namespace Samsara\Fermat\Provider\Distribution;

use RandomLib\Factory;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\Distribution\Base\Distribution;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Values\ImmutableNumber;

class Exponential extends Distribution
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
    public function cdf($x): ImmutableNumber
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
    public function pdf($x): ImmutableNumber
    {

        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        if (!$x->isPositive()) {
            throw new IntegrityConstraint(
                'X must be positive',
                'Provide a positive x',
                'Exponential distributions work on time to occurrence; the time to occurrence (x) must be positive'
            );
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
    public function rangePdf($x1, $x2): ImmutableNumber
    {
        $x1 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x1);
        $x2 = Numbers::makeOrDont(Numbers::IMMUTABLE, $x2);

        if (!$x1->isPositive() || !$x2->isPositive()) {
            throw new IntegrityConstraint(
                'X must be positive',
                'Provide a positive x',
                'Exponential distributions work on time to occurrence; the time to occurrence (x) must be positive'
            );
        }

        /** @var ImmutableNumber $rangePdf */
        $rangePdf = $this->pdf($x2)->subtract($this->pdf($x1))->abs();

        return $rangePdf;
    }

    /**
     * @return ImmutableNumber
     */
    public function random(): ImmutableNumber
    {

        $randFactory = new Factory();
        $generator = $randFactory->getMediumStrengthGenerator();
        $one = Numbers::makeOne();
        $u = Numbers::make(Numbers::IMMUTABLE, $generator->generateInt(), 20);
        $u = $u->divide(PHP_INT_MAX);

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
    public function rangeRandom($min = 0, $max = PHP_INT_MAX, int $maxIterations = 20): ImmutableNumber
    {

        $i = 0;

        do {
            $randomNumber = $this->random();
            $i++;
        } while (($randomNumber->isGreaterThan($max) || $randomNumber->isLessThan($min)) && $i < $maxIterations);

        if ($randomNumber->isGreaterThan($max) || $randomNumber->isLessThan($min)) {
            throw new OptionalExit(
                'All random numbers generated were outside of the requested range',
                'A suitable random number, restricted by the $max ('.$max.') and $min ('.$min.'), could not be found within '.$maxIterations.' iterations'
            );
        }

        return $randomNumber;
    }

}