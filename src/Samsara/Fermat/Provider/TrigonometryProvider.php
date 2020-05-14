<?php

namespace Samsara\Fermat\Provider;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;

class TrigonometryProvider
{

    /**
     * @param $radians
     *
     * @return string
     * @throws IntegrityConstraint
     */
    public static function radiansToDegrees($radians)
    {
        $radians = Numbers::makeOrDont(Numbers::IMMUTABLE, $radians);
        $pi = Numbers::makePi($radians->getPrecision() + 2);
        
        return $radians->multiply(180)->divide($pi, $radians->getPrecision() + 2)->round($radians->getPrecision() - 2)->getValue();
    }

    /**
     * @param $degrees
     *
     * @return string
     * @throws IntegrityConstraint
     */
    public static function degreesToRadians($degrees)
    {
        $degrees = Numbers::makeOrDont(Numbers::IMMUTABLE, $degrees);
        $pi = Numbers::makePi($degrees->getPrecision() + 1);

        return $degrees->multiply($pi)->divide(180)->round($degrees->getPrecision())->getValue();
    }

}