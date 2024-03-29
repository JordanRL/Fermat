<?php

namespace Samsara\Fermat\Core\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @group Providers
 */
class SeriesProviderTest extends TestCase
{

    /**
     * @medium
     */
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

        $this->assertEquals('1.11111111111', $value->getValue());

    }

}