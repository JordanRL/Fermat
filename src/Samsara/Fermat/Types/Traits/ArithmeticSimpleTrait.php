<?php


namespace Samsara\Fermat\Types\Traits;

use Composer\InstalledVersions;
use ReflectionException;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Fermat\ComplexNumbers;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticScaleTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticSelectionTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticNativeTrait;
use Samsara\Fermat\Values\ImmutableComplexNumber;
use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Values\ImmutableFraction;

trait ArithmeticSimpleTrait
{

    use ArithmeticSelectionTrait;
    use ArithmeticScaleTrait;
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
            if (!(InstalledVersions::isInstalled("samsara/fermat-complex-numbers"))) {
                throw new MissingPackage(
                    'Creating complex numbers is unsupported in Fermat without modules.',
                    'Install the samsara/fermat-complex-numbers package using composer.',
                    'An attempt was made to create a ComplexNumber instance without having the Complex Numbers module. Please install the samsara/fermat-complex-numbers package using composer.'
                );
            }

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
            if (!(InstalledVersions::isInstalled("samsara/fermat-complex-numbers"))) {
                throw new MissingPackage(
                    'Creating complex numbers is unsupported in Fermat without modules.',
                    'Install the samsara/fermat-complex-numbers package using composer.',
                    'An attempt was made to create a ComplexNumber instance without having the Complex Numbers module. Please install the samsara/fermat-complex-numbers package using composer.'
                );
            }

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

        if ($this instanceof FractionInterface) {
            if ($num instanceof FractionInterface) {
                return $this->setValue(
                    $this->getNumerator()->multiply($num->getNumerator()),
                    $this->getDenominator()->multiply($num->getDenominator())
                );
            }

            if ($num->isWhole()) {
                return $this->setValue(
                    $this->getNumerator()->multiply($num),
                    $this->getDenominator()
                );
            }

            $value = $this->asDecimal()->multiply($num);

            return new ImmutableDecimal($value, $this->getScale());
        }

        $value = $this->multiplySelector($num);

        if ($this->isImaginary() xor $num->isImaginary()) {
            $value .= 'i';
        } elseif ($this->isImaginary() && $num->isImaginary()) {
            $value = Numbers::make(Numbers::IMMUTABLE, $value)->multiply(-1);
        }

        return $this->setValue($value);
    }

    public function divide($num, ?int $scale = null)
    {
        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 1);

        $scale = is_null($scale) ? $this->getScale() : $scale;

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

        if ($this instanceof FractionInterface) {
            if ($num instanceof FractionInterface) {
                return $this->setValue(
                    $this->getNumerator()->multiply($num->getDenominator()),
                    $this->getDenominator()->multiply($num->getNumerator())
                );
            }

            if ($num->isWhole()) {
                return $this->setValue(
                    $this->getNumerator(),
                    $this->getDenominator()->multiply($num)
                );
            }

            $value = $this->asDecimal($scale)->divide($num);

            return new ImmutableDecimal($value, $scale);
        }

        $value = $this->divideSelector($num, $scale);

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
            if (!(InstalledVersions::isInstalled("samsara/fermat-complex-numbers"))) {
                throw new MissingPackage(
                    'Creating complex numbers is unsupported in Fermat without modules.',
                    'Install the samsara/fermat-complex-numbers package using composer.',
                    'An attempt was made to create a ComplexNumber instance without having the Complex Numbers module. Please install the samsara/fermat-complex-numbers package using composer.'
                );
            }

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

        if ($this->isImaginary()) {
            $value .= 'i';
        }

        return ($this instanceof DecimalInterface) ? $this->setValue($value) : new ImmutableDecimal($value, $scale);
    }

}