<?php

namespace Samsara\Fermat\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Values\ImmutableDecimal;

class SeriesProviderTest extends TestCase
{

    public function testMaclaurinSeriesStops()
    {

        $value = SeriesProvider::maclaurinSeries(
            new ImmutableDecimal(1),
            function($num) {
                return new ImmutableDecimal(1);
            },
            function($num) {
                return new ImmutableDecimal(1);
            },
            function($num) {
                $val = new ImmutableDecimal(10);

                return $val->pow($num);
            }
        );

        $this->assertEquals('1.1111111111', $value->getValue());

    }

}