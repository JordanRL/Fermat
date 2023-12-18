<?php

namespace Samsara\Fermat\Core\Types\Base\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\CompoundNumber;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;

trait ArithmeticInternalTrait
{

    protected function addInternal(
        Decimal|Fraction|ComplexNumber $thisNum,
        Decimal|Fraction|ComplexNumber $thatNum
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        if ($thatNum->isComplex()) {
            return $thatNum->add($thisNum);
        }

        $value = $this->helperAddSub($thisNum, $thatNum, CalcOperation::Addition);

        return $this->normalizeReturnInternal($value);
    }

    protected function subtractInternal(
        Decimal|Fraction|ComplexNumber $thisNum,
        Decimal|Fraction|ComplexNumber $thatNum
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        if ($thatNum->isComplex()) {
            return $thatNum->multiply(-1)->add($thisNum);
        }

        $value = $this->helperAddSub($thisNum, $thatNum, CalcOperation::Subtraction);

        return $this->normalizeReturnInternal($value);
    }

    protected function multiplyInternal(
        Decimal|Fraction|ComplexNumber $thisNum,
        Decimal|Fraction|ComplexNumber $thatNum,
        int                            $scale
    )
    {
        if ($thatNum->isComplex()) {
            return $thatNum->multiply($thisNum);
        }

        $internalScale = ($thisNum->getScale() > $thatNum->getScale()) ? $thisNum->getScale() : $thatNum->getScale();
        $internalScale = ($internalScale > $scale) ? $internalScale : $scale;

        $value = $this->helperMulDiv($thisNum, $thatNum, CalcOperation::Multiplication, $internalScale+1);

        return $this->normalizeReturnInternal($value, $scale);
    }

    protected function divideInternal(
        Decimal|Fraction|ComplexNumber $thisNum,
        Decimal|Fraction|ComplexNumber $thatNum,
        int                                                       $scale
    )
    {
        if ($thatNum->isComplex()) {
            [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 0, $this->getMode());
            /** @noinspection PhpUnhandledExceptionInspection */
            $thisComplex = (new ImmutableComplexNumber($thisRealPart, $thisImaginaryPart))->setMode($this->getMode());
            return $thisComplex->divide($thatNum);
        }

        $internalScale = $scale + 1;

        $value = $this->helperMulDiv($thisNum, $thatNum, CalcOperation::Division, $internalScale);

        return $this->normalizeReturnInternal($value, $scale);
    }

    protected function normalizeReturnInternal(
        string|array $returnValue,
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableFraction|ImmutableFraction|MutableComplexNumber|ImmutableComplexNumber|static
    {
        $scale = $scale ?? $this->getScale();

        if (is_string($returnValue) && $this instanceof Decimal) {
            return $this->setValue($returnValue)->roundToScale($scale);
        } elseif (is_string($returnValue) && $this instanceof Fraction) {
            return (new ImmutableDecimal($returnValue, $scale+2))->setMode($this->getMode())->roundToScale($scale);
        } else {
            if ($this instanceof Fraction && $returnValue[0] == CompoundNumber::Fraction) {
                return $this->setValue(
                    $returnValue[1]->setMode($this->getMode()),
                    $returnValue[2]->setMode($this->getMode())
                )->simplify();
            } else {
                return match ($returnValue[0]) {
                    CompoundNumber::ComplexNumber => (new ImmutableComplexNumber($returnValue[1], $returnValue[2]))->setMode($this->getMode()),
                    CompoundNumber::Fraction => (new ImmutableFraction($returnValue[1], $returnValue[2]))->setMode($this->getMode())->simplify()
                };
            }
        }
    }

}