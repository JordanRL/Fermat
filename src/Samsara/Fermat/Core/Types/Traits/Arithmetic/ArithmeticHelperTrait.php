<?php

namespace Samsara\Fermat\Core\Types\Traits\Arithmetic;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;
use Samsara\Fermat\Complex\ComplexNumbers;
use Samsara\Fermat\Complex\Types\Base\Interfaces\Numbers\ComplexNumberInterface;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;

/**
 *
 */
trait ArithmeticHelperTrait
{

    /**
     * @param $left
     * @param $right
     * @param int $identity
     *
     * @return array
     * @throws IntegrityConstraint
     */
    protected function translateToParts($left, $right, int $identity = 0): array
    {
        switch (gettype($right)) {
            case 'integer':
            case 'double':
                $right = Numbers::make(Numbers::IMMUTABLE, $right);
                break;

            case 'string':
                $right = self::stringSelector($right);
                break;

            case 'object':
                $right = match (get_class($right)) {
                    MutableFraction::class,
                    ImmutableFraction::class => new ImmutableFraction(
                        $right->getNumerator(),
                        $right->getDenominator(),
                        $right->getBase()
                    ),
                    MutableDecimal::class,
                    ImmutableDecimal::class => new ImmutableDecimal(
                        $right->getValue(NumberBase::Ten),
                        $right->getScale(),
                        $right->getBase()
                    ),
                    MutableComplexNumber::class,
                    ImmutableComplexNumber::class => new ImmutableComplexNumber(
                        new ImmutableDecimal(
                            $right->getRealPart()->getValue(NumberBase::Ten),
                            $right->getRealPart()->getScale(),
                            $right->getRealPart()->getBase()
                        ),
                        new ImmutableDecimal(
                            $right->getImaginaryPart()->getValue(NumberBase::Ten),
                            $right->getImaginaryPart()->getScale(),
                            $right->getImaginaryPart()->getBase()
                        ),
                        $right->getScale()
                    ),
                    default => $right
                };
                break;
        }

        [$thatRealPart, $thatImaginaryPart, $right] = self::rightSelector($left, $right, $identity);

        [$thisRealPart, $thisImaginaryPart] = self::leftSelector($left, $identity);

        $thisRealPart->setMode($this->getMode());
        $thisImaginaryPart->setMode($this->getMode());
        $thatRealPart->setMode($this->getMode());
        $thatImaginaryPart->setMode($this->getMode());
        $right->setMode($this->getMode());

        return [$thatRealPart, $thatImaginaryPart, $thisRealPart, $thisImaginaryPart, $right];
    }

    /**
     * @param string $input
     * @return ImmutableComplexNumber|ImmutableDecimal|ImmutableFraction
     * @throws IntegrityConstraint
     */
    protected static function stringSelector(string $input): ImmutableComplexNumber|ImmutableDecimal|ImmutableFraction
    {

        $input = trim($input);
        if (str_contains($input, '/')) {
            $input = Numbers::makeFractionFromString(Numbers::IMMUTABLE_FRACTION, $input);
        } elseif (strrpos($input, '+') || strrpos($input, '-')) {
            $input = ComplexNumbers::make(ComplexNumbers::IMMUTABLE_COMPLEX, $input);
        } else {
            $input = Numbers::make(Numbers::IMMUTABLE, $input);
        }

        return $input;

    }

    /**
     * @param $left
     * @param $right
     * @param $identity
     * @return array
     * @throws IntegrityConstraint
     */
    protected static function rightSelector($left, $right, $identity): array
    {

        if ($right instanceof ComplexNumberInterface) {
            $thatRealPart = $right->getRealPart();
            $thatImaginaryPart = $right->getImaginaryPart();
        } else {
            if ($right instanceof FractionInterface) {
                if ($left instanceof FractionInterface) {
                    $rightPart = $right;

                    $thatRealPart = $right->isReal() ? $rightPart : new ImmutableFraction(new ImmutableDecimal($identity), new ImmutableDecimal(1));
                    $thatImaginaryPart = $right->isImaginary() ? $rightPart : new ImmutableFraction(new ImmutableDecimal($identity.'i'), new ImmutableDecimal(1));
                } else {
                    $rightPart = $right->asDecimal();
                    $right = $right->asDecimal();

                    $thatRealPart = $right->isReal() ? $rightPart : new ImmutableDecimal($identity, $left->getScale());
                    $thatImaginaryPart = $right->isImaginary() ? $rightPart : new ImmutableDecimal($identity.'i', $left->getScale());
                }
            } else {
                $rightPart = $right;

                $thatRealPart = $right->isReal() ? $rightPart : new ImmutableDecimal($identity, $left->getScale());
                $thatImaginaryPart = $right->isImaginary() ? $rightPart : new ImmutableDecimal($identity.'i', $left->getScale());
            }
        }

        return [$thatRealPart, $thatImaginaryPart, $right];

    }

    /**
     * @param $left
     * @param $identity
     * @return array
     * @throws IntegrityConstraint
     */
    protected static function leftSelector($left, $identity): array
    {

        if ($left instanceof ComplexNumberInterface) {
            $thisRealPart = $left->getRealPart();
            $thisImaginaryPart = $left->getImaginaryPart();
        } else {
            $thisRealPart = $left->isReal() ? $left : new ImmutableDecimal($identity, $left->getScale());
            $thisImaginaryPart = $left->isImaginary() ? $left : new ImmutableDecimal($identity.'i', $left->getScale());
        }

        return [$thisRealPart, $thisImaginaryPart];

    }

    /**
     * @return $this|ImmutableComplexNumber
     */
    protected function helperAddSubXor(
        NumberInterface $thisRealPart,
        NumberInterface $thisImaginaryPart,
        NumberInterface $thatRealPart,
        NumberInterface $thatImaginaryPart,
        CalcOperation $operation
    ): static|ImmutableComplexNumber
    {
        $newRealPart = match($operation) {
            CalcOperation::Addition => $thisRealPart->add($thatRealPart),
            CalcOperation::Subtraction => $thisRealPart->subtract($thatRealPart),
        };
        $newImaginaryPart = match($operation) {
            CalcOperation::Addition => $thisImaginaryPart->add($thatImaginaryPart),
            CalcOperation::Subtraction => $thisImaginaryPart->subtract($thatImaginaryPart),
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
     * @return FractionInterface
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function helperAddSubFraction(ImmutableDecimal|ImmutableFraction $num, CalcOperation $operation): FractionInterface
    {
        if ($num instanceof FractionInterface && $this->getDenominator()->isEqual($num->getDenominator())) {
            $finalDenominator = $this->getDenominator();
            $finalNumerator = match ($operation) {
                CalcOperation::Addition => $this->getNumerator()->add($num->getNumerator()),
                CalcOperation::Subtraction => $this->getNumerator()->subtract($num->getNumerator())
            };
        } elseif ($num instanceof FractionInterface) {
            $finalDenominator = $this->getSmallestCommonDenominator($num);

            [$thisNumerator, $thatNumerator] = $this->getNumeratorsWithSameDenominator($num, $finalDenominator);

            $finalNumerator = match ($operation) {
                CalcOperation::Addition => $thisNumerator->add($thatNumerator),
                CalcOperation::Subtraction => $thisNumerator->subtract($thatNumerator),
            };
        } else {
            $finalDenominator = $this->getDenominator();
            $finalNumerator = match ($operation) {
                CalcOperation::Addition => $this->getNumerator()->add($num->multiply($finalDenominator)),
                CalcOperation::Subtraction => $this->getNumerator()->subtract($num->multiply($finalDenominator)),
            };
        }

        return $this->setValue(
            $finalNumerator,
            $finalDenominator
        )->simplify();
    }

}