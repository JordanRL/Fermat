<?php


namespace Samsara\Fermat\Types\Traits\Arithmetic;


use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Selectable;
use Samsara\Fermat\Values\ImmutableComplexNumber;

trait ArithmeticSelectionTrait
{

    /** @var int */
    protected $mode;
    /** @var array */
    protected $modeRegister;

    public function add($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart
        ] = $this->translateToParts($this, $num, 0);

        if (($this->isReal() xor $num->isReal()) || $num->isComplex()) {
            $newRealPart = $thisRealPart->add($thatRealPart);
            $newImaginaryPart = $thisImaginaryPart->add($thatImaginaryPart);

            return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
        } elseif ($this instanceof FractionInterface) {
            if ($this->getDenominator()->isEqual($num->getDenominator())) {
                $finalDenominator = $this->getDenominator();
                $finalNumerator = $this->getNumerator()->add($num->getNumerator());
            } else {
                $finalDenominator = $this->getSmallestCommonDenominator($num);

                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($num, $finalDenominator);

                $finalNumerator = $thisNumerator->add($thatNumerator);
            }

            return $this->setValue(
                $finalNumerator,
                $finalDenominator
            );
        } else {
            $value = $this->addSelector($num);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            return $this->setValue($value);
        }
    }

    public function subtract($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart
        ] = $this->translateToParts($this, $num, 0);

        if (($this->isReal() xor $num->isReal()) || $num->isComplex()) {
            $newRealPart = $thisRealPart->subtract($thatRealPart);
            $newImaginaryPart = $thisImaginaryPart->subtract($thatImaginaryPart);

            return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
        } elseif ($this instanceof FractionInterface) {
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
        } else {
            $value = $this->subtractSelector($num);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            return $this->setValue($value);
        }
    }

    public function multiply($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart
        ] = $this->translateToParts($this, $num, 1);

        if (($this->isReal() xor $num->isReal()) || $num->isComplex()) {
            $newRealPart = $thisRealPart->multiply($thatRealPart);
            $newImaginaryPart = $thisImaginaryPart->multiply($thatImaginaryPart);

            return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
        } elseif ($this instanceof FractionInterface) {
            return $this->setValue(
                $this->getNumerator()->multiply($num->getNumerator()),
                $this->getDenominator()->multiply($num->getDenominator())
            );
        } else {
            $value = $this->multiplySelector($num);

            if ($this->isImaginary() xor $num->isImaginary()) {
                $value .= 'i';
            } elseif ($this->isImaginary() && $num->isImaginary()) {
                $value = Numbers::make(Numbers::IMMUTABLE, $value)->multiply(-1);
            }

            return $this->setValue($value);
        }
    }

    public function divide($num, ?int $precision = null)
    {
        $precision = is_null($precision) ? $this->getPrecision() : $precision;

        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart
        ] = $this->translateToParts($this, $num, 1);

        if (($this->isReal() xor $num->isReal()) || $num->isComplex()) {
            $newRealPart = $thisRealPart->divide($thatRealPart);
            $newImaginaryPart = $thisImaginaryPart->divide($thatImaginaryPart);

            return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
        } elseif ($this instanceof FractionInterface) {
            return $this->setValue(
                $this->getNumerator()->multiply($num->getDenominator()),
                $this->getDenominator()->multiply($num->getNumerator())
            );
        } else {
            $value = $this->divideSelector($num, $precision);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            return $this->setValue($value);
        }
    }

    public function pow($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart
        ] = $this->translateToParts($this, $num, 1);

        if (($this->isReal() xor $num->isReal()) || $num->isComplex()) {
            $newRealPart = $thisRealPart->pow($thatRealPart);
            $newImaginaryPart = $thisImaginaryPart->pow($thatImaginaryPart);

            return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
        } else {
            $value = $this->powSelector($num);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            return $this->setValue($value);
        }
    }

    public function sqrt(?int $precision = null)
    {
        $precision = is_null($precision) ? $this->getPrecision() : $precision;

        $value = $this->sqrtSelector($precision);

        if ($this->isImaginary()) {
            $value .= 'i';
        }

        return $this->setValue($value);
    }

    protected function addSelector(DecimalInterface $num)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->addPrecision($num);
                break;

            case Selectable::MODE_NATIVE:
                return $this->addNative($num);
                break;

            default:
                return call_user_func([$this, $this->modeRegister[Selectable::MODE_FALLBACK]['add']], $num);
                break;
        }
    }

    protected function subtractSelector(DecimalInterface $num)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->subtractPrecision($num);
                break;

            case Selectable::MODE_NATIVE:
                return $this->subtractNative($num);
                break;

            default:
                return call_user_func([$this, $this->modeRegister[Selectable::MODE_FALLBACK]['subtract']], $num);
                break;
        }
    }

    protected function multiplySelector(DecimalInterface $num)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->multiplyPrecision($num);
                break;

            case Selectable::MODE_NATIVE:
                return $this->multiplyNative($num);
                break;

            default:
                return call_user_func([$this, $this->modeRegister[Selectable::MODE_FALLBACK]['multiply']], $num);
                break;
        }
    }

    protected function divideSelector(DecimalInterface $num, int $precision)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->dividePrecision($num, $precision);
                break;

            case Selectable::MODE_NATIVE:
                return $this->divideNative($num);
                break;

            default:
                return call_user_func([$this, $this->modeRegister[Selectable::MODE_FALLBACK]['divide']], $num, $precision);
                break;
        }
    }

    protected function powSelector(DecimalInterface $num)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->powPrecision($num);
                break;

            case Selectable::MODE_NATIVE:
                return $this->powNative($num);
                break;

            default:
                return call_user_func([$this, $this->modeRegister[Selectable::MODE_FALLBACK]['pow']], $num);
                break;
        }
    }

    protected function sqrtSelector(int $precision)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->sqrtPrecision($precision);
                break;

            case Selectable::MODE_NATIVE:
                return $this->sqrtNative();
                break;

            default:
                return call_user_func([$this, $this->modeRegister[Selectable::MODE_FALLBACK]['sqrt']], $precision);
                break;
        }
    }

}