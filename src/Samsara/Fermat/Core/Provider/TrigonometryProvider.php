<?php

namespace Samsara\Fermat\Core\Provider;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;

/**
 * @package Samsara\Fermat\Core
 */
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
        $pi = Numbers::makePi($radians->getScale() + 2);
        
        return $radians->multiply(180)->divide($pi, $radians->getScale() + 2)->round($radians->getScale() - 2)->getValue(NumberBase::Ten);
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
        $pi = Numbers::makePi($degrees->getScale() + 1);

        return $degrees->multiply($pi)->divide(180)->round($degrees->getScale())->getValue(NumberBase::Ten);
    }

}