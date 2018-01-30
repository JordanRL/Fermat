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
use Samsara\Fermat\Provider\StatsProvider;
use Samsara\Fermat\Types\Base\DecimalInterface;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Types\Traits\ArithmeticTrait;
use Samsara\Fermat\Types\Traits\ComparisonTrait;
use Samsara\Fermat\Types\Traits\IntegerMathTrait;
use Samsara\Fermat\Types\Traits\InverseTrigonometryTrait;
use Samsara\Fermat\Types\Traits\TrigonometryTrait;
use Samsara\Fermat\Values\ImmutableNumber;

abstract class Number implements Hashable
{
    const INFINITY = 'INF';
    const NEG_INFINITY = '-INF';
    
    protected $value;

    protected $precision;

    protected $base;

    protected $extensions = true;

    use ArithmeticTrait;
    use IntegerMathTrait;
    use ComparisonTrait;
    use TrigonometryTrait;
    use InverseTrigonometryTrait;

    public function __construct($value, $precision = null, $base = 10)
    {
        $this->base = $base;
        $this->value = (string)$value;
        
        if (!is_null($precision)) {
            if ($precision > 2147483646) {
                throw new IntegrityConstraint(
                    'Precision cannot be larger than 2147483646',
                    'Use a precision of 2147483646 or less',
                    'Precision of any number cannot be calculated beyond 2147483646 digits'
                );
            }

            $this->precision = ($precision > strlen($this->getDecimalPart())) ? $precision : strlen($this->getDecimalPart());
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

        $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

        if ($num->isWhole()) {
            $value = ArithmeticProvider::pow($this->getValue(), $num->getValue(), $internalPrecision);
        } else {
            $exponent = $num->multiply($this->ln($internalPrecision));
            $value = $exponent->exp();
        }

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value)->truncateToPrecision($internalPrecision);
    }

    public function exp()
    {
        $oldBase = $this->convertForModification();

        $value = SeriesProvider::maclaurinSeries(
            Numbers::makeOrDont(Numbers::IMMUTABLE, $this),
            function() {
                return Numbers::makeOne();
            },
            function($n) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

                return $n;
            },
            function($n) {
                $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

                return $n->factorial();
            },
            0,
            $this->getPrecision()
        );

        return $this->setValue($value)->convertFromModification($oldBase);
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
        if ($this->getDecimalPart() == 0) {
            return true;
        } else {
            return false;
        }
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
            $whole = ArithmeticProvider::add($whole, 1, $this->getPrecision());
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

        $precision = ($this->getPrecision() < $value->getPrecision()) ? $this->getPrecision() : $value->getPrecision();

        $comparison = ArithmeticProvider::compare($thisValue, $thatValue, $precision);

        $this->convertFromModification($thisBase);
        $value->convertFromModification($thatBase);

        return $comparison;
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