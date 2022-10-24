<?php

declare(strict_types=1);

namespace Samsara\Fermat\Complex\Values;

use PHPUnit\Framework\TestCase;
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

}