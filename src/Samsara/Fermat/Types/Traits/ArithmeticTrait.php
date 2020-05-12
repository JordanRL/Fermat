<?php

declare(strict_types=1);

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\ComplexNumberInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Types\ComplexNumber;
use Samsara\Fermat\Types\Decimal;
use Samsara\Fermat\Types\Fraction;
use Samsara\Fermat\Values\Algebra\PolynomialFunction;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\PolarCoordinate;
use Samsara\Fermat\Values\ImmutableComplexNumber;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Values\MutableFraction;

trait ArithmeticTrait
{

    protected function checkArithmeticTraitAndInterface()
    {

        if ($this instanceof DecimalInterface) {
            return 1;
        } elseif ($this instanceof FractionInterface) {
            return 2;
        } elseif ($this instanceof ComplexNumberInterface) {
            return 3;
        } else {
            throw new IntegrityConstraint(
                'The ArithmeticTrait can only be used by an object that implements either the DecimalInterface or FractionInterface',
                'Implement either of the required interfaces',
                'You cannot use the ArithmeticTrait without implementing either the DecimalInterface or FractionInterface'
            );
        }

    }

    protected function transformNum($num, $instance)
    {
        if ($instance == 1 || (is_string($num) && strpos($num, '.') !== false) || is_float($num) || is_int($num)) {
            if (is_object($num) && $num instanceof FractionInterface) {
                $num = $num->asDecimal($this->getPrecision());
            } elseif (is_object($num) && $num instanceof ComplexNumberInterface) {
                return $num;
            } else {
                if (method_exists($this, 'getPrecision')) {
                    $precision = $this->getPrecision();
                } else {
                    $precision = 10;
                }

                $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $num, $precision);
            }
        } elseif ($instance == 2) {
            $num = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $num);
        }

        return $num;

    }

    public function add($num)
    {

        $check = $this->checkArithmeticTraitAndInterface();

        $num = $this->transformNum($num, $check);

        if (!($this instanceof FractionInterface)) {
            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();
        }

        if ($this->isComplex() || $num->isComplex()) {
            if (!$num->isComplex()) {
                if ($num->isReal()) {
                    $numRealPart = $num;
                    $numImaginaryPart = Numbers::makeZero();
                } else {
                    $numRealPart = Numbers::makeZero();
                    $numImaginaryPart = $num;
                }
            } else {
                $numRealPart = $num->getRealPart();
                $numImaginaryPart = $num->getImaginaryPart();
            }

            if (!$this->isComplex()) {
                if ($this->isReal()) {
                    $thisRealPart = clone $this;
                    $thisImaginaryPart = Numbers::makeZero();
                } else {
                    $thisRealPart = Numbers::makeZero();
                    $thisImaginaryPart = clone $this;
                }

                $newRealPart = $thisRealPart->add($numRealPart);
                $newImaginaryPart = $thisImaginaryPart->add($numImaginaryPart);

                if ($newRealPart->isEqual(0) xor $newImaginaryPart->isEqual(0)) {
                    $newNum = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

                    return $this->setValue($newNum->getValue(), $internalPrecision);
                }

                if ($newRealPart->isEqual(0) && $newImaginaryPart->isEqual(0)) {
                    return $this->setValue('0', $internalPrecision);
                }

                return new ImmutableComplexNumber($newRealPart, $newImaginaryPart, $internalPrecision);
            } else {
                $newRealPart = $this->getRealPart()->add($numRealPart);
                $newImaginaryPart = $this->getImaginaryPart()->add($numImaginaryPart);

                if ($newRealPart->isEqual(0) xor $newImaginaryPart->isEqual(0)) {
                    $newNum = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

                    return new ImmutableDecimal($newNum->getValue(), $internalPrecision);
                } elseif ($newRealPart->isEqual(0) && $newImaginaryPart->isEqual(0)) {
                    return new ImmutableDecimal('0', $internalPrecision);
                } else {
                    return $this->setValue($newRealPart, $newImaginaryPart);
                }
            }
        } elseif (($this->isReal() xor $num->isReal()) && ($this->isImaginary() xor $num->isImaginary())) {
            if ($this->isEqual(0) || $num->isEqual(0)) {
                if ($num->isEqual(0)) {
                    return $this;
                } else {
                    return $num;
                }
            } else {
                $newRealPart = $this->isReal() ? clone $this : $num;
                $newImaginaryPart = $this->isImaginary() ? clone $this : $num;

                return new ImmutableComplexNumber($newRealPart, $newImaginaryPart, $internalPrecision);
            }
        }

        if ($check == 1) {
            /**
             * @var SimpleNumberInterface|ComplexNumberInterface $num
             * @var SimpleNumberInterface|ComplexNumberInterface $this
             */

            $value = ArithmeticProvider::add($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber(), $internalPrecision);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            return $this->setValue($value, $internalPrecision);
        } else {
            /**
             * @var ImmutableFraction $num
             * @var ImmutableFraction|MutableFraction|FractionInterface $this
             */

            if ($this->getDenominator()->isEqual($num->getDenominator())) {
                $finalDenominator = $this->getDenominator();
                $finalNumerator = $this->getNumerator()->add($num->getNumerator());
            } else {
                $finalDenominator = $this->getSmallestCommonDenominator($num);

                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($num, $finalDenominator);

                $finalNumerator = $thisNumerator->add($thatNumerator);
            }

            return $this->setValue($finalNumerator, $finalDenominator);
        }

    }

    public function subtract($num)
    {
        $check = $this->checkArithmeticTraitAndInterface();

        $num = $this->transformNum($num, $check);

        if (!($this instanceof FractionInterface)) {
            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();
        }

        if ($this->isComplex() || $num->isComplex()) {
            if (!$num->isComplex()) {
                if ($num->isReal()) {
                    $numRealPart = $num;
                    $numImaginaryPart = Numbers::makeZero();
                } else {
                    $numRealPart = Numbers::makeZero();
                    $numImaginaryPart = $num;
                }
            } else {
                $numRealPart = $num->getRealPart();
                $numImaginaryPart = $num->getImaginaryPart();
            }

            if (!$this->isComplex()) {
                if ($this->isReal()) {
                    $thisRealPart = clone $this;
                    $thisImaginaryPart = Numbers::makeZero();
                } else {
                    $thisRealPart = Numbers::makeZero();
                    $thisImaginaryPart = clone $this;
                }

                $newRealPart = $thisRealPart->subtract($numRealPart);
                $newImaginaryPart = $thisImaginaryPart->subtract($numImaginaryPart);

                if ($newRealPart->isEqual(0) xor $newImaginaryPart->isEqual(0)) {
                    $newNum = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

                    return $this->setValue($newNum->getValue(), $internalPrecision);
                } elseif ($newRealPart->isEqual(0) && $newImaginaryPart->isEqual(0)) {
                    return $this->setValue('0', $internalPrecision);
                } else {
                    return new ImmutableComplexNumber($newRealPart, $newImaginaryPart, $internalPrecision);
                }
            } else {
                $newRealPart = $this->getRealPart()->subtract($numRealPart);
                $newImaginaryPart = $this->getImaginaryPart()->subtract($numImaginaryPart);

                if ($newRealPart->isEqual(0) xor $newImaginaryPart->isEqual(0)) {
                    $newNum = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

                    return new ImmutableDecimal($newNum->getValue(), $internalPrecision);
                } elseif ($newRealPart->isEqual(0) && $newImaginaryPart->isEqual(0)) {
                    return new ImmutableDecimal('0', $internalPrecision);
                } else {
                    return $this->setValue($newRealPart, $newImaginaryPart);
                }
            }
        } elseif (($this->isReal() xor $num->isReal()) && ($this->isImaginary() xor $num->isImaginary())) {
            if ($this->isEqual(0) || $num->isEqual(0)) {
                if ($num->isEqual(0)) {
                    return $this;
                } else {
                    return $num->multiply(-1);
                }
            } else {
                $newRealPart = $this->isReal() ? clone $this : $num->multiply(-1);
                $newImaginaryPart = $this->isImaginary() ? clone $this : $num->multiply(-1);

                return new ImmutableComplexNumber($newRealPart, $newImaginaryPart, $internalPrecision);
            }
        }

        if ($check == 1) {
            $value = ArithmeticProvider::subtract($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber(), $internalPrecision);

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            return $this->setValue($value)->truncateToPrecision($internalPrecision);
        } else {
            /** @var ImmutableFraction $num */

            if ($this->getDenominator()->isEqual($num->getDenominator())) {
                $finalDenominator = $this->getDenominator();
                $finalNumerator = $this->getNumerator()->subtract($num->getNumerator());
            } else {
                $finalDenominator = $this->getSmallestCommonDenominator($num);

                list($thisNumerator, $thatNumerator) = $this->getNumeratorsWithSameDenominator($num, $finalDenominator);

                $finalNumerator = $thisNumerator->subtract($thatNumerator);
            }

            return $this->setValue($finalNumerator, $finalDenominator);
        }

    }

    public function multiply($num)
    {

        $check = $this->checkArithmeticTraitAndInterface();

        $num = $this->transformNum($num, $check);

        if (!($this instanceof FractionInterface)) {
            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();
        }

        if ($this->isComplex() || $num->isComplex()) {
            if ($this->isComplex() && $num->isComplex()) {
                $foiled = PolynomialFunction::createFromFoil([
                    $this->getRealPart(),
                    $this->getImaginaryPart()
                ], [
                    $num->getRealPart(),
                    $num->getImaginaryPart()
                ]);

                $parts = $foiled->describeShape();

                $newRealPart = new ImmutableDecimal($parts[0], $internalPrecision);
                $newImaginaryPart = new ImmutableDecimal($parts[1], $internalPrecision);
                /** @var ImmutableDecimal $newRealPart */
                $newRealPart = $newRealPart->add($parts[2]);
            } else {
                $complexPart = $this->isComplex() ? $this : $num;
                $simplePart = !$this->isComplex() ? $this : $num;

                $value1 = $complexPart->getRealPart()->multiply($simplePart);
                $value2 = $complexPart->getImaginaryPart()->multiply($simplePart);

                $newRealPart = $value1->isReal() ? $value1 : $value2;
                $newImaginaryPart = $value1->isImaginary() ? $value1 : $value2;
            }

            if (!$newRealPart->isEqual(0) && !$newImaginaryPart->isEqual(0)) {
                if ($this->isComplex()) {
                    return $this->setValue($newRealPart, $newImaginaryPart);
                } else {
                    return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
                }
            } else {
                $value = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

                if ($this->isComplex()) {
                    return new ImmutableDecimal($value->getValue(), $internalPrecision);
                } else {
                    return $this->setValue($value->getValue(), $internalPrecision);
                }
            }
        }

        if ($check == 1) {
            $value = ArithmeticProvider::multiply($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber(), $internalPrecision);

            if ($this->isImaginary() xor $num->isImaginary()) {
                $value .= 'i';
            } elseif ($this->isImaginary() && $num->isImaginary()) {
                $value = ArithmeticProvider::multiply($value, '-1', $internalPrecision);
            }

            return $this->setValue($value)->truncateToPrecision($internalPrecision);
        } else {
            /** @var ImmutableFraction $num */

            $finalDenominator = $this->getDenominator()->multiply($num->getDenominator());
            $finalNumerator = $this->getNumerator()->multiply($num->getNumerator());

            return $this->setValue($finalNumerator, $finalDenominator);
        }

    }

    public function divide($num, $precision = null)
    {
        $check = $this->checkArithmeticTraitAndInterface();

        $num = $this->transformNum($num, $check);

        if (!is_int($precision) && !($this instanceof FractionInterface)) {
            $precision = ($this->getPrecision() > $num->getPrecision()) ? $num->getPrecision() : $this->getPrecision();
        }

        if ($this->isComplex() || $num->isComplex()) {
            if ($this->isComplex() && $num->isComplex()) {
                $newRho = $this->getDistanceFromOrigin()->divide($num->getDistanceFromOrigin(), $precision);
                $newTheta = $this->getPolarAngle()->subtract($num->getPolarAngle());

                $polar = new PolarCoordinate($newRho, $newTheta);
                $cartesian = $polar->asCartesian();

                $newRealPart = $cartesian->getAxis('x');
                $newImaginaryPart = $cartesian->getAxis('y');
            } else {
                $complexPart = $this->isComplex() ? $this : $num;
                $simplePart = !$this->isComplex() ? $this : $num;

                $newRealPart = $complexPart->getRealPart()->divide($simplePart, $precision);
                $newImaginaryPart = $complexPart->getImaginaryPart()->divide($simplePart, $precision);
            }

            if ($this->isComplex()) {
                return $this->setValue($newRealPart, $newImaginaryPart);
            } else {
                return new ImmutableComplexNumber($newRealPart, $newImaginaryPart);
            }
        }

        if ($check == 1) {
            $value = ArithmeticProvider::divide($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber(), $precision);

            if ($this->isImaginary() xor $num->isImaginary()) {
                $value .= 'i';
            }

            return $this->setValue($value, $precision, $this->getBase());
        } else {
            if ($num instanceof DecimalInterface) {
                $numerator = $num;
                $denominator = Numbers::makeOne();
            } else {
                $numerator = $num->getNumerator();
                $denominator = $num->getDenominator();
            }

            $finalDenominator = $this->getDenominator()->multiply($numerator);
            $finalNumerator = $this->getNumerator()->multiply($denominator);

            return $this->setValue($finalNumerator, $finalDenominator);
        }
    }

    public function pow($num)
    {
        $check = $this->checkArithmeticTraitAndInterface();

        $num = $this->transformNum($num, $check);

        if ($check == 2) {
            /** @var ImmutableDecimal $powNumerator */
            $powNumerator = $this->getNumerator()->pow($num);
            /** @var ImmutableDecimal $powDenominator */
            $powDenominator = $this->getDenominator()->pow($num);

            if ($powNumerator->isWhole() && $powDenominator->isWhole()) {
                return $this->setValue($powNumerator, $powDenominator);
            } else {
                return $powNumerator->divide($powDenominator);
            }
        }

        if (!($this instanceof FractionInterface)) {
            $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();
            $internalPrecision += 5;
        }

        if (!$this->isReal() || !$num->isReal()) {
            if ($this->isReal()) {
                /** @var ImmutableDecimal $thisRho */
                $thisRho = $this->abs();
                /** @var ImmutableDecimal $thisTheta */
                $thisTheta = Numbers::makeZero();
            } elseif ($this->isImaginary()) {
                if ($this->isNegative()) {
                    /** @var ImmutableDecimal $thisTheta */
                    $thisTheta = Numbers::makePi()->multiply(3)->divide(2);
                } else {
                    /** @var ImmutableDecimal $thisTheta */
                    $thisTheta = Numbers::makePi()->divide(2);
                }
                /** @var ImmutableDecimal $thisRho */
                $thisRho = $this->abs();
            } else {
                /** @var ImmutableDecimal $thisRho */
                $thisRho = $this->getDistanceFromOrigin();
                /** @var ImmutableDecimal $thisTheta */
                $thisTheta = $this->getPolarAngle();
            }

            if ($num->isReal()) {
                /** @var ImmutableDecimal $numRealPart */
                $numRealPart = clone $num;
                /** @var ImmutableDecimal $numImaginaryPart */
                $numImaginaryPart = Numbers::makeZero();
            } elseif ($num->isImaginary()) {
                /** @var ImmutableDecimal $numRealPart */
                $numRealPart = Numbers::makeZero();
                /** @var ImmutableDecimal $numImaginaryPart */
                $numImaginaryPart = Numbers::make(Numbers::IMMUTABLE, $num->getAsBaseTenRealNumber());
            } else {
                /** @var ImmutableDecimal $numRealPart */
                $numRealPart = $num->getRealPart();
                /** @var ImmutableDecimal $numImaginaryPart */
                $numImaginaryPart = Numbers::make(Numbers::IMMUTABLE, $num->getImaginaryPart()->getAsBaseTenRealNumber());
            }

            $e = Numbers::makeE();

            $coef = $thisRho->pow($numRealPart)->multiply($e->pow($thisTheta->multiply($numImaginaryPart)->multiply(-1)));

            /** @var ImmutableDecimal $trigArg */
            $trigArg = $numImaginaryPart->multiply($thisRho->ln())->add($numRealPart->multiply($thisTheta));

            $newRealPart = $trigArg->cos($internalPrecision)->multiply($coef);
            $newImaginaryPart = $trigArg->sin($internalPrecision)->multiply($coef)->multiply('i');

            if ($newRealPart->absValue() == '0' xor $newImaginaryPart->absValue() == '0') {
                $newVal = $newRealPart->absValue() == '0' ? $newRealPart : $newImaginaryPart;

                if ($this instanceof DecimalInterface) {
                    return $thisRho->setValue($newVal->getValue());
                } else {
                    return $newVal;
                }
            } elseif ($newRealPart->absValue() == '0' && $newImaginaryPart->absValue() == '0') {
                if ($this instanceof DecimalInterface) {
                    return $thisRho->setValue('0', $internalPrecision);
                } else {
                    return Numbers::makeZero();
                }
            }

            return $this->setValue($newRealPart, $newImaginaryPart);
        }

        if ($num->isWhole()) {
            $value = ArithmeticProvider::pow($this->getValue(), $num->getValue(), $internalPrecision);
        } else {
            $exponent = $num->multiply($this->ln($internalPrecision));
            $value = $exponent->exp($internalPrecision)->getValue();
        }

        return $this->setValue($value)->truncateToPrecision($internalPrecision-5);

    }

    /**
     * This function returns the positive square root. If the number that the root is being taken of is not a positive
     * real number, then the root given is the one found when theta is less than 2*pi in the polar form of the number.
     *
     * To get the positive and negative roots, or the root for both forms of theta, use the root($num) function.
     *
     * NOTE: The root($num) function is not yet implemented.
     *
     * @param int? $precision
     *
     * @return ComplexNumber|Decimal|Fraction
     * @throws IntegrityConstraint
     */
    public function sqrt(int $precision = null)
    {
        $check = $this->checkArithmeticTraitAndInterface();

        if (!($this instanceof FractionInterface)) {
            $precision = $precision?? $this->getPrecision();
        }

        if (!$this->isReal() || $this->isNegative()) {
            /** @var ComplexNumber $complex */
            $complex = $this->asComplex();

            $rho = $complex->getRealPart();
            $theta = $complex->getImaginaryPart();

            if (!$rho->isEqual(0)) {
                $rho = ArithmeticProvider::squareRoot($rho->getAsBaseTenRealNumber(), $precision);
            }

            $theta = $theta->divide(2);

            $newPolar = new PolarCoordinate($rho, $theta);
            $newCartesian = $newPolar->asCartesian();

            $newRealPart = $newCartesian->getAxis('x');
            $newImaginaryPart = $newCartesian->getAxis('y');

            if (!$newRealPart->isEqual(0) && !$newImaginaryPart->isEqual(0)) {
                if ($this->isComplex()) {
                    return $this->setValue($newRealPart, $newImaginaryPart, $precision);
                } else {
                    return new ImmutableComplexNumber($newRealPart, $newImaginaryPart, $precision);
                }
            } else {
                $newValue = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

                if (!$this->isComplex()) {
                    return $this->setValue($newValue->getValue(), $precision);
                } else {
                    return new ImmutableDecimal($newValue->getValue(), $precision);
                }
            }
        }

        if ($check == 1) {
            $value = ArithmeticProvider::squareRoot($this->getAsBaseTenRealNumber(), $precision);

            return $this->setValue($value, $precision);
        } else {
            /** @var ImmutableDecimal $sqrtNumerator */
            $sqrtNumerator = $this->getNumerator()->sqrt($precision);
            /** @var ImmutableDecimal $sqrtDenominator */
            $sqrtDenominator = $this->getDenominator()->sqrt($precision);

            if ($sqrtNumerator->isWhole() && $sqrtDenominator->isWhole()) {
                return $this->setValue($sqrtNumerator, $sqrtDenominator);
            } else {
                return $sqrtNumerator->divide($sqrtDenominator);
            }
        }

    }

}