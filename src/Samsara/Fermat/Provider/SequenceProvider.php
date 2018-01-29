<?php

namespace Samsara\Fermat\Provider;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\NumberInterface;

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
     * @param $n
     *
     * @return DecimalInterface|NumberInterface
     */
    public static function nthOddNumber($n)
    {

        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, 100);

        return $n->multiply(2)->add(1);

    }

    /**
     * OEIS: A005843
     *
     * @param $n
     *
     * @return DecimalInterface|NumberInterface
     */
    public static function nthEvenNumber($n)
    {

        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, 100);

        return $n->multiply(2);

    }

    /**
     * @param $n
     *
     * @return DecimalInterface|NumberInterface
     */
    public static function nthPowerNegativeOne($n)
    {

        $negOne = Numbers::makeOrDont(Numbers::IMMUTABLE, -1, 100);

        return $negOne->pow($n);

    }

    /**
     * @param $n
     *
     * @return DecimalInterface|NumberInterface
     * @throws IntegrityConstraint
     */
    public static function nthEulerZigzag($n)
    {

        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, 100);

        if ($n->isGreaterThan(50)) {
            throw new IntegrityConstraint(
                '$n cannot be larger than 50',
                'Limit your use of the Euler Zigzag Sequence to the 50th index',
                'This library does not support the Euler Zigzag Sequence (OEIS: A000111) beyond E(50)'
            );
        }

        return Numbers::make(Numbers::IMMUTABLE, static::EULER_ZIGZAG[$n->asInt()], 100);

    }

    public static function nthSecTanCoefSequence($n)
    {

        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, 100);

        $pi = Numbers::makePi();
        $two = Numbers::make(Numbers::IMMUTABLE, 2, 100);

    }

    /**
     * WARNING: This function is VERY unoptimized. Be careful of large m values.
     *
     * @param $n
     *
     * @return DecimalInterface|NumberInterface
     */
    public static function nthBernoulliNumber($n)
    {

        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n, 100);

        if ($n->isEqual(0)) {
            return Numbers::makeOne();
        } elseif ($n->isEqual(1)) {
            return Numbers::make(Numbers::IMMUTABLE, '0.5', 100);
        }

        if ($n->isGreaterThan(1) && $n->modulo(2)->isEqual(1)) {
            return Numbers::makeZero();
        }

        $b = Numbers::make(Numbers::IMMUTABLE, -1, 100);
        $two = Numbers::make(Numbers::IMMUTABLE, 2, 100);
        $four = Numbers::make(Numbers::IMMUTABLE, 4, 100);

        $b = $b->pow($n->divide(2)->floor())
            ->multiply($n->divide($two->pow($n)->subtract($four->pow($n))))
            ->multiply(static::nthEulerZigzag($n));

        return $b;

    }

}