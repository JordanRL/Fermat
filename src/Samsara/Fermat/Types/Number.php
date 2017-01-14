<?php

namespace Samsara\Fermat\Types;

use Ds\Hashable;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Riimu\Kit\BaseConversion\BaseConverter;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\FractionInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Values\ImmutableNumber;

abstract class Number implements Hashable
{
    const INFINITY = 'INF';
    const NEG_INFINITY = '-INF';
    
    protected $value;

    protected $precision;

    protected $base;

    protected $extensions = true;

    public function __construct($value, $precision = 10, $base = 10)
    {
        $this->base = $base;
        $this->value = (string)$value;
        
        if (!is_null($precision)) {
            if ($precision > 100) {
                throw new IntegrityConstraint(
                    'Precision cannot be larger than 100',
                    'Use a precision of 100 or less',
                    'Due to the fact that 100 digit constants are used internally, precision of any number cannot be calculated beyond 100 digits'
                );
            }

            $this->precision = $precision;
        } else {
            $this->precision = (strlen($this->getDecimalPart()) > 10) ? strlen($this->getDecimalPart()) : 10;
        }
    }

    public function getValue()
    {
        if ($this->getRadixPos()) {
            return rtrim(rtrim($this->value, '0'), '.');
        } else {
            return $this->value;
        }
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getPrecision()
    {
        return $this->precision;
    }

    public function setExtensions(bool $flag)
    {

        $this->extensions = $flag;

        return $this;

    }

    public function add($num)
    {
        if (is_object($num) && method_exists($num, 'asDecimal')) {
            $num = $num->asDecimal($this->getPrecision());
        } else {
            $num = Numbers::makeOrDont($this, $num, $this->getPrecision());
        }

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = ArithmeticProvider::add($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    public function subtract($num)
    {
        if (is_object($num) && method_exists($num, 'asDecimal')) {
            $num = $num->asDecimal($this->getPrecision());
        } else {
            $num = Numbers::makeOrDont($this, $num, $this->getPrecision());
        }

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = ArithmeticProvider::subtract($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    /**
     * @param $num
     *
     * @return DecimalInterface|NumberInterface
     */
    public function multiply($num)
    {
        if (is_object($num) && method_exists($num, 'asDecimal')) {
            $num = $num->asDecimal($this->getPrecision());
        } else {
            $num = Numbers::makeOrDont($this, $num, $this->getPrecision());
        }

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = ArithmeticProvider::multiply($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    /**
     * Note about precision: it uses the smaller of the two precisions (significant figures).
     *
     * @param $num
     * @param $precision
     * @return DecimalInterface|NumberInterface
     */
    public function divide($num, $precision = null)
    {
        if (is_object($num) && method_exists($num, 'asDecimal')) {
            $num = $num->asDecimal($this->getPrecision());
        } else {
            $num = Numbers::makeOrDont($this, $num, $this->getPrecision());
        }

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        if (!is_int($precision)) {
            $precision = ($this->getPrecision() > $num->getPrecision()) ? $num->getPrecision() : $this->getPrecision();
        }

        $value = ArithmeticProvider::divide($this->getValue(), $num->getValue(), $precision);

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    public function factorial()
    {
        $oldBase = $this->convertForModification();

        if ($this->isLessThan(1)) {
            if ($this->isEqual(0)) {
                return $this->setValue(1);
            }
            throw new IncompatibleObjectState('Cannot make a factorial with a number less than 1 (other than zero)');
        }

        if ($this->getDecimalPart() != 0) {
            throw new IncompatibleObjectState('Can only perform a factorial on a whole number');
        }

        if (function_exists('gmp_fact') && function_exists('gmp_strval') && $this->extensions) {
            return $this->setValue(gmp_strval(gmp_fact($this->getValue())))->convertFromModification($oldBase);
        }

        $curVal = $this->getValue();
        $calcVal = Numbers::make(Numbers::IMMUTABLE, 1);

        for ($i = 1;$i <= $curVal;$i++) {
            $calcVal = $calcVal->multiply($i);
        }

        return $this->setValue($calcVal->getValue())->convertFromModification($oldBase);

    }

    public function doubleFactorial()
    {
        if ($this->isWhole() && $this->isLessThanOrEqualTo(1)) {
            return $this->setValue('1');
        } elseif (!$this->isWhole()) {
            throw new IncompatibleObjectState('Can only perform a double factorial on a whole number');
        }

        $oldBase = $this->convertForModification();

        $num = Numbers::make(Numbers::MUTABLE, $this->getValue(), $this->getPrecision(), $this->getBase());

        $newVal = Numbers::makeOne();

        $continue = true;

        while ($continue) {
            $newVal = $newVal->multiply($num->getValue());
            $num->subtract(2);

            if ($num->isLessThanOrEqualTo(1)) {
                $continue = false;
            }
        }

        return $this->setValue($newVal->getValue())->convertFromModification($oldBase);

    }

    public function semiFactorial()
    {
        return $this->doubleFactorial();
    }

    /**
     * @param $num
     *
     * @return DecimalInterface|NumberInterface
     */
    public function pow($num)
    {
        if (is_object($num) && method_exists($num, 'asDecimal')) {
            $num = $num->asDecimal($this->getPrecision());
        } else {
            $num = Numbers::makeOrDont($this, $num, $this->getPrecision());
        }

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = ArithmeticProvider::pow($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    /**
     * @param int $precision The number of digits which should be accurate
     * @param bool $round Whether or not to round to the $precision value. If true, will round. If false, will truncate.
     *
     * @return DecimalInterface|NumberInterface
     */
    public function ln($precision = 10, $round = true)
    {
        $oldBase = $this->convertForModification();

        if ($this->isGreaterThanOrEqualTo(PHP_INT_MIN) && $this->isLessThanOrEqualTo(PHP_INT_MAX) && $precision <= 10) {
            return $this->setValue(log($this->getValue()))->convertFromModification($oldBase);
        }

        $internalPrecision = ($precision+1 > strlen($this->value)) ? $precision+1 : strlen($this->value);

        $this->precision = $internalPrecision;

        $ePow = 0;
        $eDiv = 1;
        $e = Numbers::makeE();

        if ($this->isGreaterThan(10)) {
            $continue = true;
            do {
                $prevDiv = $eDiv;
                $eDiv = $e->pow($ePow);

                if ($eDiv->isGreaterThan($this)) {
                    $continue = false;
                } else {
                    $ePow++;
                }
            } while ($continue);

            $ePow--;
            $eDiv = $prevDiv;
        }

        $adjustedNum = $this->divide($eDiv, $internalPrecision);

        /** @var ImmutableNumber $y */
        $y = Numbers::makeOne($internalPrecision);
        $y = $y->multiply($adjustedNum->subtract(1))->divide($adjustedNum->add(1), $internalPrecision);

        $answer = SeriesProvider::genericTwoPartSeries(
            function($term) use ($y, $internalPrecision) {
                $two = Numbers::make(Numbers::IMMUTABLE, 2, $internalPrecision);
                $odd = SequenceProvider::nthOddNumber($term);

                return $two->divide($odd, $internalPrecision);
            },
            function($term) use ($y) {
                return $y;
            },
            function($term) {
                return SequenceProvider::nthOddNumber($term);
            },
            0,
            $internalPrecision
        );

        $answer = $answer->add($ePow);

        if ($round) {
            $answer = $answer->roundToPrecision($precision);
        } else {
            $answer = $answer->truncateToPrecision($precision);
        }

        return $this->setValue($answer)->convertFromModification($oldBase);
    }

    public function log10($precision = 10, $round = true)
    {
        $log10 = Numbers::makeNaturalLog10();

        $value = $this->ln($precision+1)->divide($log10, $precision+1);

        if ($round) {
            $value = $value->roundToPrecision($precision);
        } else {
            $value = $value->truncateToPrecision($precision);
        }

        return $this->setValue($value);
    }

    public function sqrt()
    {
        $oldBase = $this->convertForModification();

        $value = ArithmeticProvider::squareRoot($this->getValue(), $this->getPrecision());

        $this->convertFromModification($oldBase);

        return $this->setValue($value);
    }
    
    public function sin($precision = null, $round = true)
    {
        if ($this->isEqual(0)) {
            return $this;
        }

        $oldBase = $this->convertForModification();

        $precision = $precision ?? $this->getPrecision();

        if ($precision > 99) {
            $precision = 99;
        }

        $twoPi = Numbers::make2Pi();

        $modulo = $this->continuousModulo($twoPi);

        $answer = SeriesProvider::maclaurinSeries(
            $modulo,
            function ($n) {
                $negOne = Numbers::make(Numbers::IMMUTABLE, -1, 100);

                return $negOne->pow($n);
            },
            function ($n) {
                return SequenceProvider::nthOddNumber($n);
            },
            function ($n) {
                return SequenceProvider::nthOddNumber($n)->factorial();
            },
            0,
            $precision+1
        );

        if ($round) {
            return $this->setValue($answer->getValue())->roundToPrecision($precision)->convertFromModification($oldBase);
        } else {
            return $this->setValue($answer->getValue())->truncateToPrecision($precision)->convertFromModification($oldBase);
        }
    }
    
    public function cos($precision = null, $round = true)
    {
        if ($this->isEqual(0)) {
            return $this->setValue(1);
        }

        $oldBase = $this->convertForModification();

        $precision = $precision ?? $this->getPrecision();

        if ($precision > 99) {
            $precision = 99;
        }

        $twoPi = Numbers::make2Pi();

        $modulo = $this->continuousModulo($twoPi);

        $answer = SeriesProvider::maclaurinSeries(
            $modulo,
            function ($n) {
                return SequenceProvider::nthPowerNegativeOne($n);
            },
            function ($n) {
                return SequenceProvider::nthEvenNumber($n);
            },
            function ($n) {
                return SequenceProvider::nthEvenNumber($n)->factorial();
            },
            0,
            $precision+1
        );

        if ($round) {
            return $this->setValue($answer->getValue())->roundToPrecision($precision)->convertFromModification($oldBase);
        } else {
            return $this->setValue($answer->getValue())->truncateToPrecision($precision)->convertFromModification($oldBase);
        }
    }

    public function tan($precision = null, $round = true)
    {
        $oldBase = $this->convertForModification();

        $precision = $precision ?? $this->getPrecision();

        if ($precision > 99) {
            $precision = 99;
        }

        $pi = Numbers::makePi();
        $piDivTwo = Numbers::makePi()->divide(2);
        $piDivFour = Numbers::makePi()->divide(4);
        $piDivEight = Numbers::makePi()->divide(8);
        $threePiDivTwo = Numbers::makePi()->multiply(3)->divide(2);
        $twoPi = Numbers::make2Pi();
        $two = Numbers::make(Numbers::IMMUTABLE, 2, 100);
        $one = Numbers::make(Numbers::IMMUTABLE, 1, 100);

        $exitModulo = $this->continuousModulo($pi);

        if ($exitModulo->truncate(99)->isEqual(0)) {
            return $this->setValue(0)->convertFromModification($oldBase);
        }

        $modulo = $this->continuousModulo($twoPi);

        if (
            $modulo->truncate(99)->isEqual($piDivTwo->truncate(99)) ||
            ($this->isNegative() && $modulo->subtract($pi)->abs()->truncate(99)->isEqual($piDivTwo->truncate(99)))
        ) {
            return $this->setValue(static::INFINITY);
        }

        if (
            $modulo->subtract($pi)->truncate(99)->isEqual($piDivTwo->truncate(99)) ||
            ($this->isNegative() && $modulo->truncate(99)->abs()->isEqual($piDivTwo->truncate(99)))
        ) {
            return $this->setValue(static::NEG_INFINITY);
        }

        if ($modulo->abs()->isGreaterThan($piDivTwo)) {
            if ($this->isNegative()) {
                if ($modulo->abs()->isGreaterThan($threePiDivTwo)) {
                    $modulo = $modulo->add($twoPi);
                } else {
                    $modulo = $modulo->add($pi);
                }
            } else {
                if ($modulo->isGreaterThan($threePiDivTwo)) {
                    $modulo = $modulo->subtract($twoPi);
                } else {
                    $modulo = $modulo->subtract($pi);
                }
            }
        }

        $reciprocal = false;

        if ($modulo->abs()->isGreaterThan($piDivFour)) {
            $modulo = $piDivTwo->subtract($modulo);
            $reciprocal = true;
        }

        if ($modulo->abs()->isGreaterThan($piDivEight)) {
            /** @var ImmutableNumber $halfModTan */
            $halfModTan = $modulo->divide(2)->tan($precision+1, false);
            $answer = $two->multiply($halfModTan)->divide($one->subtract($halfModTan->pow(2)));
        } else {
            $answer = SeriesProvider::maclaurinSeries(
                $modulo,
                function ($n) {
                    $nthOddNumber = SequenceProvider::nthOddNumber($n);

                    return SequenceProvider::nthEulerZigzag($nthOddNumber);
                },
                function ($n) {

                    return SequenceProvider::nthOddNumber($n);
                },
                function ($n) {
                    return SequenceProvider::nthOddNumber($n)->factorial();
                },
                0,
                $precision + 1
            );
        }

        if ($reciprocal) {
            $answer = $one->divide($answer);
        }

        if ($round) {
            return $this->setValue($answer->getValue())->roundToPrecision($precision)->convertFromModification($oldBase);
        } else {
            return $this->setValue($answer->getValue())->truncateToPrecision($precision)->convertFromModification($oldBase);
        }

    }

    public function getLeastCommonMultiple($num)
    {

        /** @var ImmutableNumber $num */
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $num);

        if (!$this->isInt() || !$num->isInt()) {
            throw new IntegrityConstraint(
                'Both numbers must be integers',
                'Ensure that both numbers are integers before getting the LCM',
                'Both numbers being considered must be integers in order to calculate a Least Common Multiple'
            );
        }

        return $this->multiply($num)->abs()->divide($this->getGreatestCommonDivisor($num));

    }

    public function getGreatestCommonDivisor($num)
    {
        /** @var ImmutableNumber $num */
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $num)->abs();
        /** @var ImmutableNumber $thisNum */
        $thisNum = Numbers::makeOrDont(Numbers::IMMUTABLE, $this)->abs();

        if (!$this->isInt() || !$num->isInt()) {
            throw new IntegrityConstraint(
                'Both numbers must be integers',
                'Ensure that both numbers are integers before getting the GCD',
                'Both numbers being considered must be integers in order to calculate a Greatest Common Divisor'
            );
        }

        if (function_exists('gmp_gcd') && function_exists('gmp_strval') && $this->extensions) {
            $val = gmp_strval(gmp_gcd($thisNum->getValue(), $num->getValue()));

            return Numbers::make(Numbers::IMMUTABLE, $val);
        } else {

            if ($thisNum->isLessThan($num)) {
                $greater = $num;
                $lesser = $thisNum;
            } else {
                $greater = $thisNum;
                $lesser = $num;
            }

            /** @var NumberInterface $remainder */
            $remainder = $greater->modulo($lesser);

            while ($remainder->isGreaterThan(0)) {
                $greater = $lesser;
                $lesser = $remainder;
                $remainder = $greater->modulo($lesser);
            }

            return $lesser;
        }
    }

    public function convertForModification()
    {
        if ($this->getBase() == 10) {
            return false;
        } else {
            $oldBase = $this->getBase();
            $this->convertToBase(10);
            return $oldBase;
        }
    }

    public function convertFromModification($oldBase)
    {
        if ($oldBase !== false) {
            return $this->convertToBase($oldBase);
        }

        return $this;
    }

    public function abs()
    {
        $newValue = $this->absValue();

        return $this->setValue($newValue);
    }

    public function absValue(): string
    {
        if ($this->isNegative()) {
            return substr($this->getValue(), 1);
        } else {
            return $this->getValue();
        }
    }

    public function isNegative(): bool
    {
        if ($this->isEqual(0)) {
            return false;
        }

        if (strpos($this->getValue(), '-') === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isPositive(): bool
    {
        if ($this->isEqual(0)) {
            return false;
        }

        return !$this->isNegative();
    }

    public function isNatural(): bool
    {
        return $this->isInt();
    }

    public function isWhole(): bool
    {
        return $this->isInt();
    }

    public function isInt(): bool
    {
        if ($this->getDecimalPart() === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is a PHP implementation of the function described at: http://stackoverflow.com/a/1801446
     *
     * It is relatively simple to understand, which is why it was chosen as the implementation. However in the future,
     * an implementation that is based on ECPP (such as the Goldwasser implementation) may be employed to improve speed.
     *
     * @return bool
     */
    public function isPrime(): bool
    {
        if (!$this->isInt()) {
            return false;
        }

        if ($this->isEqual(2)) {
            return true;
        } elseif ($this->isEqual(3)) {
            return true;
        } elseif ($this->modulo(2)->isEqual(0)) {
            return false;
        } elseif ($this->modulo(3)->isEqual(0)) {
            return false;
        }

        $i = Numbers::make(Numbers::IMMUTABLE, 5);
        $w = Numbers::make(Numbers::IMMUTABLE, 2);
        $k = Numbers::make(Numbers::IMMUTABLE, 6);

        while ($i->pow(2)->isLessThanOrEqualTo($this)) {
            if ($this->modulo($i)->isEqual(0)) {
                return false;
            }

            $i = $i->add($w);
            $w = $k->subtract($w);
        }

        return true;
    }

    public function round($decimals = 0)
    {
        $fractional = $this->getDecimalPart();
        $whole = $this->getWholePart();

        $fractionalArr = str_split($fractional);

        if (!isset($fractionalArr[$decimals])) {
            return $this;
        } else {
            if ($decimals == 0) {
                if ($fractionalArr[$decimals] >= 5) {
                    return $this->setValue($whole)->add(1);
                } else {
                    return $this->setValue($whole);
                }
            } else {
                if ($fractionalArr[$decimals] >= 5) {
                    $fractionalArr = $this->reduceDecimals($fractionalArr, $decimals-1, 1);
                }

                if (is_null($fractionalArr)) {
                    return $this->setValue($whole)->add(1);
                }

                $rounded = $whole.'.';

                for ($i = 0;$i < $decimals;$i++) {
                    $rounded .= $fractionalArr[$i];
                }

                return $this->setValue($rounded);
            }
        }
    }

    public function truncate($decimals = 0)
    {
        $fractional = $this->getDecimalPart();
        $whole = $this->getWholePart();

        if ($decimals == 0) {
            return $this->setValue($whole);
        } else {
            $truncated = $whole.'.';

            if ($decimals > strlen($fractional)) {
                $fractional = str_pad($fractional, $decimals, '0');
            } else {
                $fractional = substr($fractional, 0, $decimals);
            }

            $truncated .= $fractional;

            return $this->setValue($truncated);
        }
    }

    public function roundToPrecision($precision)
    {

        $this->precision = $precision;

        return $this->round($precision);

    }

    public function truncateToPrecision($precision)
    {

        $this->precision = $precision;

        return $this->truncate($precision);

    }

    public function ceil()
    {
        $fractional = $this->getDecimalPart();
        $whole = $this->getWholePart();

        if ($fractional > 0) {
            $whole = ArithmeticProvider::add($whole, 1);
        }

        return $this->setValue($whole);
    }

    public function floor()
    {
        return $this->setValue($this->getWholePart());
    }

    /**
     * @param NumberInterface|int|float|string $value
     * @return int
     */
    public function compare($value): int
    {
        $value = Numbers::makeOrDont($this, $value, $this->getPrecision());

        $thisBase = $this->convertForModification();
        $thatBase = $value->convertForModification();

        $thisValue = $this->getValue();
        $thatValue = $value->getValue();

        $scale = ($this->getPrecision() < $value->getPrecision()) ? $this->getPrecision() : $value->getPrecision();

        $comparison = ArithmeticProvider::compare($thisValue, $thatValue, $scale);

        $this->convertFromModification($thisBase);
        $value->convertFromModification($thatBase);

        return $comparison;
    }
    
    public function isEqual($value): bool
    {
        $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());
        
        if ($this->compare($value) === 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isGreaterThan($value): bool
    {
        $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());
        
        if ($this->compare($value) === 1) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isGreaterThanOrEqualTo($value): bool
    {
        $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());
        
        if ($this->compare($value) > -1) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isLessThan($value): bool {
        $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());
        
        if ($this->compare($value) === -1) {
            return true;
        } else {
            return false;
        }
    }

    public function isLessThanOrEqualTo($value): bool
    {
        $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());

        if ($this->compare($value) < 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $base
     * @return NumberInterface
     */
    public function convertToBase($base)
    {
        $converter = new BaseConverter($this->getBase(), $base);

        $converter->setPrecision($this->getPrecision());

        $value = $converter->convert($this->getValue());

        $this->base = $base;

        return $this->setValue($value);
    }
    
    public function numberOfLeadingZeros()
    {
        $fractional = $this->getDecimalPart();
        
        $total = strlen($fractional);
        $fractional = ltrim($fractional, '0');
        
        return $total-strlen($fractional);
    }

    public function asInt()
    {

        if ($this->isGreaterThan(PHP_INT_MAX) || $this->isLessThan(PHP_INT_MIN)) {
            throw new IncompatibleObjectState('Cannot export number as integer because it is out of range');
        }

        return intval($this->getValue());

    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function hash()
    {
        return get_class($this).$this->getValue();
    }

    public function equals($object): bool
    {
        $reflection = new \ReflectionClass($object);

        if ($reflection->implementsInterface(NumberInterface::class) || is_numeric($object)) {
            return $this->isEqual($object);
        } else {
            return false;
        }
    }

    protected function getRadixPos()
    {
        return strpos($this->value, '.');
    }

    protected function reduceDecimals(array $decimalArray, $pos, $add)
    {

        if ($add == 1) {
            if ($decimalArray[$pos] == 9) {
                $decimalArray[$pos] = 0;

                if ($pos == 0) {
                    return null;
                } else {
                    return $this->reduceDecimals($decimalArray, $pos-1, $add);
                }
            } else {
                $decimalArray[$pos] += 1;
            }
        }

        return $decimalArray;

    }

    protected function getDecimalPart()
    {
        $radix = $this->getRadixPos();
        if ($radix !== false) {
            return substr($this->getValue(), $radix+1);
        } else {
            return 0;
        }
    }

    protected function getWholePart()
    {
        $radix = $this->getRadixPos();
        if ($radix !== false) {
            return substr($this->getValue(), 0, $radix);
        } else {
            return $this->getValue();
        }
    }

    /**
     * @param $mod
     *
     * @return NumberInterface|DecimalInterface
     */
    abstract public function modulo($mod);

    /**
     * @param $mod
     *
     * @return NumberInterface|DecimalInterface
     */
    abstract public function continuousModulo($mod);


    /**
     * @param $value
     *
     * @return NumberInterface|DecimalInterface
     */
    abstract protected function setValue($value);

}