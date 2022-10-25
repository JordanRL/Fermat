<?php


namespace Samsara\Fermat\Core\Provider;


use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
class ConstantProvider
{

    private static Decimal $pi;
    private static Decimal $e;
    private static Decimal $ln10;
    private static Decimal $ln2;
    private static Decimal $ln1p1;
    private static Decimal $phi;
    private static Decimal $ipowi;

    /**
     * @param int $digits
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public static function makePi(int $digits): string
    {

        if (isset(self::$pi) && self::$pi->numberOfDecimalDigits() >= $digits) {
            return self::$pi->truncateToScale($digits)->getValue(NumberBase::Ten);
        }

        $internalScale = ($digits*2) + 10;

        $C = Numbers::make(Numbers::IMMUTABLE, '10005', $internalScale)->setMode(CalcMode::Precision)->sqrt($internalScale)->multiply(426880);
        $M = Numbers::make(Numbers::IMMUTABLE, '1', $internalScale)->setMode(CalcMode::Precision);
        $L = Numbers::make(Numbers::IMMUTABLE, '13591409', $internalScale)->setMode(CalcMode::Precision);
        $K = Numbers::make(Numbers::IMMUTABLE, '6', $internalScale)->setMode(CalcMode::Precision);
        $X = Numbers::make(Numbers::IMMUTABLE, '1')->setMode(CalcMode::Precision);
        $sum = Numbers::make(Numbers::MUTABLE,'0', $internalScale + 2)->setMode(CalcMode::Precision);
        $termNum = 0;
        $one = Numbers::makeOne($internalScale)->setMode(CalcMode::Precision);

        $continue = true;

        while ($continue) {
            $term = $M->multiply($L)->divide($X, $internalScale);

            if ($termNum > $internalScale) {
                $continue = false;
            }

            $sum->add($term);

            $M = $M->multiply($K->pow(3)->subtract($K->multiply(16))->divide($one->add($termNum)->pow(3), $internalScale));
            $L = $L->add(545140134);
            $X = $X->multiply('-262537412640768000');
            $K = $K->add(12);
            $termNum++;
        }

        $pi = $C->divide($sum, $internalScale);

        self::$pi = $pi->truncateToScale($digits);

        return $pi->truncateToScale($digits)->getValue(NumberBase::Ten);

    }

    /**
     * Consider also: sum [0 -> INF] { (2n + 2) / (2n + 1)! }
     *
     * This converges faster (though it's unclear if the calculation is actually faster), and can be represented by this
     * set of Fermat calls:
     *
     * SequenceProvider::nthEvenNumber($n + 1)->divide(SequenceProvider::nthOddNumber($n)->factorial());
     *
     * Perhaps by substituting the nthOddNumber()->factorial() call with something tracked locally, the performance can
     * be improved. Current performance is acceptable even out past 200 digits.
     *
     * @param int $digits
     * @return string
     * @throws IntegrityConstraint
     */
    public static function makeE(int $digits): string
    {

        if (isset(self::$e) && self::$e->numberOfDecimalDigits() >= $digits) {
            return self::$e->truncateToScale($digits)->getValue(NumberBase::Ten);
        }

        $internalScale = $digits + 3;

        $one = Numbers::makeOne($internalScale+5)->setMode(CalcMode::Precision);
        $denominator = Numbers::make(Numbers::MUTABLE, '1', $internalScale)->setMode(CalcMode::Precision);
        $e = Numbers::make(NUmbers::MUTABLE, '2', $internalScale)->setMode(CalcMode::Precision);
        $n = Numbers::make(Numbers::MUTABLE, '2', $internalScale)->setMode(CalcMode::Precision);

        $continue = true;

        while ($continue) {
            $denominator->multiply($n);
            $n->add($one);
            $term = $one->divide($denominator);

            if ($term->numberOfLeadingZeros() > $internalScale || $term->isEqual(0)) {
                $continue = false;
            }

            $e->add($term);
        }

        self::$e = $e->truncateToScale($digits);

        return $e->truncateToScale($digits)->getValue(NumberBase::Ten);

    }

    /**
     * @param int $digits
     * @return string
     * @throws IntegrityConstraint
     */
    public static function makeGoldenRatio(int $digits): string
    {

        if ($digits < 5) {
            throw new IntegrityConstraint(
                'Cannot return so few digits of phi.',
                'For fewer digits of phi, use Numbers::makeGoldenRatio()'
            );
        }

        if (isset(self::$phi) && self::$phi->numberOfDecimalDigits() >= $digits) {
            return self::$phi->truncateToScale($digits)->getValue(NumberBase::Ten);
        }

        $fibIndex = floor($digits*2.5);

        [$fibSmall, $fibLarge] = SequenceProvider::nthFibonacciPair($fibIndex);

        self::$phi = $fibLarge->divide($fibSmall, $digits+2)->truncateToScale($digits);

        return self::$phi->getValue(NumberBase::Ten);

    }

    /**
     * @param int $digits
     * @return string
     */
    public static function makeIPowI(int $digits): string
    {

        if (isset(self::$ipowi) && self::$ipowi->numberOfDecimalDigits() >= $digits) {
            return self::$ipowi->truncateToScale($digits)->getValue(NumberBase::Ten);
        }

        $piDivTwo = Numbers::makePi($digits+2)->divide(2)->multiply(-1);

        $e = Numbers::makeE($digits+2);

        self::$ipowi = $e->pow($piDivTwo)->truncateToScale($digits);

        return self::$ipowi->getValue(NumberBase::Ten);

    }

    /**
     * The lnScale() implementation is very efficient, so this is probably our best bet for computing more digits of
     * ln(10) to provide.
     *
     * @param int $digits
     * @return string
     * @throws IntegrityConstraint
     */
    public static function makeLn10(int $digits): string
    {

        if (isset(self::$ln10) && self::$ln10->numberOfDecimalDigits() >= $digits) {
            return self::$ln10->truncateToScale($digits)->getValue(NumberBase::Ten);
        }

        $ln10 = Numbers::make(Numbers::IMMUTABLE, 10, $digits+2)->setMode(CalcMode::Precision);
        $ln10 = $ln10->ln();

        self::$ln10 = $ln10;

        return $ln10->truncateToScale($digits)->getValue(NumberBase::Ten);

    }

    /**
     * This function is a special case of the ln() function where x can be represented by (n + 1)/n, where n is an
     * integer. This particular special case converges extremely rapidly. For ln(2), n = 1.
     *
     * @param int $digits
     * @return string
     */
    public static function makeLn2(int $digits): string
    {

        if (isset(self::$ln2) && self::$ln2->numberOfDecimalDigits() >= $digits) {
            return self::$ln2->truncateToScale($digits)->getValue(NumberBase::Ten);
        }

        $twoThirds = Numbers::make(Numbers::IMMUTABLE, str_pad('0.', $digits+3, '6'));
        $nine = Numbers::make(Numbers::IMMUTABLE, 9, $digits+3);
        $ln2 = self::_makeLnSpecial($digits, $nine, $twoThirds);

        self::$ln2 = $ln2;

        return $ln2->getValue(NumberBase::Ten);

    }

    /**
     * This function is a special case of the ln() function where x can be represented by (n + 1)/n, where n is an
     * integer. This particular special case converges extremely rapidly. For ln(1.1), n = 10.
     *
     * @param int $digits
     * @return string
     */
    public static function makeLn1p1(int $digits): string
    {

        if (isset(self::$ln1p1) && self::$ln1p1->numberOfDecimalDigits() >= $digits) {
            return self::$ln1p1->truncateToScale($digits)->getValue(NumberBase::Ten);
        }

        $two = Numbers::make(Numbers::IMMUTABLE, 2, $digits+3);
        $twentyOne = Numbers::make(Numbers::IMMUTABLE, 21, $digits+3);
        $fourFortyOne = Numbers::make(Numbers::IMMUTABLE, 441, $digits+3);
        $twoDivTwentyOne = $two->divide($twentyOne);
        $ln1p1 = self::_makeLnSpecial($digits, $fourFortyOne, $twoDivTwentyOne);

        self::$ln1p1 = $ln1p1;

        return $ln1p1->getValue(NumberBase::Ten);

    }

    /**
     * @param int $digits
     * @param ImmutableDecimal $innerNum
     * @param ImmutableDecimal $outerNum
     * @return ImmutableDecimal
     */
    private static function _makeLnSpecial(int $digits, ImmutableDecimal $innerNum, ImmutableDecimal $outerNum): ImmutableDecimal
    {
        $one = Numbers::makeOne($digits+3);
        $two = Numbers::make(Numbers::IMMUTABLE, 2, $digits+3);
        $sum = Numbers::makeZero($digits+3);
        $k = 0;

        do {

            $diff = $one->divide($one->add($two->multiply($k))->multiply($innerNum->pow($k)), $digits+3)->truncate($digits+2);

            $sum = $sum->add($diff);

            $k++;

        } while (!$diff->isEqual(0));

        $lnSpecial = $outerNum->multiply($sum);
        return $lnSpecial->truncateToScale($digits);
    }

}