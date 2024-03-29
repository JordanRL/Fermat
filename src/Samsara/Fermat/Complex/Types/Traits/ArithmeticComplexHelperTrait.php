<?php

namespace Samsara\Fermat\Complex\Types\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Coordinates\Values\PolarCoordinate;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Traits\NumberNormalizationTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;
use Samsara\Fermat\Expressions\Values\Algebra\PolynomialFunction;

/**
 * @package Samsara\Fermat\Complex
 */
trait ArithmeticComplexHelperTrait
{

    use NumberNormalizationTrait;

    /**
     * @param MutableDecimal|ImmutableDecimal|MutableFraction|ImmutableFraction $newRealPart
     * @param MutableDecimal|ImmutableDecimal|MutableFraction|ImmutableFraction $newImaginaryPart
     * @param int                                                               $scale
     *
     * @return static|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal
     * @throws IntegrityConstraint
     */
    protected function helperComplexAddSub(
        MutableDecimal|ImmutableDecimal|MutableFraction|ImmutableFraction $newRealPart,
        MutableDecimal|ImmutableDecimal|MutableFraction|ImmutableFraction $newImaginaryPart,
        int                                                               $scale
    ): MutableComplexNumber|ImmutableDecimal|ImmutableComplexNumber|static
    {
        if ($newRealPart->isEqual(0) xor $newImaginaryPart->isEqual(0)) {
            $newNum = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

            return new ImmutableDecimal($newNum->getValue(), $scale);
        }

        if ($newRealPart->isEqual(0) && $newImaginaryPart->isEqual(0)) {
            return new ImmutableDecimal('0', $scale);
        }

        return $this->setValue($newRealPart, $newImaginaryPart)->roundToScale($scale);
    }

    /**
     * @param ImmutableDecimal|ImmutableFraction $thisRealPart
     * @param ImmutableDecimal|ImmutableFraction $thisImaginaryPart
     * @param ImmutableDecimal|ImmutableFraction $thatRealPart
     * @param ImmutableDecimal|ImmutableFraction $thatImaginaryPart
     * @param int                                $scale
     *
     * @return static|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|ImmutableFraction|MutableDecimal|MutableFraction
     * @throws IntegrityConstraint
     */
    protected function helperMulComplex(
        ImmutableDecimal|ImmutableFraction $thisRealPart,
        ImmutableDecimal|ImmutableFraction $thisImaginaryPart,
        ImmutableDecimal|ImmutableFraction $thatRealPart,
        ImmutableDecimal|ImmutableFraction $thatImaginaryPart,
        int                                $scale
    ): ImmutableFraction|MutableComplexNumber|ImmutableDecimal|MutableFraction|ImmutableComplexNumber|MutableDecimal|static
    {
        $foiled = PolynomialFunction::createFromFoil([
            $thisRealPart,
            $thisImaginaryPart,
        ], [
            $thatRealPart,
            $thatImaginaryPart,
        ]);

        $parts = $foiled->describeShape();

        $value = Numbers::makeZero()->setMode($this->getMode());

        foreach ($parts as $part) {
            $value = $value->add($part);
        }

        if ($value instanceof ComplexNumber) {
            return $this->setValue($value->getRealPart(), $value->getImaginaryPart())->roundToScale($scale);
        }

        return $value->roundToScale($scale);
    }

    /**
     * @param MutableDecimal|ImmutableDecimal|MutableFraction|ImmutableFraction $partA
     * @param MutableDecimal|ImmutableDecimal|MutableFraction|ImmutableFraction $partB
     * @param int                                                               $scale
     *
     * @return static|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|ImmutableFraction|MutableDecimal|MutableFraction
     * @throws IntegrityConstraint
     */
    protected function helperMulDivPowReturn(
        MutableDecimal|ImmutableDecimal|MutableFraction|ImmutableFraction $partA,
        MutableDecimal|ImmutableDecimal|MutableFraction|ImmutableFraction $partB,
        int                                                               $scale
    ): ImmutableFraction|MutableComplexNumber|ImmutableDecimal|MutableFraction|ImmutableComplexNumber|MutableDecimal|static
    {
        $newRealPart = $partA->isReal() ? $partA : $partB;
        $newImaginaryPart = $partA->isImaginary() ? $partA : $partB;

        $newRealPart = $newRealPart->roundToScale($scale);
        $newImaginaryPart = $newImaginaryPart->roundToScale($scale);

        if ($newRealPart->isEqual(0) xor $newImaginaryPart->isEqual(0)) {
            return match ($newRealPart->isEqual(0)) {
                true => $newImaginaryPart,
                false => $newRealPart
            };
        } elseif ($newRealPart->isEqual(0) && $newImaginaryPart->isEqual(0)) {
            return (new ImmutableDecimal(0, $this->getScale()))->setMode($this->getMode());
        }

        return $this->setValue($newRealPart, $newImaginaryPart)->roundToScale($scale);
    }

    /**
     * @param ImmutableDecimal|ImmutableFraction $thatRealPart
     * @param ImmutableDecimal|ImmutableFraction $thatImaginaryPart
     * @param int                                $scale
     *
     * @return ImmutableDecimal[]
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws OptionalExit
     */
    protected function helperPowPolar(
        ImmutableDecimal|ImmutableFraction $thatRealPart,
        ImmutableDecimal|ImmutableFraction $thatImaginaryPart,
        int                                $scale
    ): array
    {
        $internalScale = $scale;

        $thisNum = new ImmutableComplexNumber($this->getRealPart(), $this->getImaginaryPart(), $internalScale);
        $thatRealPart = $thatRealPart->truncateToScale($internalScale);
        $thatImaginaryPart = $thatImaginaryPart->truncateToScale($internalScale);

        $thisRho = $thisNum->getDistanceFromOrigin();
        $thisTheta = $thisNum->getPolarAngle();

        $e = Numbers::makeE($internalScale);

        $coefRhoPart = $thisRho->pow($thatRealPart);
        $coefThetaPart = $e->pow($thisTheta->multiply($thatImaginaryPart->asReal())->multiply(-1));

        $coef = $coefRhoPart->multiply($coefThetaPart);

        $trigArgLnPart = $thisRho->ln($internalScale)->multiply($thatImaginaryPart->asReal());
        $trigArgThetaPart = $thatRealPart->multiply($thisTheta);

        $trigArg = $trigArgLnPart->add($trigArgThetaPart);

        /** @var ImmutableDecimal $newRealPart */
        $newRealPart = $trigArg->cos($internalScale)->multiply($coef);
        /** @var ImmutableDecimal $newImaginaryPart */
        $newImaginaryPart = $trigArg->sin($internalScale)->multiply($coef)->multiply('1i');

        return [$newRealPart, $newImaginaryPart];
    }

    /**
     * @param ImmutableComplexNumber|ImmutableDecimal|ImmutableFraction $thisNum
     * @param ImmutableDecimal                                          $rotation
     *
     * @return ImmutableDecimal[]
     * @throws IntegrityConstraint
     */
    protected function helperPowPolarRotate(
        ImmutableComplexNumber|ImmutableDecimal|ImmutableFraction $thisNum,
        ImmutableDecimal                                          $rotation
    ): array
    {
        $rho = $thisNum->getDistanceFromOrigin();
        $theta = $thisNum->getPolarAngle();

        $rho = $rho->pow($rotation);
        $theta = $theta->multiply($rotation);

        $newPolar = new PolarCoordinate($rho, $theta);
        $newCartesian = $newPolar->asCartesian();

        $newRealPart = $newCartesian->getAxis('x');
        $newImaginaryPart = $newCartesian->getAxis('y')->multiply('1i');
        return [$newRealPart, $newImaginaryPart];
    }

    /**
     * @param ImmutableComplexNumber|ImmutableDecimal|ImmutableFraction $thisNum
     * @param ImmutableDecimal                                          $roots
     * @param int                                                       $period
     * @param int                                                       $scale
     *
     * @return ImmutableDecimal[]
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperRootsPolarRotate(
        ImmutableComplexNumber|ImmutableDecimal|ImmutableFraction $thisNum,
        ImmutableDecimal                                          $roots,
        int                                                       $period,
        int                                                       $scale
    ): array
    {
        $intScale = $scale + $roots->asInt();

        $rho = $thisNum->getDistanceFromOrigin();
        $theta = $thisNum->getPolarAngle();

        if (!$rho->isEqual(0)) {
            $rho = $rho->pow((new ImmutableDecimal('1', $intScale))->setMode($this->getMode())->divide(3));
        }

        $theta = $theta->divide($roots, $intScale);

        if ($period > 0) {
            $period = Numbers::makeTau($intScale)->setMode($thisNum->getMode())->multiply($period)->divide($roots, $intScale);
            $theta = $theta->add($period);
        }

        $newPolar = new PolarCoordinate($rho, $theta);
        $newCartesian = $newPolar->asCartesian($intScale);

        $newRealPart = $newCartesian->getAxis('x');
        $newImaginaryPart = $newCartesian->getAxis('y')->multiply('1i');
        return [$newRealPart, $newImaginaryPart];
    }

    /**
     * @param ImmutableComplexNumber $thisNum
     * @param int                    $scale
     *
     * @return ImmutableDecimal[]
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperSquareRoot(
        ImmutableComplexNumber $thisNum,
        int                    $scale
    ): array
    {
        $intScale = $scale + 2;

        $rho = $thisNum->getDistanceFromOrigin();

        $partA = $rho->add($thisNum->getRealPart())->divide(2)->sqrt($intScale);
        $partB = $rho->subtract($thisNum->getRealPart())->divide(2)->sqrt($intScale);

        if ($thisNum->getImaginaryPart()->isNegative()) {
            $partB = $partB->multiply('-1i');
        } else {
            $partB = $partB->multiply('1i');
        }

        return $partA->isReal() ? [$partA, $partB] : [$partB, $partA];
    }

}