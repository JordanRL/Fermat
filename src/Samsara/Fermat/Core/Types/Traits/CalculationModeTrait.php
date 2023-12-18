<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Provider\CalculationModeProvider;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;

/**
 * @package Samsara\Fermat\Core
 */
trait CalculationModeTrait
{
    /** @var CalcMode|null */
    protected ?CalcMode $calcMode = null;

    /**
     * Returns the enum setting for this object's calculation mode. If this is null, then the default mode in the
     * CalculationModeProvider at the time a calculation is performed will be used.
     *
     * @return CalcMode|null
     */
    public function getMode(): ?CalcMode
    {

        return $this->calcMode;

    }

    /**
     * Returns the mode that this object would use at the moment, accounting for all values and defaults.
     *
     * @return CalcMode
     */
    public function getResolvedMode(): CalcMode
    {

        return $this->calcMode ?? CalculationModeProvider::getCurrentMode();

    }

    /**
     * Allows you to set a mode on a number to select the calculation methods. If this is null, then the default mode in the
     * CalculationModeProvider at the time a calculation is performed will be used.
     *
     * @param CalcMode|null $mode
     *
     * @return static
     */
    public function setMode(?CalcMode $mode): static
    {
        $this->calcMode = $mode;

        return $this;
    }

    public function performOperationsFromArray(
        array $operations,
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableFraction|ImmutableFraction
    {
        if (!($this instanceof MutableDecimal) && $this instanceof Decimal) {
            $thisMutable = new MutableDecimal($this->getValue(NumberBase::Ten), $scale ?? $this->getScale());
        } elseif (!($this instanceof MutableFraction) && $this instanceof Fraction) {
            $thisMutable = new MutableFraction($this->getNumerator(), $this->getDenominator());
        } else {
            $thisMutable = $this;
        }

        $thisMutable->scale = $scale ?? $this->getScale();

        foreach ($operations as $operation) {
            if (is_array($operation)) {
                $calOp = $operation[0];
                $calVal = $operation[1] ?? false;
            } else {
                $calOp = $operation;
                $calVal = false;
            }

            if (!$calVal) {
                match ($calOp) {
                    // Arithmetic ops
                    CalcOperation::SquareRoot => $thisMutable->sqrt($scale),

                    // Regular trig ops
                    CalcOperation::Sin => $thisMutable->sin($scale),
                    CalcOperation::Cos => $thisMutable->cos($scale),
                    CalcOperation::Tan => $thisMutable->tan($scale),
                    CalcOperation::Cot => $thisMutable->cot($scale),
                    CalcOperation::Sec => $thisMutable->sec($scale),
                    CalcOperation::Csc => $thisMutable->csc($scale),

                    // Hyperbolic trig ops
                    CalcOperation::SinH => $thisMutable->sinh($scale),
                    CalcOperation::CosH => $thisMutable->cosh($scale),
                    CalcOperation::TanH => $thisMutable->tanh($scale),
                    CalcOperation::CotH => $thisMutable->coth($scale),
                    CalcOperation::SecH => $thisMutable->sech($scale),
                    CalcOperation::CscH => $thisMutable->csch($scale),

                    // Inverse trig ops
                    CalcOperation::ArcSin => $thisMutable->arcsin($scale),
                    CalcOperation::ArcCos => $thisMutable->arccos($scale),
                    CalcOperation::ArcTan => $thisMutable->arctan($scale),
                    CalcOperation::ArcCot => $thisMutable->arccot($scale),
                    CalcOperation::ArcSec => $thisMutable->arcsec($scale),
                    CalcOperation::ArcCsc => $thisMutable->arccsc($scale),

                    // Logarithmic ops
                    CalcOperation::Ln => $thisMutable->ln($scale),
                    CalcOperation::Exp => $thisMutable->exp($scale),
                    CalcOperation::Log10 => $thisMutable->log10($scale),

                    // Integer ops
                    CalcOperation::Factorial => $thisMutable->factorial(),
                    CalcOperation::DoubleFactorial => $thisMutable->doubleFactorial(),
                    CalcOperation::SubFactorial => $thisMutable->subFactorial(),

                    // Utility ops
                    CalcOperation::Abs => $thisMutable->abs(),

                    // Missing value argument
                    default => throw new IntegrityConstraint(
                        'A value is needed for CalcOperation: '.$calOp,
                        'Ensure that when you perform operations from array, you provide values for the operations that require them.'
                    )
                };
            } else {
                match ($calOp) {
                    // Arithmetic ops
                    CalcOperation::Addition => $thisMutable->addInternal($this, $calVal),
                    CalcOperation::Subtraction => $thisMutable->subtractInternal($this, $calVal),
                    CalcOperation::Multiplication => $thisMutable->multiplyInternal($this, $calVal, $scale),
                    CalcOperation::Division => $thisMutable->divideInternal($this, $calVal, $scale),
                    CalcOperation::Power => $thisMutable->pow($calVal, $scale),

                    // Utility ops
                    CalcOperation::Modulo => $thisMutable->modulo($calVal),
                    CalcOperation::ContinuousModulo => $thisMutable->continuousModulo($calVal),

                    // Extra value argument
                };
            }
        }

        return $this->setValue($thisMutable->getValue());
    }
}