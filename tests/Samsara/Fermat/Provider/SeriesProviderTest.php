<?php

namespace Samsara\Fermat\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Values\ImmutableNumber;

class SeriesProviderTest extends TestCase
{

    public function testMaclaurinSeriesStops()
    {

        $value = SeriesProvider::maclaurinSeries(
            new ImmutableNumber(1),
            function($num) {
                return new ImmutableNumber(1);
            },
            function($num) {
                return new ImmutableNumber(1);
            },
            function($num) {
                $val = new ImmutableNumber(10);

                return $val->pow($num);
            },
            0,
            10,
            true
        );

        $this->assertEquals('1.1111111111', $value->getValue());

    }

}