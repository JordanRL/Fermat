<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\BCProvider;
use Riimu\Kit\BaseConversion\BaseConverter;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\NumberInterface;

abstract class Number
{
    const INFINITY = 'INF';
    const NEG_INFINITY = '-INF';
    
    protected $value;

    protected $precision;

    protected $base;

    public function __construct($value, $precision = null, $base = 10)
    {
        $this->base = $base;
        $this->value = (string)$value;
        
        if (!is_null($precision)) {
            $this->precision = $precision;
        } else {
            $this->precision = strlen($this->getDecimalPart());
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getPrecision()
    {
        return $this->precision;
    }

    public function add($num)
    {
        $num = Numbers::makeOrDont($this, $num, $this->getPrecision());

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = BCProvider::add($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    public function subtract($num)
    {
        $num = Numbers::makeOrDont($this, $num, $this->getPrecision());

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = BCProvider::subtract($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    public function multiply($num)
    {
        $num = Numbers::makeOrDont($this, $num, $this->getPrecision());

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = BCProvider::multiply($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    /**
     * Note about precision: it uses the smaller of the two precisions (significant figures).
     *
     * @param $num
     * @return mixed
     */
    public function divide($num)
    {
        $num = Numbers::makeOrDont($this, $num, $this->getPrecision());

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $precision = ($this->getPrecision() > $num->getPrecision()) ? $num->getPrecision() : $this->getPrecision();

        $value = BCProvider::divide($this->getValue(), $num->getValue(), $precision);

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    public function factorial()
    {
        $oldBase = $this->convertForModification();

        if ($this->lessThan(1)) {
            if ($this->equals(0)) {
                return $this->setValue(1);
            }
            throw new \Exception('Cannot make a factorial with a number less than 1 (other than zero)');
        }

        if ($this->getDecimalPart() !== 0) {
            throw new \Exception('Can only perform a factorial on a whole number');
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
        $oldBase = $this->convertForModification();

        $val = Numbers::make(Numbers::IMMUTABLE, $this->getValue());

        if ($val->modulo(2)->equals(1)) {
            $m = Numbers::make(Numbers::IMMUTABLE, $val->add(1)->divide(2));
            $term = function ($n) {
                return Numbers::make(Numbers::IMMUTABLE, 2)->multiply($n);
            };
        } else {
            $m = Numbers::make(Numbers::IMMUTABLE, $val->divide(2));
            $term = function ($n) {
                return Numbers::make(Numbers::IMMUTABLE, 2)->multiply($n)->subtract(1);
            };
        }

        $newVal = Numbers::makeOne();

        for ($i = 1;$m->greaterThanOrEqualTo($i);$i++) {
            $newVal = $newVal->multiply($term($i));
        }

        return $this->setValue($newVal->getValue())->convertFromModification($oldBase);

    }

    public function semiFactorial()
    {
        return $this->doubleFactorial();
    }

    public function pow($num)
    {
        $num = Numbers::makeOrDont($this, $num, $this->getPrecision());

        $oldBase = $this->convertForModification();
        $numOldBase = $num->convertForModification();

        $value = BCProvider::pow($this->getValue(), $num->getValue());

        $this->convertFromModification($oldBase);
        $num->convertFromModification($numOldBase);

        return $this->setValue($value);
    }

    public function sqrt()
    {
        $oldBase = $this->convertForModification();

        $value = BCProvider::squareRoot($this->getValue(), $this->getPrecision());

        $this->convertFromModification($oldBase);

        return $this->setValue($value);
    }
    
    public function sin($mult = 1, $div = 1, $precision = null, $calc = false)
    {
        if ($this->equals(0)) {
            return $this;
        }

        $oldBase = $this->convertForModification();
        
        if ($this->greaterThanOrEqualTo(PHP_INT_MIN) && $this->lessThanOrEqualTo(PHP_INT_MAX)) {
            return $this->setValue(sin($this->getValue()));
        }
        
        $value = $this->getValue();
        
        $value = BCProvider::multiply($value, $mult);
        $value = BCProvider::divide($value, $div);

        $pi = Numbers::makePi();

        $modulo = Numbers::make(Numbers::IMMUTABLE, $value);
        $modulo = $modulo->continuousModulo($pi->multiply(2));

        if ($calc) {
            return $this->setValue(
                SeriesProvider::maclaurinSeries(
                    Numbers::make(Numbers::IMMUTABLE, $value),
                    function ($n) {
                        $negOne = Numbers::make(Numbers::IMMUTABLE, -1);

                        return $negOne->pow($n);
                    },
                    function ($n) {
                        return SequenceProvider::nthOddNumber($n);
                    },
                    function ($n) {
                        return SequenceProvider::nthOddNumber($n)->factorial();
                    },
                    0,
                    $precision
                )->getValue()
            )->convertFromModification($oldBase);
        } else {
            return $this->setValue(sin($modulo->getValue()));
        }
    }
    
    public function cos($mult = 1, $div = 1, $precision = null, $calc = false)
    {
        if ($this->equals(0)) {
            return $this->setValue(1);
        }

        $oldBase = $this->convertForModification();

        if ($this->greaterThanOrEqualTo(PHP_INT_MIN) && $this->lessThanOrEqualTo(PHP_INT_MAX)) {
            return $this->setValue(cos($this->getValue()));
        }

        $value = $this->getValue();

        $value = BCProvider::multiply($value, $mult);
        $value = BCProvider::divide($value, $div);
        
        $pi = Numbers::makePi();
        
        $modulo = Numbers::make(Numbers::IMMUTABLE, $value);
        $modulo = $modulo->continuousModulo($pi->multiply(2));

        if ($calc) {
            return $this->setValue(
                SeriesProvider::maclaurinSeries(
                    Numbers::make(Numbers::IMMUTABLE, $value),
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
                    $precision
                )->getValue()
            )->convertFromModification($oldBase);
        } else {
            return $this->setValue(cos($modulo->getValue()));
        }
    }

    public function tan($mult = 1, $div = 1, $precision = null, $calc = false)
    {
        $oldBase = $this->convertForModification();

        if ($this->greaterThanOrEqualTo(PHP_INT_MIN) && $this->lessThanOrEqualTo(PHP_INT_MAX)) {
            return $this->setValue(cos($this->getValue()));
        }

        $value = $this->getValue();

        $value = BCProvider::multiply($value, $mult);
        $value = BCProvider::divide($value, $div);

        $pi = Numbers::makePi();

        $modulo = Numbers::make(Numbers::IMMUTABLE, $value);
        $modulo = $modulo->continuousModulo($pi->multiply(2));

        if ($calc) {
            return $this->setValue(
                SeriesProvider::maclaurinSeries(
                    Numbers::make(Numbers::IMMUTABLE, $value),
                    function ($n) {
                        $four = Numbers::make(Numbers::IMMUTABLE, 4);
                        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

                        return SequenceProvider::nthBernoulliNumber($n->multiply(2)->getValue(), -4)->pow($n)->multiply(Numbers::makeOne()->subtract($four->pow($n)));
                    },
                    function ($n) {
                        $n = Numbers::makeOrDont(Numbers::IMMUTABLE, $n);

                        return $n->multiply(2)->subtract(1);
                    },
                    function ($n) {
                        return SequenceProvider::nthEvenNumber($n)->factorial();
                    },
                    1,
                    $precision
                )
            )->convertFromModification($oldBase);
        } else {
            return $this->setValue(tan($modulo->getValue()));
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

    public function absValue()
    {
        if ($this->isNegative()) {
            return substr($this->getValue(), 1);
        } else {
            return $this->getValue();
        }
    }

    public function isNegative()
    {
        if ($this->equals(0)) {
            return false;
        }

        if (strpos($this->getValue(), '-') === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isPositive()
    {
        if ($this->equals(0)) {
            return false;
        }

        return !$this->isNegative();
    }

    public function isNatural()
    {
        return $this->isInt();
    }

    public function isWhole()
    {
        return $this->isInt();
    }

    public function isInt()
    {
        if ($this->getDecimalPart() === 0) {
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

        if ($fractionalArr[$decimals] >= 5) {
            if ($decimals == 0) {
                $rounded = BCProvider::add($whole, 1);
            } else {
                $rounded = $whole.'.';
                for ($i = 0; $i < $decimals; $i++) {
                    if (($i+1) == $decimals) {
                        $rounded .= ($fractionalArr[$i]+1);
                    } else {
                        $rounded .= $fractionalArr[$i];
                    }
                }
            }
        } else {
            if ($decimals == 0) {
                $rounded = $whole;
            } else {
                $rounded = $whole.'.';
                for ($i = 0; $i < $decimals; $i++) {
                    $rounded .= $fractionalArr[$i];
                }
            }
        }

        return $this->setValue($rounded);
    }

    public function roundToPrecision($precision)
    {

        $this->precision = $precision;

        return $this->round($precision);

    }

    public function ceil()
    {
        $fractional = $this->getDecimalPart();
        $whole = $this->getWholePart();

        if ($fractional > 0) {
            $whole = BCProvider::add($whole, 1);
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
    public function compare($value)
    {
        $value = Numbers::makeOrDont($this, $value, $this->getPrecision());

        $thisBase = $this->convertForModification();
        $thatBase = $value->convertForModification();

        $thisValue = $this->getValue();
        $thatValue = $value->getValue();

        $scale = ($this->getPrecision() < $value->getPrecision()) ? $this->getPrecision() : $value->getPrecision();

        $comparison = BCProvider::compare($thisValue, $thatValue, $scale);

        $this->convertFromModification($thisBase);
        $value->convertFromModification($thatBase);

        return $comparison;
    }
    
    public function equals($value)
    {
        $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());
        
        if ($this->compare($value) === 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function greaterThan($value)
    {
        $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());
        
        if ($this->compare($value) === 1) {
            return true;
        } else {
            return false;
        }
    }
    
    public function greaterThanOrEqualTo($value)
    {
        $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());
        
        if ($this->compare($value) > -1) {
            return true;
        } else {
            return false;
        }
    }
    
    public function lessThan($value) {
        $value = Numbers::makeOrDont(Numbers::IMMUTABLE, $value, $this->getPrecision());
        
        if ($this->compare($value) === -1) {
            return true;
        } else {
            return false;
        }
    }

    public function lessThanOrEqualTo($value)
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

        if ($this->greaterThan(PHP_INT_MAX) || $this->lessThan(PHP_INT_MIN)) {
            throw new \Exception('Cannot export number as integer because it is out of range');
        }

        return intval($this->getValue());

    }

    public function __toString()
    {
        return $this->getValue();
    }

    protected function getRadixPos()
    {
        return strpos($this->getValue(), '.');
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
     * @param $value
     *
     * @return NumberInterface
     */
    abstract protected function setValue($value);

}