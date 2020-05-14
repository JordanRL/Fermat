<?php

declare(strict_types=1);

namespace Samsara\Fermat\Types\Traits\Arithmetic;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\ComplexNumberInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\SimpleNumberInterface;
use Samsara\Fermat\Types\ComplexNumber;
use Samsara\Fermat\Types\Decimal;
use Samsara\Fermat\Types\Fraction;
use Samsara\Fermat\Values\Algebra\PolynomialFunction;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\PolarCoordinate;
use Samsara\Fermat\Values\ImmutableComplexNumber;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Values\MutableFraction;

trait ArithmeticPrecisionTrait
{

    protected function addPrecision($num)
    {

        $precision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

        return ArithmeticProvider::add($this->asReal(), $num->asReal(), $precision);

    }

    protected function subtractPrecision($num)
    {

        $precision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

        return ArithmeticProvider::subtract($this->asReal(), $num->asReal(), $precision);

    }

    protected function multiplyPrecision($num)
    {

        $precision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

        return ArithmeticProvider::multiply($this->asReal(), $num->asReal(), $precision);

    }

    protected function dividePrecision($num, ?int $precision)
    {

        if (is_null($precision)) {
            $precision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();
        }

        return ArithmeticProvider::divide($this->asReal(), $num->asReal(), $precision);

    }

    protected function powPrecision($num)
    {

        $precision = ($this->getPrecision() > $num->getPrecision()) ? $this->getPrecision() : $num->getPrecision();

        if (!$num->isWhole()) {
            $precision += 5;
            $exponent = $num->multiply($this->ln($precision));
            return $exponent->exp($precision)->truncateToPrecision($precision - 5)->getValue();
        }

        return ArithmeticProvider::pow($this->asReal(), $num->asReal(), $precision);

    }

    protected function sqrtPrecision(?int $precision)
    {

        $precision = $precision ?? $this->getPrecision();

        return ArithmeticProvider::squareRoot($this->getAsBaseTenRealNumber(), $precision);

    }

}