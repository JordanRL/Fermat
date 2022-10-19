<?php


namespace Samsara\Fermat\Complex\Types\Traits;

use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\ArithmeticProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticHelperTrait;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticNativeTrait;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticScaleTrait;
use Samsara\Fermat\Core\Types\Traits\Arithmetic\ArithmeticSelectionTrait;
use Samsara\Fermat\Expressions\Values\Algebra\PolynomialFunction;
use Samsara\Fermat\Coordinates\Values\PolarCoordinate;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

trait ArithmeticComplexTrait
{

    use ArithmeticHelperTrait;
    use ArithmeticSelectionTrait;
    use ArithmeticScaleTrait;
    use ArithmeticNativeTrait;

    public function add($num)
    {

        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 0);

        $newRealPart = $thisRealPart->add($thatRealPart);
        $newImaginaryPart = $thisImaginaryPart->add($thatImaginaryPart);

        if ($newRealPart->isEqual(0) xor $newImaginaryPart->isEqual(0)) {
            $newNum = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

            return new ImmutableDecimal($newNum->getValue());
        }

        if ($newRealPart->isEqual(0) && $newImaginaryPart->isEqual(0)) {
            return new ImmutableDecimal('0');
        }

        return $this->setValue($newRealPart, $newImaginaryPart);

    }

    public function subtract($num)
    {

        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 0);

        $newRealPart = $thisRealPart->subtract($thatRealPart);
        $newImaginaryPart = $thisImaginaryPart->subtract($thatImaginaryPart);

        if ($newRealPart->isEqual(0) xor $newImaginaryPart->isEqual(0)) {
            $newNum = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

            return new ImmutableDecimal($newNum->getValue());
        }

        if ($newRealPart->isEqual(0) && $newImaginaryPart->isEqual(0)) {
            return new ImmutableDecimal('0');
        }

        return $this->setValue($newRealPart, $newImaginaryPart);

    }

    public function multiply($num)
    {

        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 1);

        if ($num->isComplex()) {
            $foiled = PolynomialFunction::createFromFoil([
                $thisRealPart,
                $thisImaginaryPart
            ], [
                $thatRealPart,
                $thatImaginaryPart
            ]);

            $parts = $foiled->describeShape();

            $newRealPart = new ImmutableDecimal($parts[0]);
            $newImaginaryPart = new ImmutableDecimal($parts[1]);
            /** @var ImmutableDecimal $newRealPart */
            $newRealPart = $newRealPart->add($parts[2]);
        } else {
            $value1 = $thisRealPart->multiply($num);
            $value2 = $thisImaginaryPart->multiply($num);

            $newRealPart = $value1->isReal() ? $value1 : $value2;
            $newImaginaryPart = $value1->isImaginary() ? $value1 : $value2;
        }

        if (!$newRealPart->isEqual(0) && !$newImaginaryPart->isEqual(0)) {
            return $this->setValue($newRealPart, $newImaginaryPart);
        }

        $value = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;
        $value = $value->isEqual(0) ? new ImmutableDecimal(0) : $value;

        return new ImmutableDecimal($value->getValue());

    }

    public function divide($num, int $scale = null)
    {

        $scale = $scale ?? $this->getScale();

        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 1);

        if ($num->isComplex()) {
            $newRho = $this->getDistanceFromOrigin()->divide($num->getDistanceFromOrigin(), $scale);
            $newTheta = $this->getPolarAngle()->subtract($num->getPolarAngle());

            $polar = new PolarCoordinate($newRho, $newTheta);
            $cartesian = $polar->asCartesian();

            $newRealPart = $cartesian->getAxis('x');
            $newImaginaryPart = $cartesian->getAxis('y');
        } else {
            $newRealPart = $thisRealPart->divide($num, $scale);
            $newImaginaryPart = $thisImaginaryPart->divide($num, $scale);
        }

        return $this->setValue($newRealPart, $newImaginaryPart);

    }

    public function pow($num)
    {

        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 1);

        /*
         * If the exponent is a real, positive integer, then we can just do repeated multiplication faster
         */
        if ($num->isReal() && $num->isNatural() && $num->isPositive()) {
            $newValue = clone $this;

            for ($i=0;$num->isGreaterThan($i);$i++) {
                $newValue = $newValue->multiply($this);
            }

            if ($newValue instanceof DecimalInterface) {
                return $newValue;
            } else {
                return $this->setValue($newValue->getRealPart(), $newValue->getImaginaryPart());
            }
        }

        $internalScale = ($this->getScale() > $num->getScale()) ? $this->getScale() : $num->getScale();
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

        return $this->setValue($newRealPart, $newImaginaryPart);

    }

    public function sqrt(int $scale = null)
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