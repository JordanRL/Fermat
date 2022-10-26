<?php

declare(strict_types=1);

namespace Samsara\Fermat\Complex\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Fermat\Coordinates\Values\PolarCoordinate;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 *
 */
class ImmutableComplexNumberTest extends TestCase
{

    protected static ImmutableComplexNumber $complexOneOne;
    protected static ImmutableComplexNumber $complexOneTwo;
    protected static ImmutableComplexNumber $complexTwoTwo;
    protected static ImmutableComplexNumber $complexOneNegTwo;
    protected static ImmutableComplexNumber $complexNegOneNegTwo;
    protected static ImmutableComplexNumber $complexThreeFour;

    public static function setUpBeforeClass(): void
    {
        $one = new ImmutableDecimal('1');
        $negOne = new ImmutableDecimal('-1');
        $oneI = new ImmutableDecimal('1i');
        $two = new ImmutableDecimal('2');
        $twoI = new ImmutableDecimal('2i');
        $negTwoI = new ImmutableDecimal('-2i');
        $three = new ImmutableDecimal('3');
        $fourI = new ImmutableDecimal('4i');

        self::$complexOneTwo = new ImmutableComplexNumber($one, $twoI);
        self::$complexOneNegTwo = new ImmutableComplexNumber($one, $negTwoI);
        self::$complexThreeFour = new ImmutableComplexNumber($three, $fourI);
        self::$complexNegOneNegTwo = new ImmutableComplexNumber($negOne, $negTwoI);
        self::$complexTwoTwo = new ImmutableComplexNumber($two, $twoI);
        self::$complexOneOne = new ImmutableComplexNumber($one, $oneI);
    }

    /**
     * @medium
     */
    public function testConstruct()
    {

        $one = new ImmutableDecimal('1');
        $twoI = new ImmutableDecimal('2i');
        $negTwoI = new ImmutableDecimal('-2i');

        $complex = new ImmutableComplexNumber($one, $twoI);

        $this->assertEquals('1+2i', $complex->getValue());

        $complex = new ImmutableComplexNumber($one, $negTwoI);

        $this->assertEquals('1-2i', $complex->getValue());

    }

    /**
     * @group complex
     * @medium
     */
    public function testAbsValue()
    {

        $complex = self::$complexThreeFour;

        $this->assertEquals('5', $complex->absValue());
        $this->assertEquals('5', $complex->abs()->getValue());

        $complex = self::$complexOneTwo;

        $this->assertEquals('2.2360679775', $complex->absValue());
        $this->assertEquals('2.2360679775', $complex->abs()->getValue());

    }

    /**
     * @group complex
     * @medium
     */
    public function testAsReal()
    {

        $complex = self::$complexThreeFour;

        $this->assertEquals('5', $complex->asReal()->getValue());
        $this->assertEquals(ImmutableDecimal::class, get_class($complex->asReal()));

        $complex = self::$complexOneTwo;

        $this->assertEquals('2.2360679775', $complex->asReal()->getValue());
        $this->assertEquals(ImmutableDecimal::class, get_class($complex->asReal()));

    }

    /**
     * @group complex
     * @medium
     */
    public function testAsImaginary()
    {

        $complex = self::$complexThreeFour;

        $this->assertEquals('5i', $complex->asImaginary()->getValue());
        $this->assertEquals(ImmutableDecimal::class, get_class($complex->asImaginary()));

        $complex = self::$complexOneTwo;

        $this->assertEquals('2.2360679775i', $complex->asImaginary()->getValue());
        $this->assertEquals(ImmutableDecimal::class, get_class($complex->asImaginary()));

    }

    public function testAsPolar()
    {

        $complex = self::$complexOneTwo;

        $this->assertEquals(PolarCoordinate::class, get_class($complex->asPolar()));
        $this->assertEquals('2.23606797749979', $complex->asPolar()->getDistanceFromOrigin()->getValue());
        $this->assertEquals('1.107148717794', $complex->asPolar()->getPolarAngle()->getValue());

    }

    public function testGetAsBaseTenRealNumber()
    {
        $complex = self::$complexOneTwo;

        $this->assertEquals('2.23606797749979', $complex->getAsBaseTenRealNumber());
    }

    public function testIsFunctions()
    {
        $complex = self::$complexOneTwo;

        // isImaginary
        $this->assertFalse($complex->isImaginary());

        // isEqual
        $this->assertFalse($complex->isEqual('1'));
        $this->assertFalse($complex->isEqual(new ImmutableDecimal(1)));
        $this->assertFalse($complex->isEqual('1+3i'));
        $this->assertFalse($complex->isEqual('1+3i+6'));
        $this->assertFalse($complex->isEqual('2i'));
        $this->assertFalse($complex->isEqual('2i+4i'));
        $this->assertTrue($complex->isEqual('1+2i'));
        $this->assertTrue($complex->isEqual(self::$complexOneTwo));
    }

    public function testExceptionInequality1()
    {
        $complex = self::$complexOneTwo;

        $this->expectException(IncompatibleObjectState::class);
        $complex->isGreaterThan('1');
    }

    public function testExceptionInequality2()
    {
        $complex = self::$complexOneTwo;

        $this->expectException(IncompatibleObjectState::class);
        $complex->isGreaterThanOrEqualTo('1');
    }

    public function testExceptionInequality3()
    {
        $complex = self::$complexOneTwo;

        $this->expectException(IncompatibleObjectState::class);
        $complex->isLessThan('1');
    }

    public function testExceptionInequality4()
    {
        $complex = self::$complexOneTwo;

        $this->expectException(IncompatibleObjectState::class);
        $complex->isLessThanOrEqualTo('1');
    }

    public function testAsComplex()
    {
        $complex = self::$complexOneTwo;

        $this->assertEquals(ImmutableComplexNumber::class, get_class($complex->asComplex()));
    }

    public function testMakeFromString()
    {
        $complex = ImmutableComplexNumber::makeFromString('1-4i');

        $this->assertEquals('1', $complex->getRealPart()->getValue());
        $this->assertEquals('-4i', $complex->getImaginaryPart()->getValue());
        $this->assertEquals(ImmutableComplexNumber::class, get_class($complex));

        $complex = MutableComplexNumber::makeFromString('1-4i');

        $this->assertEquals('1', $complex->getRealPart()->getValue());
        $this->assertEquals('-4i', $complex->getImaginaryPart()->getValue());
        $this->assertEquals(MutableComplexNumber::class, get_class($complex));
    }

}