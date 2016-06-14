<?php

namespace Samsara\Fermat\Provider;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Cartesian;
use Samsara\Fermat\Types\Tuple;
use Samsara\Fermat\Values\Base\NumberInterface;
use Samsara\Fermat\Values\ImmutableNumber;

class TrigonometryProvider
{

    public static function radiansToDegrees($radians)
    {
        $radians = Numbers::makeOrDont(Numbers::IMMUTABLE, $radians);
        $pi = Numbers::makePi();
        
        return $radians->multiply(180)->divide($pi)->getValue();
    }

    public static function degreesToRadians($degrees)
    {
        $degrees = Numbers::makeOrDont(Numbers::IMMUTABLE, $degrees);
        $pi = Numbers::makePi();

        return $degrees->multiply($pi)->divide(180)->getValue();
    }

    public static function sphericalCartesianAzimuth($x, $y)
    {
        return atan2($y, $x);
    }

    public static function sphericalCartesianInclination(Cartesian $cartesian)
    {
        $squaredSum = Numbers::make(Numbers::MUTABLE, 0);

        $operation = function(NumberInterface $number) {
            return $number->exp(2);
        };

        foreach ($cartesian->performOperation($operation) as $value) {
            $squaredSum->add($value);
        }

        $squaredSum->sqrt();

        return acos(
            BCProvider::divide(
                $cartesian->getAxis(2),
                $squaredSum->getValue()
            )
        );
    }

    public static function cartesianDistance(Cartesian $end, Cartesian $start, $returnType = Numbers::IMMUTABLE)
    {
        $squaredSum = Numbers::make($returnType, 0);

        $operation = function(NumberInterface $point1, NumberInterface $point2) {
            return $point1->subtract($point2)->exp(2);
        };

        foreach ($end->performPairedOperation($start, $operation) as $value) {
            $squaredSum = $squaredSum->add($value);
        }

        return $squaredSum->sqrt();
    }

    public static function moveCartesianToOrigin(Cartesian $end, Cartesian $start)
    {

        $operation = function(NumberInterface $end, NumberInterface $start) {
            return $end->subtract($start);
        };

        $points = [];

        foreach ($end->performPairedOperation($start, $operation) as $value) {
            $points[] = $value;
        }

        return new Cartesian($points);
    }

    public static function headingFromSpherical($azimuth, $inclination)
    {
        return $azimuth.' Mark '.$inclination;
    }

    /**
     * @param string $heading
     * @return ImmutableNumber[]
     */
    public static function sphericalFromHeading($heading)
    {
        $parts = explode('mark', strtolower($heading));

        return [
            'azimuth' => Numbers::make(Numbers::IMMUTABLE, $parts[0]),
            'inclination' => Numbers::make(Numbers::IMMUTABLE, $parts[1]),
        ];
    }

    public static function cartesianFromSpherical($azimuth, $inclination, $rho)
    {
        $azimuth = TrigonometryProvider::degreesToRadians($azimuth);
        $inclination = TrigonometryProvider::degreesToRadians($inclination);
        $rho = Numbers::makeOrDont(Numbers::IMMUTABLE, $rho);

        $units[] = Numbers::make(Numbers::IMMUTABLE, cos($azimuth))->multiply($rho);
        $units[] = Numbers::make(Numbers::IMMUTABLE, sin($azimuth))->multiply($rho);
        $units[] = Numbers::make(Numbers::IMMUTABLE, sin($inclination))->multiply($rho);

        return new Cartesian($units);
    }

}