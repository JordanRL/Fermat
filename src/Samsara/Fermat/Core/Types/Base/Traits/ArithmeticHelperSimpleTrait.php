<?php

namespace Samsara\Fermat\Core\Types\Base\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\CompoundNumber;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\ArithmeticProvider;
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
     * @param Decimal|Fraction|ComplexNumber $thisNum
     * @param Decimal|Fraction|ComplexNumber $thatNum
     * @param CalcOperation                                             $operation
     *
     * @return string|array
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperAddSub(
        Decimal|Fraction|ComplexNumber $thisNum,
        Decimal|Fraction|ComplexNumber $thatNum,
        CalcOperation                                             $operation
    ): string|array
    {
        if ($thatNum->isEqual(0)) {
            return $this->getValue(NumberBase::Ten);
        }

        if ($this->isReal() xor $thatNum->isReal()) {
            return $this->helperAddSubXor($thisNum, $thatNum, $operation);
        }

        if ($this instanceof Fraction) {
            return $this->helperAddSubFraction($thisNum, $thatNum, $operation);
        } else {
            if ($thatNum instanceof Fraction) {
                $thatNum = $thatNum->asDecimal();
            }
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

            return $value;
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
        Fraction                  $thisNum,
        Decimal|Fraction $thatNum,
        CalcOperation                      $operation
    ): array
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

        return [
            CompoundNumber::Fraction,
            $finalNumerator,
            $finalDenominator
        ];
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
        Decimal|Fraction|ComplexNumber $thisNum,
        Decimal|Fraction|ComplexNumber $thatNum,
        CalcOperation                                             $operation
    ): string|array
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

        if ($newImaginaryPart == '0') {
            return $newRealPart;
        }

        if ($newRealPart == '0') {
            return $newImaginaryPart;
        }

        return [
            CompoundNumber::ComplexNumber,
            $newRealPart,
            $newImaginaryPart
        ];
    }

    /**
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thisNum
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum
     * @param CalcOperation                                             $operation
     * @param int                                                       $scale
     *
     * @return string|array
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperMulDiv(
        Decimal|Fraction|ComplexNumber $thisNum,
        Decimal|Fraction|ComplexNumber $thatNum,
        CalcOperation                                             $operation,
        int                                                       $scale
    ): string|array
    {
        if ($thatNum->isEqual(1)) {
            if ($this instanceof Decimal) {
                return $this->getValue(NumberBase::Ten);
            } else {
                return [
                    CompoundNumber::Fraction,
                    $this->getNumerator(),
                    $this->getDenominator()
                ];
            }
        }

        if ($this instanceof Fraction) {
            return $this->helperMulDivFraction($thisNum, $thatNum, $operation, $scale);
        }

        if ($thatNum instanceof Fraction) {
            $thatNum = $thatNum->asDecimal($scale);
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
            if ($thatNum->isImaginary() && $operation == CalcOperation::Division) {
                $value = ArithmeticProvider::multiply($value, '-1', $scale);
            }

            $value .= 'i';
        } elseif ($thisNum->isImaginary() && $thatNum->isImaginary() && $operation == CalcOperation::Multiplication) {
            $value = ArithmeticProvider::multiply($value, '-1', $scale);
        }

        return $value;
    }

    /**
     * @param ImmutableFraction                                         $thisNum
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $thatNum
     * @param CalcOperation                                             $operation
     * @param int                                                       $scale
     *
     * @return string|array
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperMulDivFraction(
        Fraction                       $thisNum,
        Decimal|Fraction|ComplexNumber $thatNum,
        CalcOperation                  $operation,
        int                            $scale
    ): string|array
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

            return [
                CompoundNumber::Fraction,
                $thisNum->getNumerator()->multiply($mulNumerator),
                $thisNum->getDenominator()->multiply($mulDenominator)
            ];
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

            return [
                CompoundNumber::Fraction,
                $thisNum->getNumerator()->multiply($mulNumerator),
                $thisNum->getDenominator()->multiply($mulDenominator)
            ];
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

        return $value->getValue(NumberBase::Ten);
    }

}