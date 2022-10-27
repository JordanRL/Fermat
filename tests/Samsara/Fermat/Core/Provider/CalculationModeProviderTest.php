<?php

namespace Provider;

use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Provider\CalculationModeProvider;
use PHPUnit\Framework\TestCase;

class CalculationModeProviderTest extends TestCase
{

    public function testGetCurrentMode()
    {
        $this->assertEquals(CalcMode::Auto, CalculationModeProvider::getCurrentMode());
    }

    public function testSetCurrentMode()
    {
        CalculationModeProvider::setCurrentMode(CalcMode::Precision);
        $this->assertEquals(CalcMode::Precision, CalculationModeProvider::getCurrentMode());
        CalculationModeProvider::setCurrentMode(CalcMode::Auto);
    }
}
