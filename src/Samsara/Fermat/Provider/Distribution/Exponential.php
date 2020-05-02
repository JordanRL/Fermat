<?php

namespace Samsara\Fermat\Provider\Distribution;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\Distribution\Base\Distribution;
use Samsara\Fermat\Provider\PolyfillProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

class Exponential extends Distribution
{

    /**
     * @var ImmutableDecimal
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
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public function cdf($x): ImmutableDecimal
    {

        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);
        /** @var ImmutableDecimal $one */
        $one = Numbers::makeOne();

        if (!$x->isPositive()) {
            throw new IntegrityConstraint(
                'X must be positive',
                'Provide a positive x',
                'Exponential distributions work on time to occurrence; the time to occurrence (x) must be positive'
            );
        }

        /** @var ImmutableDecimal $e */
        $e = Numbers::makeE();

        /** @var ImmutableDecimal $cdf */
        $cdf = $one->subtract($e->pow($x->multiply($this->lambda)->multiply(-1)));

        return $cdf;

    }

    /**
     * @param $x
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public function pdf($x): ImmutableDecimal
    {

        $x = Numbers::makeOrDont(Numbers::IMMUTABLE, $x);

        if (!$x->isPositive()) {
            throw new IntegrityConstraint(
                'X must be positive',
                'Provide a positive x',
                'Exponential distributions work on time to occurrence; the time to occurrence (x) must be positive'
            );
        }

        /** @var ImmutableDecimal $e */
        $e = Numbers::makeE();

        /** @var ImmutableDecimal $pdf */
        $pdf = $this->lambda->multiply($e->pow($this->lambda->multiply(-1)->multiply($x)));

        return $pdf;

    }

    /**
     * @param $x1
     * @param $x2
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    public function rangePdf($x1, $x2): ImmutableDecimal
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

        /** @var ImmutableDecimal $rangePdf */
        $rangePdf = $this->pdf($x2)->subtract($this->pdf($x1))->abs();

        return $rangePdf;
    }

    /**
     * @return ImmutableDecimal
     */
    public function random(): ImmutableDecimal
    {

        $randomInt = PolyfillProvider::randomInt(0, PHP_INT_MAX);
        $one = Numbers::makeOne();
        $u = Numbers::make(Numbers::IMMUTABLE, $randomInt, 20);
        $u = $u->divide(PHP_INT_MAX);

        /** @var ImmutableDecimal $random */
        $random = $one->subtract($u)->ln()->divide($this->lambda->multiply(-1));

        return $random;

    }

    /**
     * @param int|float|DecimalInterface $min
     * @param int|float|DecimalInterface $max
     * @param int $maxIterations
     *
     * @return ImmutableDecimal
     * @throws OptionalExit
     */
    public function rangeRandom($min = 0, $max = PHP_INT_MAX, int $maxIterations = 20): ImmutableDecimal
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