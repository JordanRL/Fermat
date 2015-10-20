<?php

namespace Samsara\Fermat\Provider;

use Samsara\Fermat\Numbers;

class TrigonometryProvider
{

    public static function radiansToDegrees($radians)
    {
        $radians = Numbers::makeOrDont(Numbers::IMMUTABLE, $radians);

        return BCProvider::divide(BCProvider::multiply(180, $radians->getValue()), M_1_PI);
    }

    public static function degreesToRadians($degrees)
    {
        $degrees = Numbers::makeOrDont(Numbers::IMMUTABLE, $degrees);

        return BCProvider::divide(BCProvider::multiply(M_1_PI, $degrees->getValue()), 180);
    }

    public static function sphericalCartesianAzimuth($x, $y)
    {
        return atan2($y, $x);
    }

    public static function sphericalCartesianInclination($x, $y, $z)
    {
        return acos(
            BCProvider::divide(
                $z,
                self::sphericalCartesianDistance($x, $y, $z)
            )
        );
    }

    public static function sphericalCartesianDistance($x, $y, $z)
    {
        return BCProvider::squareRoot(
            BCProvider::add(
                BCProvider::add(
                    BCProvider::exp($x, 2),
                    BCProvider::exp($y, 2)
                ),
                BCProvider::exp($z, 2)
            )
        );
    }

    public static function moveCartesianToOrigin($startX, $startY, $startZ, $endX, $endY, $endZ)
    {
        return [
            'x' => (BCProvider::subtract($endX, $startX)),
            'y' => (BCProvider::subtract($endY, $startY)),
            'z' => (BCProvider::subtract($endZ, $startZ))
        ];
    }

    public static function headingFromSpherical($azimuth, $inclination)
    {
        return $azimuth.' Mark '.$inclination;
    }

    public static function sphericalFromHeading($heading)
    {
        $parts = explode('mark', strtolower($heading));

        return [
            'azimuth' => $parts[0],
            'inclination' => $parts[1]
        ];
    }

    public static function cartesianFromSpherical($azimuth, $inclination, $rho)
    {
        $azimuth = TrigonometryProvider::degreesToRadians($azimuth);
        $inclination = TrigonometryProvider::degreesToRadians($inclination);
        $rho = Numbers::makeOrDont(Numbers::IMMUTABLE, $rho);

        $unitX = Numbers::make(Numbers::IMMUTABLE, cos($azimuth));
        $unitY = Numbers::make(Numbers::IMMUTABLE, sin($azimuth));
        $unitZ = Numbers::make(Numbers::IMMUTABLE, sin($inclination));

        return [
            'x' => $unitX->multiply($rho),
            'y' => $unitY->multiply($rho),
            'z' => $unitZ->multiply($rho),
        ];
    }

}