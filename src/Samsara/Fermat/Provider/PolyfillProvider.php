<?php

namespace Samsara\Fermat\Provider;

use RandomLib\Factory;

class PolyfillProvider
{

    /**
     * @param $max
     * @param $min
     *
     * @return int
     */
    public static function randomInt(int $max, int $min): int
    {

        try {
            $num = random_int($min, $max);
        } catch (\Exception $exception) {
            $randFactory = new Factory();
            $generator = $randFactory->getMediumStrengthGenerator();
            $num = $generator->generateInt($min, $max);
        }

        return $num;

    }

}