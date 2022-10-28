<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;

/**
 * @package Samsara\Fermat\Core
 */
trait NumberNormalizationTrait
{

    /**
     * @param string                 $inputClass
     * @param Fraction|ComplexNumber $inputObject
     * @param CalcMode|null          $mode
     * @param int|null               $scale
     *
     * @return ImmutableFraction|ImmutableComplexNumber
     * @throws IntegrityConstraint
     */
    protected static function buildTwoPartNumber(
        string                 $inputClass,
        Fraction|ComplexNumber $inputObject,
        ?CalcMode              $mode,
        ?int                   $scale
    ): ImmutableFraction|ImmutableComplexNumber
    {
        $inputA = match ($inputClass) {
            ImmutableFraction::class => $inputObject->getNumerator(),
            ImmutableComplexNumber::class => $inputObject->getRealPart()
        };
        $inputB = match ($inputClass) {
            ImmutableFraction::class => $inputObject->getDenominator(),
            ImmutableComplexNumber::class => $inputObject->getImaginaryPart()
        };

        return (new $inputClass(
            new ImmutableDecimal(
                $inputA->getValue(NumberBase::Ten),
                $scale ?? $inputA->getScale()
            ),
            new ImmutableDecimal(
                $inputB->getValue(NumberBase::Ten),
                $scale ?? $inputB->getScale()
            ),
            $inputClass === ImmutableComplexNumber::class ? $inputObject->getScale() : NumberBase::Ten
        ))->setMode($mode);
    }

    /**
     * @param Fraction|Decimal|ComplexNumber $object
     * @param CalcMode|null                  $mode
     * @param int|null                       $scale
     *
     * @return ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber
     * @throws IntegrityConstraint
     */
    protected static function normalizeObject(
        Fraction|Decimal|ComplexNumber $object,
        ?CalcMode                      $mode,
        ?int                           $scale = null
    ): ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber
    {
        return match (true) {
            $object instanceof Fraction => self::buildTwoPartNumber(
                ImmutableFraction::class,
                $object,
                $mode,
                $scale
            ),
            $object instanceof Decimal => (new ImmutableDecimal(
                $object->getValue(NumberBase::Ten),
                $scale ?? $object->getScale(),
                $object->getBase()
            ))->setMode($mode),
            $object instanceof ComplexNumber => self::buildTwoPartNumber(
                ImmutableComplexNumber::class,
                $object,
                $mode,
                $scale
            ),
            default => throw new IntegrityConstraint(
                'Cannot normalize provided object.',
                'When providing custom value classes, extend the abstract classes.'
            )
        };
    }

    /**
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $partThis
     * @param ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $compareTo
     * @param int                                                       $identity
     * @param CalcMode|null                                             $mode
     * @param int|null                                                  $scale
     *
     * @return ImmutableDecimal[]
     * @throws IntegrityConstraint
     */
    protected static function partSelector(
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $partThis,
        ImmutableDecimal|ImmutableFraction|ImmutableComplexNumber $compareTo,
        int                                                       $identity,
        ?CalcMode                                                 $mode,
        ?int                                                      $scale = null
    ): array
    {

        if ($partThis->isComplex()) {
            $realPart = self::normalizeObject($partThis->getRealPart(), $mode, $scale);
            $imaginaryPart = self::normalizeObject($partThis->getImaginaryPart(), $mode, $scale);
        } elseif ($partThis->isReal()) {
            $realPart = match (true) {
                $partThis instanceof Fraction => match (true) {
                    $compareTo instanceof Fraction => self::normalizeObject($partThis, $mode, $scale),
                    default => self::normalizeObject($partThis->asDecimal(), $mode, $scale)
                },
                $partThis instanceof Decimal => self::normalizeObject($partThis, $mode, $scale)
            };
            $imaginaryPart = match (true) {
                $compareTo instanceof Fraction => (new ImmutableFraction(
                    new ImmutableDecimal(
                        $identity . 'i',
                        $scale ?? $compareTo->getNumerator()->getScale()
                    ),
                    new ImmutableDecimal(
                        1,
                        $scale ?? $compareTo->getDenominator()->getScale()
                    ),
                    $compareTo->getBase()
                ))->setMode($mode),
                default => (new ImmutableDecimal(
                    $identity . 'i',
                    $scale ?? $compareTo->getScale()
                ))->setMode($mode)
            };
        } else {
            $realPart = match (true) {
                $compareTo instanceof Fraction => (new ImmutableFraction(
                    new ImmutableDecimal(
                        $identity,
                        $scale ?? $compareTo->getNumerator()->getScale()
                    ),
                    new ImmutableDecimal(
                        1,
                        $scale ?? $compareTo->getDenominator()->getScale()
                    ),
                    $compareTo->getBase()
                ))->setMode($mode),
                default => (new ImmutableDecimal(
                    $identity,
                    $scale ?? $compareTo->getScale()
                ))->setMode($mode)
            };
            $imaginaryPart = match (true) {
                $partThis instanceof Fraction => match (true) {
                    $compareTo instanceof Fraction => self::normalizeObject($partThis, $mode, $scale),
                    default => self::normalizeObject($partThis->asDecimal(), $mode, $scale)
                },
                $partThis instanceof Decimal => self::normalizeObject($partThis, $mode, $scale)
            };
        }

        return [$realPart, $imaginaryPart];

    }

    /**
     * @param string        $input
     * @param CalcMode|null $mode
     *
     * @return ImmutableComplexNumber|ImmutableDecimal|ImmutableFraction
     * @throws IntegrityConstraint
     */
    protected static function stringSelector(string $input, ?CalcMode $mode): ImmutableComplexNumber|ImmutableDecimal|ImmutableFraction
    {

        $input = trim($input);
        if (str_contains($input, '/')) {
            $input = Numbers::makeFractionFromString(Numbers::IMMUTABLE_FRACTION, $input)->setMode($mode);
        } elseif (strrpos($input, '+') || strrpos($input, '-')) {
            $input = ImmutableComplexNumber::makeFromString($input)->setMode($mode);
        } else {
            $input = (new ImmutableDecimal($input))->setMode($mode);
        }

        return $input;

    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $right
     *
     * @return ImmutableFraction[]|ImmutableDecimal[]|ImmutableComplexNumber[]
     * @throws IntegrityConstraint
     */
    protected function translateToObjects(string|int|float|Decimal|Fraction|ComplexNumber $right): array
    {
        $normalizedLeft = self::normalizeObject($this, $this->getMode());

        $normalizedRight = match (gettype($right)) {
            'integer', 'double' => (new ImmutableDecimal($right))->setMode($this->getMode()),
            'object' => self::normalizeObject($right, $this->getMode()),
            default => self::stringSelector($right, $this->getMode()),
        };

        if ($normalizedLeft instanceof ImmutableDecimal && $normalizedRight instanceof ImmutableFraction) {
            $normalizedRight = $normalizedRight->asDecimal($this->getScale())->setMode($this->getMode());
        }

        return [$normalizedLeft, $normalizedRight];
    }
}