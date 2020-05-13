<?php


namespace Samsara\Fermat\Types\Traits;

use ReflectionException;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\ComplexNumbers;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticPrecisionTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticSelectionTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticNativeTrait;
use Samsara\Fermat\Values\ImmutableComplexNumber;
use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Values\ImmutableFraction;

trait ArithmeticSimpleTrait
{

    use ArithmeticSelectionTrait;
    use ArithmeticPrecisionTrait;
    use ArithmeticNativeTrait;

    public function add($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 0);

        if ($num->isComplex()) {
            return $num->add($this);
        }

        if ($num->isEqual(0)) {
            return $this;
        }

        if ($this->isReal() xor $num->isReal()) {
            $newRealPart = $thisRealPart->add($thatRealPart);
            $newImaginaryPart = $thisImaginaryPart->add($thatImaginaryPart);

            if ($newImaginaryPart->isEqual(0)) {
                return $this->setValue($newRealPart->getValue());
            }

            if ($newRealPart->isEqual(0)) {
                return $this->setValue($newImaginaryPart->getValue());
            }

            return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
        }

        if ($this instanceof FractionInterface) {
            if ($this->getDenominator()->isEqual($num->getDenominator())) {
                $finalDenominator = $this->getDenominator();
                $finalNumerator = $this->getNumerator()->add($num->getNumerator());
            } else {
                $finalDenominator = $this->getSmallestCommonDenominator($num);

                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($num, $finalDenominator);

                /** @var ImmutableDecimal $finalNumerator */
                $finalNumerator = $thisNumerator->add($thatNumerator);
            }

            return $this->setValue(
                $finalNumerator,
                $finalDenominator
            );
        }

        $value = $this->addSelector($num);

        if ($this->isImaginary()) {
            $value .= 'i';
        }

        return $this->setValue($value);
    }

    public function subtract($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 0);

        if ($num->isComplex()) {
            return $num->multiply(-1)->add($this);
        }

        if ($num->isEqual(0)) {
            return $this;
        }

        if ($this->isReal() xor $num->isReal()) {
            $newRealPart = $thisRealPart->subtract($thatRealPart);
            $newImaginaryPart = $thisImaginaryPart->subtract($thatImaginaryPart);

            if ($newImaginaryPart->isEqual(0)) {
                return $this->setValue($newRealPart->getValue());
            }

            if ($newRealPart->isEqual(0)) {
                return $this->setValue($newImaginaryPart->getValue());
            }

            return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
        }

        if ($this instanceof FractionInterface) {
            if ($this->getDenominator()->isEqual($num->getDenominator())) {
                $finalDenominator = $this->getDenominator();
                $finalNumerator = $this->getNumerator()->subtract($num->getNumerator());
            } else {
                $finalDenominator = $this->getSmallestCommonDenominator($num);

                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($num, $finalDenominator);

                $finalNumerator = $thisNumerator->subtract($thatNumerator);
            }

            return $this->setValue(
                $finalNumerator,
                $finalDenominator
            );
        }

        $value = $this->subtractSelector($num);

        if ($this->isImaginary()) {
            $value .= 'i';
        }

        return $this->setValue($value);
    }

    public function multiply($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 1);

        if ($num->isComplex()) {
            return $num->multiply($this);
        }

        if ($num->isEqual(1)) {
            return $this;
        }

        /*
        if ($this->isReal() xor $num->isReal()) {
            $newRealPart = $thisRealPart->multiply($thatRealPart);
            $newImaginaryPart = $thisImaginaryPart->multiply($thatImaginaryPart);

            if ($newImaginaryPart->isEqual(0)) {
                return $this->setValue($newRealPart->getValue());
            }

            if ($newRealPart->isEqual(0)) {
                return $this->setValue($newImaginaryPart->getValue());
            }

            return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
        }
        */

        if ($this instanceof FractionInterface) {
            return $this->setValue(
                $this->getNumerator()->multiply($num->getNumerator()),
                $this->getDenominator()->multiply($num->getDenominator())
            );
        }

        $value = $this->multiplySelector($num);

        if ($this->isImaginary() xor $num->isImaginary()) {
            $value .= 'i';
        } elseif ($this->isImaginary() && $num->isImaginary()) {
            $value = Numbers::make(Numbers::IMMUTABLE, $value)->multiply(-1);
        }

        return $this->setValue($value);
    }

    public function divide($num, ?int $precision = null)
    {
        $precision = is_null($precision) ? $this->getPrecision() : $precision;

        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 1);

        if ($num->isComplex()) {
            //return $num->divide($this);
        }

        if ($num->isEqual(1)) {
            return $this;
        }

        if ($this->isReal() xor $num->isReal()) {
            $newRealPart = $thisRealPart->divide($thatRealPart);
            $newImaginaryPart = $thisImaginaryPart->divide($thatImaginaryPart);

            if ($newImaginaryPart->isEqual(0)) {
                return $this->setValue($newRealPart->getValue());
            }

            if ($newRealPart->isEqual(0)) {
                return $this->setValue($newImaginaryPart->getValue());
            }

            return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
        }

        if ($this instanceof FractionInterface) {
            return $this->setValue(
                $this->getNumerator()->multiply($num->getDenominator()),
                $this->getDenominator()->multiply($num->getNumerator())
            );
        }

        $value = $this->divideSelector($num, $precision);

        if ($this->isImaginary()) {
            $value .= 'i';
        }

        return $this->setValue($value);
    }

    public function pow($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 1);

        if ($num->isComplex() || ($this->isReal() xor $num->isReal())) {
            $newRealPart = $thisRealPart->pow($thatRealPart);
            $newImaginaryPart = $thisImaginaryPart->pow($thatImaginaryPart);

            return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
        }

        if ($this instanceof FractionInterface) {
            /** @var ImmutableDecimal $powNumerator */
            $powNumerator = $this->getNumerator()->pow($num);
            /** @var ImmutableDecimal $powDenominator */
            $powDenominator = $this->getDenominator()->pow($num);

            if ($powNumerator->isWhole() && $powDenominator->isWhole()) {
                return $this->setValue($powNumerator, $powDenominator);
            }

            return $powNumerator->divide($powDenominator);
        }

        $value = $this->powSelector($num);

        if ($this->isImaginary()) {
            $value .= 'i';
        }

        return $this->setValue($value);
    }

    public function sqrt(?int $precision = null)
    {
        $precision = is_null($precision) ? $this->getPrecision() : $precision;

        if ($this instanceof FractionInterface) {
            $numerator = $this->getNumerator()->sqrt($precision);
            $denominator = $this->getDenominator()->sqrt($precision);

            if ($numerator->isWhole() && $denominator->isWhole()) {
                return $this->setValue($numerator, $denominator);
            }

            $value = $numerator->divide($denominator);
        } else {
            $value = $this->sqrtSelector($precision);
        }

        if ($this->isImaginary()) {
            $value .= 'i';
        }

        return ($this instanceof DecimalInterface) ? $this->setValue($value) : new ImmutableDecimal($value, $precision);
    }

}