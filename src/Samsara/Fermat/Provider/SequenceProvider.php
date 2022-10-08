<?php

namespace Samsara\Fermat\Provider;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\CalcMode;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\NumberCollection;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 *
 */
class SequenceProvider
{

    const EULER_ZIGZAG = [
        '1',                                                        // 0
        '1',                                                        // 1
        '1',                                                        // 2
        '2',                                                        // 3
        '5',                                                        // 4
        '16',                                                       // 5
        '61',                                                       // 6
        '272',                                                      // 7
        '1385',                                                     // 8
        '7936',                                                     // 9
        '50521',                                                    // 10
        '353792',                                                   // 11
        '2702765',                                                  // 12
        '22368256',                                                 // 13
        '199360981',                                                // 14
        '1903757312',                                               // 15
        '19391512145',                                              // 16
        '209865342976',                                             // 17
        '2404879675441',                                            // 18
        '29088885112832',                                           // 19
        '370371188237525',                                          // 20
        '4951498053124096',                                         // 21
        '69348874393137901',                                        // 22
        '1015423886506852352',                                      // 23
        '15514534163557086905',                                     // 24
        '246921480190207983616',                                    // 25
        '4087072509293123892361',                                   // 26
        '70251601603943959887872',                                  // 27
        '1252259641403629865468285',                                // 28
        '23119184187809597841473536',                               // 29
        '441543893249023104553682821',                              // 30
        '8713962757125169296170811392',                             // 31
        '177519391579539289436664789665',                           // 32
        '3729407703720529571097509625856',                          // 33
        '80723299235887898062168247453281',                         // 34
        '1798651693450888780071750349094912',                       // 35
        '41222060339517702122347079671259045',                      // 36
        '970982810785059112379399707952152576',                     // 37
        '23489580527043108252017828576198947741',                   // 38
        '583203324917310043943191641625494290432',                  // 39
        '14851150718114980017877156781405826684425',                // 40
        '387635983772083031828014624002175135645696',               // 41
        '10364622733519612119397957304745185976310201',             // 42
        '283727921907431909304183316295787837183229952',            // 43
        '7947579422597592703608040510088070619519273805',           // 44
        '227681379129930886488600284336316164603920777216',         // 45
        '6667537516685544977435028474773748197524107684661',        // 46
        '199500252157859031027160499643195658166340757225472',      // 47
        '6096278645568542158691685742876843153976539044435185',     // 48
        '190169564657928428175235445073924928592047775873499136',   // 49
        '6053285248188621896314383785111649088103498225146815121',  // 50
    ];

    /**
     * OEIS: A005408
     *
     * @param int $n
     * @param bool $asCollection
     * @param int $collectionSize
     *
     * @return DecimalInterface|NumberInterface|NumberCollection
     * @throws IntegrityConstraint|\ReflectionException
     */
    public static function nthOddNumber(int $n, bool $asCollection = false, int $collectionSize = 10)
    {
        if ($asCollection) {
            $sequence = new NumberCollection();

            for ($i = 0;$i < $collectionSize;$i++) {
                $sequence->push(self::nthOddNumber($n + $i));
            }

            return $sequence;
        }

        if ($n >= (PHP_INT_MAX/2)) {
            $n = new ImmutableDecimal($n, 100);

            return $n->multiply(2)->add(1);
        } else {
            return new ImmutableDecimal(($n*2)+1, 100);
        }

    }

    /**
     * OEIS: A005843
     *
     * @param int $n
     * @param bool $asCollection
     * @param int $collectionSize
     *
     * @return DecimalInterface|NumberInterface|NumberCollection
     * @throws IntegrityConstraint
     */
    public static function nthEvenNumber(int $n, int $scale = null, bool $asCollection = false, int $collectionSize = 10)
    {

        if ($asCollection) {
            $sequence = new NumberCollection();

            for ($i = 0;$i < $collectionSize;$i++) {
                $sequence->push(self::nthEvenNumber($n + $i));
            }

            return $sequence;
        }
        if ($n > (PHP_INT_MAX/2)) {
            $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, $scale);

            return $n->multiply(2);
        } else {
            return new ImmutableDecimal(($n*2), $scale);
        }

    }

    /**
     * OEIS: A033999
     *
     * @param int $n
     * @param bool $asCollection
     * @param int $collectionSize
     *
     * @return DecimalInterface|NumberInterface|NumberCollection
     * @throws IntegrityConstraint
     */
    public static function nthPowerNegativeOne(int $n, bool $asCollection = false, int $collectionSize = 10)
    {

        if ($asCollection) {
            $sequence = new NumberCollection();

            for ($i = 0;$i < $collectionSize;$i++) {
                $sequence->push(self::nthPowerNegativeOne($n + $i));
            }

            return $sequence;
        }

        return ($n % 2 ? new ImmutableDecimal(-1) : new ImmutableDecimal(1));

    }

    /**
     * OEIS: A000111
     *
     * @param int $n
     * @param bool $asCollection
     * @param int $collectionSize
     *
     * @return DecimalInterface|NumberInterface|NumberCollection
     * @throws IntegrityConstraint
     */
    public static function nthEulerZigzag(int $n, bool $asCollection = false, int $collectionSize = 10)
    {

        if ($asCollection) {
            $sequence = new NumberCollection();

            for ($i = 0;$i < $collectionSize;$i++) {
                $sequence->push(self::nthEulerZigzag($n + $i));
            }

            return $sequence;
        }

        if ($n > 50) {
            throw new IntegrityConstraint(
                '$n cannot be larger than 50',
                'Limit your use of the Euler Zigzag Sequence to the 50th index',
                'This library does not support the Euler Zigzag Sequence (OEIS: A000111) beyond E(50)'
            );
        }

        return Numbers::make(Numbers::IMMUTABLE, static::EULER_ZIGZAG[$n], 100);

    }

    /**
     * Returns the nth Bernoulli Number, where odd indexes return zero, and B1() is -1/2.
     *
     * This function gets very slow if you demand large precision.
     *
     * @param $n
     * @param int|null $scale
     * @return DecimalInterface
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public static function nthBernoulliNumber($n, ?int $scale = null): DecimalInterface
    {

        $scale = $scale ?? 5;

        $internalScale = (int)ceil($scale*(log10($scale)+1));

        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, $internalScale)->setMode(CalcMode::Precision);

        if (!$n->isWhole()) {
            throw new IntegrityConstraint(
                'Only integers may be indexes for Bernoulli numbers',
                'Ensure only integers are provided as indexes',
                'An attempt was made to get a Bernoulli number with a non-integer index'
            );
        }

        if ($n->isLessThan(0)) {
            throw new IntegrityConstraint(
                'Index must be non-negative',
                'Provide only non-negative indexes for Bernoulli number generation',
                'An attempt was made to get a Bernoulli number with a negative index'
            );
        }

        if ($n->isEqual(0)) {
            return Numbers::makeOne($scale);
        }

        if ($n->isEqual(1)) {
            return Numbers::make(Numbers::IMMUTABLE, '-0.5', $scale);
        }

        if ($n->modulo(2)->isEqual(1)) {
            return Numbers::makeZero($scale);
        }

        $tau = Numbers::makeTau($internalScale)->setMode(CalcMode::Precision);

        $d = Numbers::make(Numbers::IMMUTABLE, 4, $internalScale)->setMode(CalcMode::Precision)->add(
            $n->factorial()->ln($internalScale)->subtract(
                $n->multiply($tau->log10($internalScale))
            )->truncate()
        )->add(
            $n->numberOfIntDigits()
        );
        $s = $d->multiply(
            Numbers::makeNaturalLog10($internalScale)
        )->multiply(
            '0.5'
        )->divide($n, $internalScale)->exp($internalScale)->truncate()->add(1);
        $internalScale = ($d->isGreaterThan($internalScale)) ? $d->asInt() : $internalScale;

        $s = $s->truncateToScale($internalScale);
        $n = $n->truncateToScale($internalScale);
        $tau = Numbers::make2Pi($internalScale)->setMode(CalcMode::Precision);
        $p = Numbers::makeOne($internalScale)->setMode(CalcMode::Precision);
        $t1 = Numbers::makeOne($internalScale)->setMode(CalcMode::Precision);
        $t2 = Numbers::makeOne($internalScale)->setMode(CalcMode::Precision);

        while ($p->isLessThanOrEqualTo($s)) {
            $p = self::_nextprime($p);
            $pn = $p->pow($n);
            $pn1 = $pn->subtract(1);
            $t1 = $pn->multiply($t1);
            $t2 = $pn1->multiply($t2);
        }

        $z = $t1->divide($t2, $internalScale);
        $oz = Numbers::makeZero($internalScale)->setMode(CalcMode::Precision);

        while (!$oz->isEqual($z)) {
            $oz = $z;
            $p = self::_nextprime($p);
            $pn = $p->pow($n);
            $pn1 = $z->divide($pn, $internalScale);
            $z = $z->add($pn1);
        }

        $f = $n->factorial();
        $taun = $tau->pow($n);

        $z = $z->multiply(2)->multiply($f)->divide($taun, $internalScale);

        if ($n->modulo(4)->isEqual(0)) {
            $z = $z->multiply(-1);
        }

        return $z->round($scale);

    }

    /**
     * @param int $n
     * @return NumberCollection
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public static function nthPrimeNumbers(int $n): NumberCollection
    {
        $collection = new NumberCollection();

        $collection->push(Numbers::make(Numbers::IMMUTABLE, 2));

        $currentPrime = Numbers::make(Numbers::IMMUTABLE, 3);

        for ($i = 1;$i < $n;$i++) {
            while (!$currentPrime->isPrime()) {
                $currentPrime = $currentPrime->add(2);
            }

            $collection->push($currentPrime);
            $currentPrime = $currentPrime->add(2);
        }

        return $collection;
    }

    /**
     * OEIS: A000045
     *
     * This uses an implementation of the fast-doubling Karatsuba multiplication algorithm as described by 'Nayuki':
     *
     * https://www.nayuki.io/page/fast-fibonacci-algorithms
     *
     * @param int $n
     * @param bool $asCollection
     * @param int $collectionSize
     *
     * @return ImmutableDecimal|NumberCollection
     * @throws IntegrityConstraint
     */
    public static function nthFibonacciNumber(int $n, bool $asCollection = false, int $collectionSize = 10)
    {
        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

        if ($n->isLessThan(0)) {
            throw new IntegrityConstraint(
                'Negative term numbers not valid for Fibonacci Sequence',
                'Provide a positive term number',
                'A negative term number for the Fibonacci sequence was requested; provide a positive term number'
            );
        }

        $fastFib = static::_fib($n);

        if ($asCollection) {
            $sequence = new NumberCollection();
            $sequence->push($fastFib[0]);
            $sequence->push($fastFib[1]);
            for ($i = 0;$i < ($collectionSize-2);$i++) {
                $sequence->push($sequence->get($i)->add($sequence[$i+1]));
            }

            return $sequence;
        }

        return $fastFib[0];
    }

    /**
     * @param ImmutableDecimal $number
     * @return ImmutableDecimal[]
     * @throws IntegrityConstraint
     */
    private static function _fib(ImmutableDecimal $number): array
    {
        if ($number->isEqual(0)) {
            return [Numbers::makeZero(), Numbers::makeOne()];
        }

        /**
         * @var ImmutableDecimal $a
         * @var ImmutableDecimal $b
         * @var ImmutableDecimal $prevCall
         */
        $prevCall = $number->divide(2)->floor();
        [$a, $b] = static::_fib($prevCall);
        $c = $a->multiply($b->multiply(2)->subtract($a));
        $d = $a->multiply($a)->add($b->multiply($b));

        if ($number->modulo(2)->isEqual(0)) {
            return [$c, $d];
        }

        return [$d, $c->add($d)];
    }

    private static function _nextprime(ImmutableDecimal $number): ImmutableDecimal
    {
        if (function_exists('gmp_nextprime')) {
            return Numbers::make(Numbers::IMMUTABLE, gmp_strval(gmp_nextprime($number->getValue(NumberBase::Ten))));
        }

        $number = $number->add(1);
        while (!$number->isPrime()) {
            if ($number->modulo(2)->isEqual(1)) {
                $number = $number->add(2);
            } else {
                $number = $number->add(1);
            }
        }

        return $number;
    }

}