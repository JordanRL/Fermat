<?php


namespace Samsara\Fermat\Provider;


use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\CalcMode;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 *
 */
class ConstantProvider
{

    private static DecimalInterface $pi;
    private static DecimalInterface $e;
    private static DecimalInterface $ln10;
    private static DecimalInterface $ln2;

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

        $internalScale = $digits + 10;

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
            $term = $M->multiply($L)->divide($X);

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
     * The lnScale() implementation is very efficient, so this is probably our best bet for computing more digits of
     * ln(10) to provide.
     *
     * @param int $digits
     * @return string
     * @throws IntegrityConstraint
     */
    public static function makeLn2(int $digits): string
    {

        if (isset(self::$ln2) && self::$ln2->numberOfDecimalDigits() >= $digits) {
            return self::$ln2->truncateToScale($digits)->getValue(NumberBase::Ten);
        }

        $ln2 = Numbers::make(Numbers::IMMUTABLE, 2, $digits+2)->setMode(CalcMode::Precision);
        $ln2 = $ln2->ln();

        self::$ln2 = $ln2;

        return $ln2->truncateToScale($digits)->getValue(NumberBase::Ten);

    }

}