<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticGMPTrait;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticHelperSimpleTrait;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticScaleTrait;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticSelectionTrait;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticNativeTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;

/**
 * @package Samsara\Fermat\Core
 */
trait ArithmeticSimpleTrait
{

    use ArithmeticSelectionTrait;
    use ArithmeticScaleTrait;
    use ArithmeticNativeTrait;
    use ArithmeticGMPTrait;
    use ArithmeticHelperSimpleTrait;

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public function add(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        [$thisNum, $thatNum] = $this->translateToObjects($num);

        if ($thatNum->isComplex()) {
            return $thatNum->add($thisNum);
        }

        return $this->helperAddSub($thisNum, $thatNum, CalcOperation::Addition);
    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public function subtract(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        [$thisNum, $thatNum] = $this->translateToObjects($num);

        if ($thatNum->isComplex()) {
            return $thatNum->multiply(-1)->add($thisNum);
        }

        return $this->helperAddSub($thisNum, $thatNum, CalcOperation::Subtraction);
    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    public function multiply(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        [$thisNum, $thatNum] = $this->translateToObjects($num);

        if ($thatNum->isComplex()) {
            return $thatNum->multiply($thisNum);
        }

        return $this->helperMulDiv($thisNum, $thatNum, CalcOperation::Multiplication, $this->getScale());
    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @param int|null $scale
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function divide(
        string|int|float|Decimal|Fraction|ComplexNumber $num,
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {

        $scale = $scale ?? $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);

        if ($thatNum->isEqual(0)) {
            throw new IntegrityConstraint(
                'Cannot divide by zero',
                'Check for zero before calling divide',
                'An attempt was made to divide by zero, which is not supported by the number system used in Fermat'
            );
        }

        if ($thatNum->isComplex()) {
            [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 0, $this->getMode());
            $thisComplex = (new ImmutableComplexNumber($thisRealPart, $thisImaginaryPart))->setMode($this->getMode());
            return $thisComplex->divide($thatNum);
        }

        return $this->helperMulDiv($thisNum, $thatNum, CalcOperation::Division, $scale);
    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function pow(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        [$thisNum, $thatNum] = $this->translateToObjects($num);

        if (($thisNum->isNegative() && !$thatNum->isInt())) {
            throw new IntegrityConstraint(
                'Decimal exponents of negative bases are not well defined.',
                'Provide only positive bases, or provide only integer exponents.'
            );
        } elseif (
            $thatNum->isComplex()
            || ($thisNum->isReal() xor $thatNum->isReal())
        ) {
            [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 0, $this->getMode());
            [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thatNum, 0, $this->getMode());

            $thisComplex = new ImmutableComplexNumber($thisRealPart, $thisImaginaryPart);
            $thatComplex = new ImmutableComplexNumber($thatRealPart, $thatImaginaryPart);

            $thisComplex->setMode($this->getMode());
            $thatComplex->setMode($this->getMode());

            return $thisComplex->pow($thatComplex);
        }

        if ($this instanceof Fraction) {
            /** @var ImmutableDecimal $powNumerator */
            $powNumerator = $thisNum->getNumerator()->pow($thatNum);
            /** @var ImmutableDecimal $powDenominator */
            $powDenominator = $thisNum->getDenominator()->pow($thatNum);

            if ($powNumerator->isWhole() && $powDenominator->isWhole()) {
                return $this->setValue($powNumerator, $powDenominator);
            }

            return $powNumerator->divide($powDenominator)->truncateToScale(10);
        } else {

            $originalScale = $this->getScale();

            $value = $thisNum->powSelector($thatNum);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            return $this->setValue($value)->roundToScale($originalScale);
        }
    }

    /**
     * @param int|null $scale
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    public function sqrt(
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        $scale = is_null($scale) ? $this->getScale() : $scale;

        if ($this instanceof Fraction) {
            $numerator = $this->getNumerator()->sqrt($scale);
            $denominator = $this->getDenominator()->sqrt($scale);

            if ($numerator->isWhole() && $denominator->isWhole()) {
                return $this->setValue($numerator, $denominator);
            }

            $value = $numerator->divide($denominator);
        } else {
            $value = $this->sqrtSelector($scale);
        }

        if ($this->isNegative()) {
            $value .= 'i';
        }

        return ($this instanceof Decimal) ? $this->setValue($value)->roundToScale($scale) : (new ImmutableDecimal($value, $scale))->roundToScale($scale);
    }

}