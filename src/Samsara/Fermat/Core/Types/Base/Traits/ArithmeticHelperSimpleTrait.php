<?php

namespace Samsara\Fermat\Core\Types\Base\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Types\Traits\NumberNormalizationTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;

/**
 * @package Samsara\Fermat\Core
 */
trait ArithmeticHelperSimpleTrait
{

    use NumberNormalizationTrait;

    /**
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum
     * @param CalcOperation                                             $operation
     *
     * @return static|ImmutableComplexNumber|ImmutableDecimal|MutableDecimal|ImmutableFraction|MutableFraction
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperAddSub(
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum,
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum,
        CalcOperation                                             $operation
    ): static|ImmutableComplexNumber|ImmutableDecimal|MutableDecimal|ImmutableFraction|MutableFraction
    {
        if ($thatNum->isEqual(0)) {
            return $this;
        }

        if ($this->isReal() xor $thatNum->isReal()) {
            return $this->helperAddSubXor($thisNum, $thatNum, $operation);
        }

        if ($this instanceof Fraction) {
            return $this->helperAddSubFraction($thisNum, $thatNum, $operation);
        } else {
            $value = match ($operation) {
                CalcOperation::Addition => $this->addSelector($thatNum),
                CalcOperation::Subtraction => $this->subtractSelector($thatNum),
                /** @codeCoverageIgnore */
                default => throw new IncompatibleObjectState(
                    'Cannot use the AddSub helper with other operations',
                    'None'
                )
            };

            if ($this->isImaginary()) {
                $value .= 'i';
            }

            $originalScale = $this->getScale();

            return $this->setValue($value)->roundToScale($originalScale);
        }
    }

    /**
     * @param ImmutableFraction                  $thisNum
     * @param ImmutableDecimal|ImmutableFraction $thatNum
     * @param CalcOperation                      $operation
     *
     * @return static
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperAddSubFraction(
        ImmutableFraction                  $thisNum,
        ImmutableDecimal|ImmutableFraction $thatNum,
        CalcOperation                      $operation
    ): static
    {
        if ($this instanceof Decimal) {
            /**
             * This exception can only be thrown in the case of a badly done implementation by a user created inheritance
             * structure, so it is not covered by unit testing.
             */
            /** @codeCoverageIgnore */
            throw new IncompatibleObjectState(
                'Cannot call protected method helperAddSubFraction() from descendent of Decimal',
                'Use a descendent of Fraction instead'
            );
        }

        if ($thatNum instanceof Fraction && $thisNum->getDenominator()->isEqual($thatNum->getDenominator())) {
            $finalDenominator = $thisNum->getDenominator();
            $finalNumerator = match ($operation) {
                CalcOperation::Addition => $thisNum->getNumerator()->add($thatNum->getNumerator()),
                CalcOperation::Subtraction => $thisNum->getNumerator()->subtract($thatNum->getNumerator()),
                /** @codeCoverageIgnore */
                default => throw new IncompatibleObjectState(
                    'Cannot use the AddSub helper with other operations',
                    'None'
                )
            };
        } elseif ($thatNum instanceof Fraction) {
            $finalDenominator = $this->getSmallestCommonDenominator($thatNum);

            [$thisNumerator, $thatNumerator] = $this->getNumeratorsWithSameDenominator($thatNum, $finalDenominator);

            $finalNumerator = match ($operation) {
                CalcOperation::Addition => $thisNumerator->add($thatNumerator),
                CalcOperation::Subtraction => $thisNumerator->subtract($thatNumerator),
                /** @codeCoverageIgnore */
                default => throw new IncompatibleObjectState(
                    'Cannot use the AddSub helper with other operations',
                    'None'
                )
            };
        } else {
            $finalDenominator = $thisNum->getDenominator();
            $finalNumerator = match ($operation) {
                CalcOperation::Addition => $thisNum->getNumerator()->add($thatNum->multiply($finalDenominator)),
                CalcOperation::Subtraction => $thisNum->getNumerator()->subtract($thatNum->multiply($finalDenominator)),
                /** @codeCoverageIgnore */
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
     * @param CalcOperation                                             $operation
     *
     * @return ImmutableComplexNumber|ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperAddSubXor(
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum,
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum,
        CalcOperation                                             $operation
    ): ImmutableDecimal|ImmutableComplexNumber
    {
        [$thisRealPart, $thisImaginaryPart] = static::partSelector($thisNum, $thatNum, 0, $this->getMode());
        [$thatRealPart, $thatImaginaryPart] = static::partSelector($thatNum, $thisNum, 0, $this->getMode());

        $newRealPart = match ($operation) {
            CalcOperation::Addition => $thisRealPart->add($thatRealPart),
            CalcOperation::Subtraction => $thisRealPart->subtract($thatRealPart),
            /** @codeCoverageIgnore */
            default => throw new IncompatibleObjectState(
                'Cannot use the AddSub helper with other operations',
                'None'
            )
        };
        $newImaginaryPart = match ($operation) {
            CalcOperation::Addition => $thisImaginaryPart->add($thatImaginaryPart),
            CalcOperation::Subtraction => $thisImaginaryPart->subtract($thatImaginaryPart),
            /** @codeCoverageIgnore */
            default => throw new IncompatibleObjectState(
                'Cannot use the AddSub helper with other operations',
                'None'
            )
        };

        if ($newImaginaryPart->isEqual(0)) {
            return (new ImmutableDecimal($newRealPart->getValue(NumberBase::Ten)))->setMode($this->getMode());
        }

        if ($newRealPart->isEqual(0)) {
            return (new ImmutableDecimal($newImaginaryPart->getValue(NumberBase::Ten)))->setMode($this->getMode());
        }

        $complex = new ImmutableComplexNumber($newRealPart, $newImaginaryPart);

        return $complex->setMode($this->getMode());
    }

    /**
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum
     * @param CalcOperation                                             $operation
     * @param int                                                       $scale
     *
     * @return ImmutableDecimal|ImmutableComplexNumber|static
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperMulDiv(
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum,
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum,
        CalcOperation                                             $operation,
        int                                                       $scale
    ): static|ImmutableComplexNumber|ImmutableDecimal
    {
        if ($thatNum->isEqual(1)) {
            return $this;
        }

        if ($this instanceof Fraction) {
            return $this->helperMulDivFraction($thisNum, $thatNum, $operation, $scale);
        }

        $value = match ($operation) {
            CalcOperation::Multiplication => $this->multiplySelector($thatNum),
            CalcOperation::Division => $this->divideSelector($thisNum, $thatNum, $scale),
            /** @codeCoverageIgnore */
            default => throw new IncompatibleObjectState(
                'Cannot use the MulDiv helper with other operations',
                'None'
            )
        };

        if ($thisNum->isImaginary() xor $thatNum->isImaginary()) {
            $value .= 'i';

            if ($thatNum->isImaginary() && $operation == CalcOperation::Division) {
                $value = Numbers::make(Numbers::IMMUTABLE, $value)->multiply(-1);
            }
        } elseif ($thisNum->isImaginary() && $thatNum->isImaginary() && $operation == CalcOperation::Multiplication) {
            $value = Numbers::make(Numbers::IMMUTABLE, $value)->multiply(-1);
        }

        return $this->setValue($value, $scale + 1)->roundToScale($scale);
    }

    /**
     * @param ImmutableFraction                                         $thisNum
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum
     * @param CalcOperation                                             $operation
     * @param int                                                       $scale
     *
     * @return ImmutableDecimal|ImmutableComplexNumber|static
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperMulDivFraction(
        ImmutableFraction                                         $thisNum,
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum,
        CalcOperation                                             $operation,
        int                                                       $scale
    ): static|ImmutableComplexNumber|ImmutableDecimal
    {
        if ($this instanceof Decimal) {
            /**
             * This exception can only be thrown in the case of a badly done implementation by a user created inheritance
             * structure, so it is not covered by unit testing.
             */
            /** @codeCoverageIgnore */
            throw new IncompatibleObjectState(
                'Cannot call protected method helperMulDivFraction() from descendent of Decimal',
                'Use a descendent of Fraction instead'
            );
        }

        if ($thatNum instanceof ImmutableFraction) {
            $mulNumerator = match ($operation) {
                CalcOperation::Multiplication => $thatNum->getNumerator(),
                CalcOperation::Division => $thatNum->getDenominator(),
                /** @codeCoverageIgnore */
                default => throw new IncompatibleObjectState(
                    'Cannot use the MulDiv helper with other operations',
                    'None'
                )
            };
            $mulDenominator = match ($operation) {
                CalcOperation::Multiplication => $thatNum->getDenominator(),
                CalcOperation::Division => $thatNum->getNumerator(),
                /** @codeCoverageIgnore */
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
                /** @codeCoverageIgnore */
                default => throw new IncompatibleObjectState(
                    'Cannot use the MulDiv helper with other operations',
                    'None'
                )
            };
            $mulDenominator = match ($operation) {
                CalcOperation::Multiplication => 1,
                CalcOperation::Division => $thatNum,
                /** @codeCoverageIgnore */
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
            /** @codeCoverageIgnore */
            default => throw new IncompatibleObjectState(
                'Cannot use the MulDiv helper with other operations',
                'None'
            )
        };

        return $value->setMode($this->getMode());
    }

}