<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Types\Base\Traits\ArithmeticGMPTrait;
use Samsara\Fermat\Core\Types\Base\Traits\ArithmeticHelperSimpleTrait;
use Samsara\Fermat\Core\Types\Base\Traits\ArithmeticInternalTrait;
use Samsara\Fermat\Core\Types\Base\Traits\ArithmeticNativeTrait;
use Samsara\Fermat\Core\Types\Base\Traits\ArithmeticScaleTrait;
use Samsara\Fermat\Core\Types\Base\Traits\ArithmeticSelectionTrait;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;

/**
 * @package Samsara\Fermat\Core
 */
trait SimpleArithmeticTrait
{

    use ArithmeticSelectionTrait;
    use ArithmeticScaleTrait;
    use ArithmeticNativeTrait;
    use ArithmeticGMPTrait;
    use ArithmeticHelperSimpleTrait;
    use ArithmeticInternalTrait;

    /**
     * Adds a number to this number. Works (to the degree that math allows it to work) for all classes that extend the
     * Number abstract class.
     *
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num The number you are adding to this number
     *
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    public function add(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        if (
            is_string($num) ||
            is_float($num) ||
            is_int($num) ||
            $num instanceof MutableDecimal ||
            $num instanceof MutableFraction ||
            $num instanceof MutableComplexNumber
        ) {
            [$thisNum, $thatNum] = $this->translateToObjects($num);
        } else {
            $thisNum = $this;
            $thatNum = $num;
        }

        return $this->addInternal($thisNum, $thatNum);
    }

    /**
     * Divides this number by a number. Works (to the degree that math allows it to work) for all classes that extend the
     * Number abstract class.
     *
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num The number you dividing this number by
     * @param int|null                                        $scale The number of digits you want to return from the division. Leave null to use this object's scale.
     *
     * @return static|ImmutableFraction|ImmutableComplexNumber|ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function divide(
        string|int|float|Decimal|Fraction|ComplexNumber $num,
        ?int                                            $scale = null
    ): ImmutableDecimal|ImmutableComplexNumber|ImmutableFraction|static
    {

        $scale = $scale ?? $this->getScale();

        if (
            is_string($num) ||
            is_float($num) ||
            is_int($num) ||
            $num instanceof MutableDecimal ||
            $num instanceof MutableFraction ||
            $num instanceof MutableComplexNumber
        ) {
            [$thisNum, $thatNum] = $this->translateToObjects($num);
        } else {
            $thisNum = $this;
            $thatNum = $num;
        }

        if ($thatNum->isEqual(0)) {
            throw new IntegrityConstraint(
                'Cannot divide by zero',
                'Check for zero before calling divide',
                'An attempt was made to divide by zero, which is not supported by the number system used in Fermat'
            );
        }

        return $this->divideInternal($thisNum, $thatNum, $scale);
    }

    /**
     * Multiplies a number with this number. Works (to the degree that math allows it to work) for all classes that extend the
     * Number abstract class.
     *
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num The number you are multiplying with this number
     *
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    public function multiply(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        if (
            is_string($num) ||
            is_float($num) ||
            is_int($num) ||
            $num instanceof MutableDecimal ||
            $num instanceof MutableFraction ||
            $num instanceof MutableComplexNumber
        ) {
            [$thisNum, $thatNum] = $this->translateToObjects($num);
        } else {
            $thisNum = $this;
            $thatNum = $num;
        }

        return $this->multiplyInternal($thisNum, $thatNum, $thisNum->getScale());
    }

    /**
     * Raises this number to the power of a number. Works (to the degree that math allows it to work) for all classes
     * that extend the Number abstract class.
     *
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num The exponent to raise the number to
     *
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function pow(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        [$thisNum, $thatNum] = $this->translateToObjects($num);

        if ($thisNum->isNegative() && !$thatNum->isInt()) {
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

            /** @noinspection PhpUnhandledExceptionInspection */
            $thisComplex = new ImmutableComplexNumber($thisRealPart, $thisImaginaryPart);
            /** @noinspection PhpUnhandledExceptionInspection */
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

            $value = $this->powSelector($thatNum);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            return $this->setValue($value)->roundToScale($originalScale);
        }
    }

    /**
     * Takes the (positive) square root of this number. Works (to the degree that math allows it to work) for all classes
     * that extend the Number abstract class.
     *
     * @param int|null $scale The number of digits you want to return from the operation. Leave null to use this object's scale.
     *
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

    /**
     * Subtracts a number from this number. Works (to the degree that math allows it to work) for all classes that extend the
     * Number abstract class.
     *
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num The number you are subtracting from this number
     *
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    public function subtract(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        if (
            is_string($num) ||
            is_float($num) ||
            is_int($num) ||
            $num instanceof MutableDecimal ||
            $num instanceof MutableFraction ||
            $num instanceof MutableComplexNumber
        ) {
            [$thisNum, $thatNum] = $this->translateToObjects($num);
        } else {
            $thisNum = $this;
            $thatNum = $num;
        }

        return $this->subtractInternal($thisNum, $thatNum);
    }

}