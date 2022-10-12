<?php

namespace Samsara\Fermat\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Numbers;

/**
 * @group Providers
 */
class TrigonometryProviderTest extends TestCase
{

    /**
     * @small
     */
    public function testRadiansToDegrees()
    {
        $radians = Numbers::makePi(15);

        $this->assertEquals('180', TrigonometryProvider::radiansToDegrees($radians));

        $half = $radians->divide(2);

        $this->assertEquals('90', TrigonometryProvider::radiansToDegrees($half));
    }

    /**
     * @small
     */
    public function testDegreesToRadians()
    {

        $degrees = Numbers::make(Numbers::IMMUTABLE, '180', 5);

        $this->assertEquals('3.14159', TrigonometryProvider::degreesToRadians($degrees));

    }
}
