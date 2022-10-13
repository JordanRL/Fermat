<?php /** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

/** @noinspection ALL */


namespace Samsara\Fermat\Types\Traits;

use Composer\InstalledVersions;
use ReflectionException;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
/** @psalm-suppress UndefinedClass */
use Samsara\Fermat\ComplexNumbers;
use Samsara\Fermat\Enums\CalcOperation;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Fraction;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticGMPTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticHelperTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticScaleTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticSelectionTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticNativeTrait;
/** @psalm-suppress UndefinedClass */
use Samsara\Fermat\Values\ImmutableComplexNumber;
use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\MutableDecimal;

/**
 *
 */
trait ArithmeticSimpleTrait
{

    use ArithmeticSelectionTrait;
    use ArithmeticScaleTrait;
    use ArithmeticNativeTrait;
    use ArithmeticGMPTrait;
    use ArithmeticHelperTrait;

    /**
     * @param $num
     * @return $this|DecimalInterface|Fraction|ImmutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public function add($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num);

        if ($num->isComplex()) {
            return $num->add($this);
        }

        if ($num->isEqual(0)) {
            return $this;
        }

        if ($this->isReal() xor $num->isReal()) {
            return $this->helperAddSubXor(
                $thisRealPart,
                $thisImaginaryPart,
                $thatRealPart,
                $thatImaginaryPart,
                CalcOperation::Addition
            );
        }

        if ($this instanceof FractionInterface) {
            return $this->helperAddSubFraction($num, CalcOperation::Addition);
        } else {
            /** @var DecimalInterface|ImmutableDecimal|MutableDecimal $this */

            $value = $this->addSelector($num);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            $originalScale = $this->getScale();

            return $this->setValue($value)->roundToScale($originalScale);
        }
    }

    /**
     * @param $num
     * @return $this|DecimalInterface|Fraction|ImmutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public function subtract($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num);

        if ($num->isComplex()) {
            return $num->multiply(-1)->add($this);
        }

        if ($num->isEqual(0)) {
            return $this;
        }

        if ($this->isReal() xor $num->isReal()) {
            return $this->helperAddSubXor(
                $thisRealPart,
                $thisImaginaryPart,
                $thatRealPart,
                $thatImaginaryPart,
                CalcOperation::Subtraction
            );
        }

        if ($this instanceof FractionInterface) {
            return $this->helperAddSubFraction($num, CalcOperation::Subtraction);
        } else {
            /** @var DecimalInterface|ImmutableDecimal|MutableDecimal $this */

            $value = $this->subtractSelector($num);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            $originalScale = $this->getScale();

            return $this->setValue($value)->roundToScale($originalScale);
        }
    }

    /**
     * @param $num
     * @return $this|DecimalInterface|Fraction|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
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

        if ($this instanceof FractionInterface) {
            if ($num instanceof FractionInterface) {
                return $this->setValue(
                    $this->getNumerator()->multiply($num->getNumerator()),
                    $this->getDenominator()->multiply($num->getDenominator())
                )->simplify();
            }

            if ($num->isWhole()) {
                return $this->setValue(
                    $this->getNumerator()->multiply($num),
                    $this->getDenominator()
                )->simplify();
            }

            $value = $this->asDecimal()->multiply($num);

            return new ImmutableDecimal($value, $this->getScale());
        } else {
            /** @var DecimalInterface|ImmutableDecimal|MutableDecimal $this */
            $value = $this->multiplySelector($num);

            if ($this->isImaginary() xor $num->isImaginary()) {
                $value .= 'i';
            } elseif ($this->isImaginary() && $num->isImaginary()) {
                $value = Numbers::make(Numbers::IMMUTABLE, $value)->multiply(-1);
            }

            $originalScale = $this->getScale();

            return $this->setValue($value)->roundToScale($originalScale);
        }
    }

    /**
     * @param $num
     * @param int|null $scale
     * @return $this|DecimalInterface|Fraction|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    public function divide($num, ?int $scale = null)
    {

        $scale = $scale ?? $this->getScale();

        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 1);

        if ($num->isEqual(0)) {
            throw new IntegrityConstraint(
                'Cannot divide by zero',
                'Check for zero before calling divide',
                'An attempt was made to divide by zero, which is not supported by the number system used in Fermat'
            );
        }

        if ($num->isComplex()) {
            return $num->divide($this);
        }

        if ($num->isEqual(1)) {
            return $this;
        }

        if ($this instanceof FractionInterface) {
            if ($num instanceof FractionInterface) {
                return $this->setValue(
                    $this->getNumerator()->multiply($num->getDenominator()),
                    $this->getDenominator()->multiply($num->getNumerator())
                )->simplify();
            }

            if ($num->isWhole()) {
                return $this->setValue(
                    $this->getNumerator(),
                    $this->getDenominator()->multiply($num)
                )->simplify();
            }

            $value = $this->asDecimal($scale)->divide($num);

            return new ImmutableDecimal($value, $scale);
        } else {
            /** @var DecimalInterface|ImmutableDecimal|MutableDecimal $this */
            $value = $this->divideSelector($num, $scale);

            if ($this->isImaginary() xor $num->isImaginary()) {
                $value .= 'i';

                if ($num->isImaginary()) {
                    $value = Numbers::make(Numbers::IMMUTABLE, $value)->multiply(-1);
                }
            }

            return $this->setValue($value, $scale+1)->roundToScale($scale);
        }
    }

    /**
     * @param $num
     * @return DecimalInterface|Fraction|ImmutableComplexNumber
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public function pow($num)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 0);

        if (
            ($this->isNegative() && !$num->isInt())
            || $num->isComplex()
            || ($this->isReal() xor $num->isReal())
        ) {
            if (
                !(InstalledVersions::isInstalled('samsara/fermat-complex-numbers'))
                || !class_exists('Samsara\\Fermat\\Values\\ImmutableComplexNumber')
            ) {
                throw new MissingPackage(
                    'Creating complex numbers is unsupported in Fermat without modules.',
                    'Install the samsara/fermat-complex-numbers package using composer.',
                    'An attempt was made to create a ComplexNumber instance without having the Complex Numbers module. Please install the samsara/fermat-complex-numbers package using composer.'
                );
            }

            /** @psalm-suppress UndefinedClass */
            $thisComplex = new ImmutableComplexNumber($thisRealPart, $thisImaginaryPart);

            /** @psalm-suppress UndefinedClass */
            $thatComplex = new ImmutableComplexNumber($thatRealPart, $thatImaginaryPart);

            return $thisComplex->pow($thatComplex);
        }

        if ($this instanceof FractionInterface) {
            /** @var ImmutableDecimal $powNumerator */
            $powNumerator = $this->getNumerator()->pow($num);
            /** @var ImmutableDecimal $powDenominator */
            $powDenominator = $this->getDenominator()->pow($num);

            if ($powNumerator->isWhole() && $powDenominator->isWhole()) {
                return $this->setValue($powNumerator, $powDenominator);
            }

            return $powNumerator->divide($powDenominator)->truncateToScale(10);
        } else {

            $originalScale = $this->getScale();

            /** @var DecimalInterface|ImmutableDecimal|MutableDecimal $this */
            $value = $this->powSelector($num);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            return $this->setValue($value)->roundToScale($originalScale);
        }
    }

    /**
     * @param int|null $scale
     * @return DecimalInterface|Fraction
     * @throws IntegrityConstraint
     */
    public function sqrt(?int $scale = null)
    {
        $scale = is_null($scale) ? $this->getScale() : $scale;

        if ($this instanceof FractionInterface) {
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

        return ($this instanceof DecimalInterface) ? $this->setValue($value)->roundToScale($scale) : (new ImmutableDecimal($value, $scale))->roundToScale($scale);
    }

}