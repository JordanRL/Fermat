<?php

namespace Samsara\Fermat\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Types\Decimal;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class RoundingProviderTest extends TestCase
{

    protected static int $roundingMode;

    public static function setUpBeforeClass(): void
    {
        static::$roundingMode = RoundingProvider::getRoundingMode();
    }

    public static function tearDownAfterClass(): void
    {
        RoundingProvider::setRoundingMode(static::$roundingMode);
    }

    public function testRoundDefault()
    {

        $num1 = new ImmutableDecimal('1.111111');
        $num2 = new ImmutableDecimal('1.555555');
        $num3 = new ImmutableDecimal('555555');
        $num4 = new ImmutableDecimal('9999.9999');
        $num5 = new ImmutableDecimal('2.222222');
        $num6 = new ImmutableDecimal('2.522225');

        $this->assertEquals(RoundingProvider::MODE_HALF_EVEN, RoundingProvider::getRoundingMode());
        $this->assertEquals('1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('1.55556', RoundingProvider::round($num2, 5));
        $this->assertEquals('2.0', RoundingProvider::round($num2));
        $this->assertEquals('556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('10000.0', RoundingProvider::round($num4));
        $this->assertEquals('10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('3.0', RoundingProvider::round($num6));
        $this->assertEquals('2.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('2.52222', RoundingProvider::round($num6, 5));

        $num1 = new ImmutableDecimal('-1.111111');
        $num2 = new ImmutableDecimal('-1.555555');
        $num3 = new ImmutableDecimal('-555555');
        $num4 = new ImmutableDecimal('-9999.9999');
        $num5 = new ImmutableDecimal('-2.222222');
        $num6 = new ImmutableDecimal('-2.522225');

        $this->assertEquals('-1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('-1.55556', RoundingProvider::round($num2, 5));
        $this->assertEquals('-2.0', RoundingProvider::round($num2));
        $this->assertEquals('-556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('-2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('-3.0', RoundingProvider::round($num6));
        $this->assertEquals('-2.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('-2.52222', RoundingProvider::round($num6, 5));

    }

    public function testRoundHalfUp()
    {

        RoundingProvider::setRoundingMode(RoundingProvider::MODE_HALF_UP);

        $num1 = new ImmutableDecimal('1.111111');
        $num2 = new ImmutableDecimal('1.555555');
        $num3 = new ImmutableDecimal('555555');
        $num4 = new ImmutableDecimal('9999.9999');
        $num5 = new ImmutableDecimal('2.222222');
        $num6 = new ImmutableDecimal('2.522225');

        $this->assertEquals(RoundingProvider::MODE_HALF_UP, RoundingProvider::getRoundingMode());
        $this->assertEquals('1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('1.55556', RoundingProvider::round($num2, 5));
        $this->assertEquals('2.0', RoundingProvider::round($num2));
        $this->assertEquals('556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('10000.0', RoundingProvider::round($num4));
        $this->assertEquals('10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('3.0', RoundingProvider::round($num6));
        $this->assertEquals('3.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('2.52223', RoundingProvider::round($num6, 5));

        $num1 = new ImmutableDecimal('-1.111111');
        $num2 = new ImmutableDecimal('-1.555555');
        $num3 = new ImmutableDecimal('-555555');
        $num4 = new ImmutableDecimal('-9999.9999');
        $num5 = new ImmutableDecimal('-2.222222');
        $num6 = new ImmutableDecimal('-2.522225');

        $this->assertEquals('-1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('-1.55555', RoundingProvider::round($num2, 5));
        $this->assertEquals('-2.0', RoundingProvider::round($num2));
        $this->assertEquals('-556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('-2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('-3.0', RoundingProvider::round($num6));
        $this->assertEquals('-2.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('-2.52222', RoundingProvider::round($num6, 5));

    }

    public function testRoundHalfDown()
    {

        RoundingProvider::setRoundingMode(RoundingProvider::MODE_HALF_DOWN);

        $num1 = new ImmutableDecimal('1.111111');
        $num2 = new ImmutableDecimal('1.555555');
        $num3 = new ImmutableDecimal('555555');
        $num4 = new ImmutableDecimal('9999.9999');
        $num5 = new ImmutableDecimal('2.222222');
        $num6 = new ImmutableDecimal('2.522225');

        $this->assertEquals(RoundingProvider::MODE_HALF_DOWN, RoundingProvider::getRoundingMode());
        $this->assertEquals('1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('1.55555', RoundingProvider::round($num2, 5));
        $this->assertEquals('2.0', RoundingProvider::round($num2));
        $this->assertEquals('556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('10000.0', RoundingProvider::round($num4));
        $this->assertEquals('10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('3.0', RoundingProvider::round($num6));
        $this->assertEquals('2.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('2.52222', RoundingProvider::round($num6, 5));

        $num1 = new ImmutableDecimal('-1.111111');
        $num2 = new ImmutableDecimal('-1.555555');
        $num3 = new ImmutableDecimal('-555555');
        $num4 = new ImmutableDecimal('-9999.9999');
        $num5 = new ImmutableDecimal('-2.222222');
        $num6 = new ImmutableDecimal('-2.522225');

        $this->assertEquals('-1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('-1.55556', RoundingProvider::round($num2, 5));
        $this->assertEquals('-2.0', RoundingProvider::round($num2));
        $this->assertEquals('-556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('-2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('-3.0', RoundingProvider::round($num6));
        $this->assertEquals('-3.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('-2.52223', RoundingProvider::round($num6, 5));

    }

    public function testRoundHalfOdd()
    {

        RoundingProvider::setRoundingMode(RoundingProvider::MODE_HALF_ODD);

        $num1 = new ImmutableDecimal('1.111111');
        $num2 = new ImmutableDecimal('1.555555');
        $num3 = new ImmutableDecimal('555555');
        $num4 = new ImmutableDecimal('9999.9999');
        $num5 = new ImmutableDecimal('2.222222');
        $num6 = new ImmutableDecimal('2.522225');

        $this->assertEquals(RoundingProvider::MODE_HALF_ODD, RoundingProvider::getRoundingMode());
        $this->assertEquals('1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('1.55555', RoundingProvider::round($num2, 5));
        $this->assertEquals('2.0', RoundingProvider::round($num2));
        $this->assertEquals('556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('10000.0', RoundingProvider::round($num4));
        $this->assertEquals('10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('3.0', RoundingProvider::round($num6));
        $this->assertEquals('3.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('2.52223', RoundingProvider::round($num6, 5));

        $num1 = new ImmutableDecimal('-1.111111');
        $num2 = new ImmutableDecimal('-1.555555');
        $num3 = new ImmutableDecimal('-555555');
        $num4 = new ImmutableDecimal('-9999.9999');
        $num5 = new ImmutableDecimal('-2.222222');
        $num6 = new ImmutableDecimal('-2.522225');

        $this->assertEquals('-1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('-1.55555', RoundingProvider::round($num2, 5));
        $this->assertEquals('-2.0', RoundingProvider::round($num2));
        $this->assertEquals('-556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('-2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('-3.0', RoundingProvider::round($num6));
        $this->assertEquals('-3.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('-2.52223', RoundingProvider::round($num6, 5));

    }

    public function testRoundHalfZero()
    {

        RoundingProvider::setRoundingMode(RoundingProvider::MODE_HALF_ZERO);

        $num1 = new ImmutableDecimal('1.111111');
        $num2 = new ImmutableDecimal('1.555555');
        $num3 = new ImmutableDecimal('555555');
        $num4 = new ImmutableDecimal('9999.9999');
        $num5 = new ImmutableDecimal('2.222222');
        $num6 = new ImmutableDecimal('2.522225');

        $this->assertEquals(RoundingProvider::MODE_HALF_ZERO, RoundingProvider::getRoundingMode());
        $this->assertEquals('1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('1.55555', RoundingProvider::round($num2, 5));
        $this->assertEquals('2.0', RoundingProvider::round($num2));
        $this->assertEquals('556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('10000.0', RoundingProvider::round($num4));
        $this->assertEquals('10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('3.0', RoundingProvider::round($num6));
        $this->assertEquals('2.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('2.52222', RoundingProvider::round($num6, 5));

        $num1 = new ImmutableDecimal('-1.111111');
        $num2 = new ImmutableDecimal('-1.555555');
        $num3 = new ImmutableDecimal('-555555');
        $num4 = new ImmutableDecimal('-9999.9999');
        $num5 = new ImmutableDecimal('-2.222222');
        $num6 = new ImmutableDecimal('-2.522225');

        $this->assertEquals('-1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('-1.55555', RoundingProvider::round($num2, 5));
        $this->assertEquals('-2.0', RoundingProvider::round($num2));
        $this->assertEquals('-556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('-2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('-3.0', RoundingProvider::round($num6));
        $this->assertEquals('-2.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('-2.52222', RoundingProvider::round($num6, 5));

    }

    public function testRoundHalfInf()
    {

        RoundingProvider::setRoundingMode(RoundingProvider::MODE_HALF_INF);

        $num1 = new ImmutableDecimal('1.111111');
        $num2 = new ImmutableDecimal('1.555555');
        $num3 = new ImmutableDecimal('555555');
        $num4 = new ImmutableDecimal('9999.9999');
        $num5 = new ImmutableDecimal('2.222222');
        $num6 = new ImmutableDecimal('2.522225');

        $this->assertEquals(RoundingProvider::MODE_HALF_INF, RoundingProvider::getRoundingMode());
        $this->assertEquals('1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('1.55556', RoundingProvider::round($num2, 5));
        $this->assertEquals('2.0', RoundingProvider::round($num2));
        $this->assertEquals('556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('10000.0', RoundingProvider::round($num4));
        $this->assertEquals('10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('3.0', RoundingProvider::round($num6));
        $this->assertEquals('3.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('2.52223', RoundingProvider::round($num6, 5));

        $num1 = new ImmutableDecimal('-1.111111');
        $num2 = new ImmutableDecimal('-1.555555');
        $num3 = new ImmutableDecimal('-555555');
        $num4 = new ImmutableDecimal('-9999.9999');
        $num5 = new ImmutableDecimal('-2.222222');
        $num6 = new ImmutableDecimal('-2.522225');

        $this->assertEquals('-1.11111', RoundingProvider::round($num1, 5));
        $this->assertEquals('-1.55556', RoundingProvider::round($num2, 5));
        $this->assertEquals('-2.0', RoundingProvider::round($num2));
        $this->assertEquals('-556000.0', RoundingProvider::round($num3, -3));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4));
        $this->assertEquals('-10000.0', RoundingProvider::round($num4, 3));
        $this->assertEquals('-2.22', RoundingProvider::round($num5, 2));
        $this->assertEquals('-3.0', RoundingProvider::round($num6));
        $this->assertEquals('-3.0', RoundingProvider::round($num6->truncateToScale(1)));
        $this->assertEquals('-2.52223', RoundingProvider::round($num6, 5));

    }

    public function testRoundHalfRandom()
    {

        RoundingProvider::setRoundingMode(RoundingProvider::MODE_RANDOM);

        $num1 = new ImmutableDecimal('1.5');

        $this->assertEquals(RoundingProvider::MODE_RANDOM, RoundingProvider::getRoundingMode());
        for ($i=0;$i<20;$i++) {
            $this->assertEqualsWithDelta(1.5, RoundingProvider::round($num1), 0.5);
        }

        $num1 = new ImmutableDecimal('-1.5');

        for ($i=0;$i<20;$i++) {
            $this->assertEqualsWithDelta(-1.5, RoundingProvider::round($num1), 0.5);
        }

    }

    public function testRoundHalfAlternating()
    {

        RoundingProvider::setRoundingMode(RoundingProvider::MODE_ALTERNATING);

        $num1 = new ImmutableDecimal('1.5');

        $this->assertEquals(RoundingProvider::MODE_ALTERNATING, RoundingProvider::getRoundingMode());
        $sum = 0;
        for ($i=0;$i<20;$i++) {
            $result = (int)RoundingProvider::round($num1);
            $this->assertEqualsWithDelta(1.5, $result, 0.5);
            $sum += $result;
        }

        $this->assertEquals(30, $sum);

        $num1 = new ImmutableDecimal('-1.5');

        $sum = 0;
        for ($i=0;$i<20;$i++) {
            $result = (int)RoundingProvider::round($num1);
            $this->assertEqualsWithDelta(-1.5, $result, 0.5);
            $sum += $result;
        }

        $this->assertEquals(-30, $sum);

    }

    public function testRoundHalfStochastic()
    {

        RoundingProvider::setRoundingMode(RoundingProvider::MODE_STOCHASTIC);

        $num1 = new ImmutableDecimal('1.5');

        $this->assertEquals(RoundingProvider::MODE_STOCHASTIC, RoundingProvider::getRoundingMode());
        $sum = 0;
        for ($i=0;$i<20;$i++) {
            $result = (int)RoundingProvider::round($num1);
            $this->assertEqualsWithDelta(1.5, $result, 0.5);
            $sum += $result;
        }

        $this->assertEqualsWithDelta(30, $sum, 10);

        $num1 = new ImmutableDecimal('-1.5');

        $sum = 0;
        for ($i=0;$i<20;$i++) {
            $result = (int)RoundingProvider::round($num1);
            $this->assertEqualsWithDelta(-1.5, $result, 0.5);
            $sum += $result;
        }

        $this->assertEqualsWithDelta(-30, $sum, 10);

    }

}