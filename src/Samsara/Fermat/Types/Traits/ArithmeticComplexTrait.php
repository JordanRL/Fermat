<?php


namespace Samsara\Fermat\Types\Traits;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticNativeTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticPrecisionTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticSelectionTrait;
use Samsara\Fermat\Values\Algebra\PolynomialFunction;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\PolarCoordinate;
use Samsara\Fermat\Values\ImmutableComplexNumber;
use Samsara\Fermat\Values\ImmutableDecimal;

trait ArithmeticComplexTrait
{

    use ArithmeticSelectionTrait;
    use ArithmeticPrecisionTrait;
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

        return new ImmutableDecimal($value->getValue());

    }

    public function divide($num, int $precision = null)
    {

        $precision = $precision ?? $this->getPrecision();

        [
            $thatRealPart,
            $thatImaginaryPart,
            $thisRealPart,
            $thisImaginaryPart,
            $num
        ] = $this->translateToParts($this, $num, 1);

        if ($num->isComplex()) {
            $newRho = $this->getDistanceFromOrigin()->divide($num->getDistanceFromOrigin(), $precision);
            $newTheta = $this->getPolarAngle()->subtract($num->getPolarAngle());

            $polar = new PolarCoordinate($newRho, $newTheta);
            $cartesian = $polar->asCartesian();

            $newRealPart = $cartesian->getAxis('x');
            $newImaginaryPart = $cartesian->getAxis('y');
        } else {
            $newRealPart = $thisRealPart->divide($num, $precision);
            $newImaginaryPart = $thisImaginaryPart->divide($num, $precision);
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

        $internalPrecision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();
        $internalPrecision += 5;

        /** @var ImmutableDecimal $thisRho */
        $thisRho = $this->getDistanceFromOrigin();
        /** @var ImmutableDecimal $thisTheta */
        $thisTheta = $this->getPolarAngle();

        $e = Numbers::makeE();

        $coef = $thisRho->pow($thatRealPart)->multiply($e->pow($thisTheta->multiply($thatImaginaryPart)->multiply(-1)));

        /** @var ImmutableDecimal $trigArg */
        $trigArg = $thatImaginaryPart->multiply($thisRho->ln())->add($thatRealPart->multiply($thisTheta));

        $newRealPart = $trigArg->cos($internalPrecision)->multiply($coef);
        $newImaginaryPart = $trigArg->sin($internalPrecision)->multiply($coef)->multiply('i');

        if ($newRealPart->absValue() === '0' xor $newImaginaryPart->absValue() === '0') {
            return $newRealPart->absValue() === '0' ? $newRealPart : $newImaginaryPart;
        }

        if ($newRealPart->absValue() === '0' && $newImaginaryPart->absValue() === '0') {
            return Numbers::makeZero();
        }

        return $this->setValue($newRealPart, $newImaginaryPart);

    }

    public function sqrt(int $precision = null)
    {

        $precision = $precision ?? $this->getPrecision();

        $rho = $this->getDistanceFromOrigin();
        $theta = $this->getPolarAngle();

        if (!$rho->isEqual(0)) {
            $rho = ArithmeticProvider::squareRoot($rho->getAsBaseTenRealNumber(), $precision);
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

        return new ImmutableDecimal($newValue->getValue(), $precision);

    }

}