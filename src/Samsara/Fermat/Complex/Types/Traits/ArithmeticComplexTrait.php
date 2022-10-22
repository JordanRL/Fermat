<?php


namespace Samsara\Fermat\Complex\Types\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\ArithmeticProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticNativeTrait;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticScaleTrait;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticSelectionTrait;
use Samsara\Fermat\Core\Types\Traits\InputNormalizationTrait;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;
use Samsara\Fermat\Expressions\Values\Algebra\PolynomialFunction;
use Samsara\Fermat\Coordinates\Values\PolarCoordinate;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 *
 */
trait ArithmeticComplexTrait
{

    use InputNormalizationTrait;

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     *
     * @return MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
     * @throws IntegrityConstraint
     */
    public function add(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {

        $scale = $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);
        [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 0, $this->getMode());
        [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thisNum, 0, $this->getMode());

        $newRealPart = $thisRealPart->add($thatRealPart);
        $newImaginaryPart = $thisImaginaryPart->add($thatImaginaryPart);

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
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     *
     * @return MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
     */
    public function subtract(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        $scale = $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);
        [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 0, $this->getMode());
        [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thisNum, 0, $this->getMode());

        $newRealPart = $thisRealPart->subtract($thatRealPart);
        $newImaginaryPart = $thisImaginaryPart->subtract($thatImaginaryPart);

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
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    public function multiply(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        $scale = $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);
        [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 1, $this->getMode());
        [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thisNum, 1, $this->getMode());

        if ($thatNum->isComplex()) {
            $foiled = PolynomialFunction::createFromFoil([
                $thisRealPart,
                $thisImaginaryPart
            ], [
                $thatRealPart,
                $thatImaginaryPart
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
        } else {
            $value1 = $thisRealPart->multiply($thatNum);
            $value2 = $thisImaginaryPart->multiply($thatNum);

            $newRealPart = $value1->isReal() ? $value1 : $value2;
            $newImaginaryPart = $value1->isImaginary() ? $value1 : $value2;
        }

        if (!$newRealPart->isEqual(0) && !$newImaginaryPart->isEqual(0)) {
            return $this->setValue($newRealPart, $newImaginaryPart)->roundToScale($scale);
        }

        return (new ImmutableDecimal(0))->setMode($this->getMode());
    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @param int|null $scale
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function divide(
        string|int|float|Decimal|Fraction|ComplexNumber $num,
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {

        $scale = $scale ?? $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);
        [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 1, $this->getMode());
        [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thisNum, 1, $this->getMode());

        if ($thatNum->isComplex()) {
            $intScale = $scale + 2;
            $denominator = $thatRealPart->roundToScale($intScale)->pow(2)->add($thatImaginaryPart->asReal()->roundToScale($intScale)->pow(2));

            $partA = $thisRealPart->roundToScale($intScale)->multiply($thatRealPart->roundToScale($intScale))
                ->add($thisImaginaryPart->asReal()->roundToScale($intScale)->multiply($thatImaginaryPart->asReal()->roundToScale($intScale)))
                ->divide($denominator, $intScale);
            $partB = $thisImaginaryPart->asReal()->roundToScale($intScale)->multiply($thatRealPart->roundToScale($intScale))
                ->subtract($thisRealPart->roundToScale($intScale)->multiply($thatImaginaryPart->asReal()->roundToScale($intScale)))
                ->divide($denominator, $intScale)
                ->multiply('1i');
        } else {
            $partA = $thisRealPart->divide($thatNum, $scale+2);
            $partB = $thisImaginaryPart->divide($thatNum, $scale+2);
        }

        $newRealPart = Numbers::makeZero($scale)->setMode($this->getMode());
        $newImaginaryPart = Numbers::makeZero($scale)->setMode($this->getMode());

        $newRealPart = $partA->isReal() ? $newRealPart->add($partA) : $newRealPart->add($partB);
        $newImaginaryPart = $partA->isImaginary() ? $newImaginaryPart->add($partA) : $newImaginaryPart->add($partB);

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
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function pow(
        string|int|float|Decimal|Fraction|ComplexNumber $num,
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        $scale = $scale ?? $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);
        [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thisNum, 1, $this->getMode());

        /*
         * If the exponent is a real, positive integer, then we can just do repeated multiplication faster
         */
        if ($thatNum->isReal() && $thatNum->isNatural() && $thatNum->isPositive()) {
            $newValue = $thisNum;

            for ($i=0;$thatNum->isGreaterThan($i);$i++) {
                $newValue = $newValue->multiply($thisNum);
            }

            if ($newValue instanceof ComplexNumber) {
                return $this->setValue($newValue->getRealPart(), $newValue->getImaginaryPart());
            } else {
                return $newValue;
            }
        }

        $internalScale = ($this->getScale() > $thatNum->getScale()) ? $this->getScale() : $thatNum->getScale();
        $internalScale += 5;

        $thisRho = $this->getDistanceFromOrigin()->truncateToScale($internalScale);
        $thisTheta = $this->getPolarAngle()->truncateToScale($internalScale);

        $e = Numbers::makeE($internalScale);

        $coef = $thisRho->pow($thatRealPart)->multiply($e->pow($thisTheta->multiply($thatImaginaryPart->asReal())->multiply(-1)));

        /** @var ImmutableDecimal $trigArg */
        $trigArg = $thisRho->ln()->multiply($thatImaginaryPart->asReal())->add($thatRealPart->multiply($thisTheta));

        $newRealPart = $trigArg->cos($internalScale)->multiply($coef);
        $newImaginaryPart = $trigArg->sin($internalScale)->multiply($coef)->multiply('i');

        if ($newRealPart->absValue() === '0' xor $newImaginaryPart->absValue() === '0') {
            return $newRealPart->absValue() === '0' ? $newRealPart : $newImaginaryPart;
        }

        if ($newRealPart->absValue() === '0' && $newImaginaryPart->absValue() === '0') {
            return Numbers::makeZero();
        }

        return $this->setValue($newRealPart, $newImaginaryPart)->roundToScale($scale);

    }

    /**
     * @param int|null $scale
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    public function sqrt(
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {

        $scale = $scale ?? $this->getScale();

        $rho = $this->getDistanceFromOrigin();
        $theta = $this->getPolarAngle();

        if (!$rho->isEqual(0)) {
            $rho = ArithmeticProvider::squareRoot($rho->getAsBaseTenRealNumber(), $scale);
        }

        $theta = $theta->divide(2);

        $newPolar = new PolarCoordinate($rho, $theta);
        $newCartesian = $newPolar->asCartesian();

        $newRealPart = $newCartesian->getAxis('x');
        $newImaginaryPart = $newCartesian->getAxis('y');

        if (!$newRealPart->isEqual(0) && !$newImaginaryPart->isEqual(0)) {
            return $this->setValue($newRealPart, $newImaginaryPart);
        }

        $newValue = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

        return new ImmutableDecimal($newValue->getValue(), $scale);

    }

}