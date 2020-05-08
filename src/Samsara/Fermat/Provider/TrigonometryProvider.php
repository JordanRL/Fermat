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
        $pi = Numbers::makePi();
        
        return $radians->multiply(180)->divide($pi)->getValue();
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
        $pi = Numbers::makePi();

        return $degrees->multiply($pi)->divide(180)->getValue();
    }

}