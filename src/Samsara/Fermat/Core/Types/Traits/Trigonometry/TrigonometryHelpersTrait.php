<?php

namespace Samsara\Fermat\Core\Types\Traits\Trigonometry;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\SequenceProvider;
use Samsara\Fermat\Core\Provider\SeriesProvider;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
trait TrigonometryHelpersTrait
{

    /**
     * @param int|null $scale
     * @param bool $round
     * @param CalcOperation $operation
     * @return static|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    protected function helperBasicTrigSelector(?int $scale, bool $round, CalcOperation $operation): ImmutableDecimal|MutableDecimal|static
    {
        $finalScale = $scale ?? $this->getScale();

        $answer = match ($operation) {
            CalcOperation::Sin => $this->sinSelector($scale),
            CalcOperation::Cos => $this->cosSelector($scale),
            CalcOperation::Tan => $this->tanSelector($scale),
            CalcOperation::Sec => $this->secSelector($scale),
            CalcOperation::Csc => $this->cscSelector($scale),
            CalcOperation::Cot => $this->cotSelector($scale),
            CalcOperation::SinH => $this->sinhSelector($scale),
            CalcOperation::CosH => $this->coshSelector($scale),
            CalcOperation::TanH => $this->tanhSelector($scale),
            CalcOperation::SecH => $this->sechSelector($scale),
            CalcOperation::CscH => $this->cschSelector($scale),
            CalcOperation::CotH => $this->cothSelector($scale),
        };

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    /**
     * @param CalcOperation $operation
     * @param string $piDivTwoReturn
     * @param string $piReturn
     * @param string $threePiDivTwoReturn
     * @param string $twoPiReturn
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws \ReflectionException
     * @throws IncompatibleObjectState
     * @throws OptionalExit
     */
    protected function helperSinCosScale(
        CalcOperation $operation,
        string $piDivTwoReturn,
        string $piReturn,
        string $threePiDivTwoReturn,
        string $twoPiReturn,
        ?int $scale
    )
    {
        $scale = $scale ?? $this->getScale();
        $modScale = ($scale > $this->getScale()) ? $scale : $this->getScale();
        $intScale = $scale+3;
        $modScale = $modScale+$this->numberOfIntDigits()+2;

        $thisNum = Numbers::make(
            Numbers::IMMUTABLE,
            $this->getValue(NumberBase::Ten),
            $modScale
        );

        $twoPi = Numbers::make2Pi($modScale + 2);
        $pi = Numbers::makePi( $modScale + 2);
        $piDivTwo = $pi->divide(2);
        $threePiDivTwo = $piDivTwo->multiply(3);
        $modulo = $thisNum->continuousModulo($twoPi);

        $answer = match ($modulo->truncate($scale)->getValue()) {
            $twoPi->truncate($scale)->getValue(),
            '0' => $twoPiReturn,
            $piDivTwo->truncate($scale)->getValue() => $piDivTwoReturn,
            $pi->truncate($scale)->getValue() => $piReturn,
            $threePiDivTwo->truncate($scale)->getValue() => $threePiDivTwoReturn,
            default => false
        };

        if ($answer !== false) {
            return $answer;
        }

        $negOne = Numbers::make(Numbers::IMMUTABLE, -1, $intScale);
        $one = Numbers::make(Numbers::IMMUTABLE, 1, $intScale);

        $answer = SeriesProvider::maclaurinSeries(
            $modulo,
            function ($n) use ($negOne, $one) {

                return $n % 2 ? $negOne : $one;
            },
            function ($n) use ($intScale, $operation) {
                return match ($operation) {
                    CalcOperation::Cos => SequenceProvider::nthEvenNumber($n, $intScale),
                    CalcOperation::Sin => SequenceProvider::nthOddNumber($n, $intScale)
                };
            },
            function ($n) use ($intScale, $operation) {
                return match ($operation) {
                    CalcOperation::Cos => SequenceProvider::nthEvenNumber($n, $intScale)->factorial(),
                    CalcOperation::Sin => SequenceProvider::nthOddNumber($n, $intScale)->factorial()
                };
            },
            0,
            $scale+2
        );

        return $answer->getAsBaseTenRealNumber();
    }

}