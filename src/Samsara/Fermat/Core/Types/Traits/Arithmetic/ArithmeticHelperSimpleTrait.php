<?php

namespace Samsara\Fermat\Core\Types\Traits\Arithmetic;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Core\Types\Traits\InputNormalizationTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;

/**
 *
 */
trait ArithmeticHelperSimpleTrait
{

    use InputNormalizationTrait;

    /**
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum
     * @param CalcOperation $operation
     * @return static|ImmutableComplexNumber|ImmutableDecimal|MutableDecimal|ImmutableFraction|MutableFraction
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperAddSub(
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum,
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum,
        CalcOperation $operation
    ): static|ImmutableComplexNumber|ImmutableDecimal|MutableDecimal|ImmutableFraction|MutableFraction
    {
        if ($thatNum->isEqual(0)) {
            return $this;
        }

        if ($this->isReal() xor $thatNum->isReal()) {
            return $this->helperAddSubXor($thisNum, $thatNum, $operation);
        }

        if ($thisNum instanceof FractionInterface) {
            return $this->helperAddSubFraction($thatNum, $operation);
        } else {
            $value = match ($operation) {
                CalcOperation::Addition => $thisNum->addSelector($thatNum),
                CalcOperation::Subtraction => $thisNum->subtractSelector($thatNum),
                /** @codeCoverageIgnore  */
                default => throw new IncompatibleObjectState(
                    'Cannot use the AddSub helper with other operations',
                    'None'
                )
            };

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            $originalScale = $this->getScale();

            /** @var ImmutableDecimal|MutableDecimal $this */
            return $this->setValue($value)->roundToScale($originalScale);
        }
    }

    /**
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum
     * @param CalcOperation $operation
     * @return ImmutableComplexNumber|static
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperAddSubXor(
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum,
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum,
        CalcOperation $operation
    ): static|ImmutableComplexNumber
    {
        [$thisRealPart, $thisImaginaryPart] = static::partSelector($thisNum, $thatNum, 0, $this->getMode());
        [$thatRealPart, $thatImaginaryPart] = static::partSelector($thatNum, $thisNum, 0, $this->getMode());

        $newRealPart = match($operation) {
            CalcOperation::Addition => $thisRealPart->add($thatRealPart),
            CalcOperation::Subtraction => $thisRealPart->subtract($thatRealPart),
            /** @codeCoverageIgnore  */
            default => throw new IncompatibleObjectState(
                'Cannot use the AddSub helper with other operations',
                'None'
            )
        };
        $newImaginaryPart = match($operation) {
            CalcOperation::Addition => $thisImaginaryPart->add($thatImaginaryPart),
            CalcOperation::Subtraction => $thisImaginaryPart->subtract($thatImaginaryPart),
            /** @codeCoverageIgnore  */
            default => throw new IncompatibleObjectState(
                'Cannot use the AddSub helper with other operations',
                'None'
            )
        };

        if ($newImaginaryPart->isEqual(0)) {
            return $this->setValue($newRealPart->getValue(NumberBase::Ten));
        }

        if ($newRealPart->isEqual(0)) {
            return $this->setValue($newImaginaryPart->getValue(NumberBase::Ten));
        }

        $complex = new ImmutableComplexNumber($newRealPart, $newImaginaryPart);

        return $complex->setMode($this->getMode());
    }

    /**
     * @param ImmutableDecimal|ImmutableFraction $num
     * @param CalcOperation $operation
     * @return ImmutableFraction|MutableFraction
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function helperAddSubFraction(
        ImmutableDecimal|ImmutableFraction $num,
        CalcOperation $operation
    ): ImmutableFraction|MutableFraction
    {
        if ($num instanceof FractionInterface && $this->getDenominator()->isEqual($num->getDenominator())) {
            $finalDenominator = $this->getDenominator();
            $finalNumerator = match ($operation) {
                CalcOperation::Addition => $this->getNumerator()->add($num->getNumerator()),
                CalcOperation::Subtraction => $this->getNumerator()->subtract($num->getNumerator()),
                /** @codeCoverageIgnore  */
                default => throw new IncompatibleObjectState(
                    'Cannot use the AddSub helper with other operations',
                    'None'
                )
            };
        } elseif ($num instanceof FractionInterface) {
            $finalDenominator = $this->getSmallestCommonDenominator($num);

            [$thisNumerator, $thatNumerator] = $this->getNumeratorsWithSameDenominator($num, $finalDenominator);

            $finalNumerator = match ($operation) {
                CalcOperation::Addition => $thisNumerator->add($thatNumerator),
                CalcOperation::Subtraction => $thisNumerator->subtract($thatNumerator),
                /** @codeCoverageIgnore  */
                default => throw new IncompatibleObjectState(
                    'Cannot use the AddSub helper with other operations',
                    'None'
                )
            };
        } else {
            $finalDenominator = $this->getDenominator();
            $finalNumerator = match ($operation) {
                CalcOperation::Addition => $this->getNumerator()->add($num->multiply($finalDenominator)),
                CalcOperation::Subtraction => $this->getNumerator()->subtract($num->multiply($finalDenominator)),
                /** @codeCoverageIgnore  */
                default => throw new IncompatibleObjectState(
                    'Cannot use the AddSub helper with other operations',
                    'None'
                )
            };
        }

        return $this->setValue(
            $finalNumerator,
            $finalDenominator
        )->simplify();
    }

    /**
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum
     * @param CalcOperation $operation
     * @return ImmutableComplexNumber|static
     */
    public function helperMulDiv(
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum,
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum,
        CalcOperation $operation,
        int $scale
    ): static|ImmutableComplexNumber
    {
        if ($thatNum->isEqual(1)) {
            return $this;
        }

        if ($thisNum instanceof ImmutableFraction) {
            return $this->helperMulDivFraction($thisNum, $thatNum, $operation, $scale);
        }

        /** @var DecimalInterface|ImmutableDecimal|MutableDecimal $this */
        $value = match ($operation) {
            CalcOperation::Multiplication => $thisNum->multiplySelector($thatNum),
            CalcOperation::Division => $thisNum->divideSelector($thatNum, $scale),
            /** @codeCoverageIgnore  */
            default => throw new IncompatibleObjectState(
                'Cannot use the MulDiv helper with other operations',
                'None'
            )
        };

        if ($this->isImaginary() xor $thatNum->isImaginary()) {
            $value .= 'i';

            if ($thatNum->isImaginary() && $operation == CalcOperation::Division) {
                $value = Numbers::make(Numbers::IMMUTABLE, $value)->multiply(-1);
            }
        } elseif ($this->isImaginary() && $thatNum->isImaginary() && $operation == CalcOperation::Multiplication) {
            $value = Numbers::make(Numbers::IMMUTABLE, $value)->multiply(-1);
        }

        return $this->setValue($value, $scale+1)->roundToScale($scale);
    }

    /**
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum
     * @param CalcOperation $operation
     * @param int $scale
     * @return ImmutableComplexNumber|static
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function helperMulDivFraction(
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum,
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum,
        CalcOperation $operation,
        int $scale
    ): static|ImmutableComplexNumber
    {
        if ($thatNum instanceof ImmutableFraction) {
            $mulNumerator = match ($operation) {
                CalcOperation::Multiplication => $thatNum->getNumerator(),
                CalcOperation::Division => $thatNum->getDenominator(),
                /** @codeCoverageIgnore  */
                default => throw new IncompatibleObjectState(
                    'Cannot use the MulDiv helper with other operations',
                    'None'
                )
            };
            $mulDenominator = match ($operation) {
                CalcOperation::Multiplication => $thatNum->getDenominator(),
                CalcOperation::Division => $thatNum->getNumerator(),
                /** @codeCoverageIgnore  */
                default => throw new IncompatibleObjectState(
                    'Cannot use the MulDiv helper with other operations',
                    'None'
                )
            };

            return $this->setValue(
                $thisNum->getNumerator()->multiply($mulNumerator),
                $thisNum->getDenominator()->multiply($mulDenominator)
            )->simplify();
        }

        if ($thatNum->isWhole()) {
            $mulNumerator = match ($operation) {
                CalcOperation::Multiplication => $thatNum,
                CalcOperation::Division => 1,
                /** @codeCoverageIgnore  */
                default => throw new IncompatibleObjectState(
                    'Cannot use the MulDiv helper with other operations',
                    'None'
                )
            };
            $mulDenominator = match ($operation) {
                CalcOperation::Multiplication => 1,
                CalcOperation::Division => $thatNum,
                /** @codeCoverageIgnore  */
                default => throw new IncompatibleObjectState(
                    'Cannot use the MulDiv helper with other operations',
                    'None'
                )
            };

            return $this->setValue(
                $thisNum->getNumerator()->multiply($mulNumerator),
                $thisNum->getDenominator()->multiply($mulDenominator)
            )->simplify();
        }

        $value = match ($operation) {
            CalcOperation::Multiplication => $thisNum->asDecimal($scale)->multiply($thatNum),
            CalcOperation::Division => $thisNum->asDecimal($scale)->divide($thatNum),
            /** @codeCoverageIgnore  */
            default => throw new IncompatibleObjectState(
                'Cannot use the MulDiv helper with other operations',
                'None'
            )
        };

        return $value->setMode($this->getMode());
    }

}