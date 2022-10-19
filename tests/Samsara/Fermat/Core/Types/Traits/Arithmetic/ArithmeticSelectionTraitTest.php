<?php

namespace Samsara\Fermat\Core\Types\Traits\Arithmetic;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Fermat\Core\Numbers;

/**
 * @group Types
 */
class ArithmeticSelectionTraitTest extends TestCase
{

    public function testTranslateToParts()
    {

        $one = Numbers::makeOne();

        $this->assertEquals('1.5', $one->add('1/2')->getValue());

    }

}
