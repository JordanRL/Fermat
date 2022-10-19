<?php

namespace Samsara\Fermat\Core\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Core\Enums\RandomMode;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @group Providers
 */
class RandomProviderTest extends TestCase
{

    /**
     * @medium
     */
    public function testRandomIntLargeNumbers()
    {

        $num1 = new ImmutableDecimal(PHP_INT_MAX);
        $num2 = $num1->add(PHP_INT_MAX);

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomInt($num1, $num2);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
        }

    }

    /**
     * @medium
     */
    public function testRandomIntSmallNumbers()
    {

        $num1 = new ImmutableDecimal('0');
        $num2 = new ImmutableDecimal('100');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomInt($num1, $num2);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
        }

        $num1 = 0;
        $num2 = 100;

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomInt($num1, $num2);
            $this->assertGreaterThanOrEqual(0, $rand->asInt());
            $this->assertLessThanOrEqual(100, $rand->asInt());
        }

        $num1 = new ImmutableDecimal('0');
        $num2 = new ImmutableDecimal('100');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomInt($num1, $num2, RandomMode::Speed);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
        }

    }

    public function testRandomIntNegativeNumbers()
    {

        $num1 = new ImmutableDecimal('-100');
        $num2 = new ImmutableDecimal('-50');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomInt($num1, $num2);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
        }

    }

    public function testRandomIntPosNegNumbers()
    {

        $num1 = new ImmutableDecimal('-100');
        $num2 = new ImmutableDecimal('100');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomInt($num1, $num2);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
        }

    }

    public function testRandomIntEqualInput()
    {

        $num1 = new ImmutableDecimal('100');
        $num2 = new ImmutableDecimal('100');

        $this->expectWarning();
        $this->expectWarningMessage('Attempted to get a random value for a range of no size, with minimum of 100 and maximum of 100');
        $this->assertEquals('100', RandomProvider::randomInt($num1, $num2)->getValue());

    }

    public function testRandomDecimal()
    {

        $num1 = new ImmutableDecimal('0');
        $num2 = new ImmutableDecimal('1');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomDecimal(3);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
        }

        $num1 = new ImmutableDecimal('0');
        $num2 = new ImmutableDecimal('1');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomDecimal(3, RandomMode::Speed);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
        }

    }

    public function testRandomRealStraddle()
    {

        $num1 = new ImmutableDecimal('0.9');
        $num2 = new ImmutableDecimal('1.1');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomReal($num1, $num2, 3);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
            $this->assertLessThanOrEqual(5, strlen($rand->getValue()));
        }

        $num1 = new ImmutableDecimal('0.9');
        $num2 = new ImmutableDecimal('1.1');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomReal($num1, $num2, 8);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
            $this->assertLessThanOrEqual(10, strlen($rand->getValue()));
        }

    }

    public function testRandomRealBounded()
    {

        $num1 = new ImmutableDecimal('0.4');
        $num2 = new ImmutableDecimal('0.5');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomReal($num1, $num2, 3);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
            $this->assertLessThanOrEqual(5, strlen($rand->getValue()));
        }

        $num1 = new ImmutableDecimal('4.04');
        $num2 = new ImmutableDecimal('4.05');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomReal($num1, $num2, 8);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
            $this->assertLessThanOrEqual(10, strlen($rand->getValue()));
        }

    }

    public function testRandomRealRange()
    {

        $num1 = new ImmutableDecimal('0.4');
        $num2 = new ImmutableDecimal('2.6');

        for ($i=0;$i<20;$i++) {
            $rand = RandomProvider::randomReal($num1, $num2, 3);
            $this->assertTrue($num1->isLessThanOrEqualTo($rand));
            $this->assertTrue($num2->isGreaterThanOrEqualTo($rand));
            $this->assertLessThanOrEqual(5, strlen($rand->getValue()));
        }

    }

}