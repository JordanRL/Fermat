<?php

namespace Samsara\Fermat\Complex\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

class ComplexScaleTest extends TestCase
{

    public function roundProvider(): array
    {
        $aD = new ImmutableDecimal('2.4445');
        $bD = new ImmutableDecimal('1.4445i');

        $a = new ImmutableComplexNumber($aD, $bD, 10);

        return [
            'IComplex round 2.4445+1.4445i Half Even 3 places' => [
                $a,
                RoundingMode::HalfEven,
                3,
                '2.444+1.444i',
                '2.444',
                '1.444i'
            ],
            'IComplex round 2.4445+1.4445i Half Even 2 places' => [
                $a,
                RoundingMode::HalfEven,
                2,
                '2.44+1.44i',
                '2.44',
                '1.44i'
            ],
            'IComplex round 2.4445+1.4445i Half Odd 3 places' => [
                $a,
                RoundingMode::HalfOdd,
                3,
                '2.445+1.445i',
                '2.445',
                '1.445i'
            ],
            'IComplex round 2.4445+1.4445i Half Odd 2 places' => [
                $a,
                RoundingMode::HalfOdd,
                2,
                '2.44+1.44i',
                '2.44',
                '1.44i'
            ],
        ];
    }

    /**
     * @dataProvider roundProvider
     */
    public function testRound(
        ImmutableComplexNumber $a,
        RoundingMode $mode,
        int $decimals,
        string $expectedComplex,
        string $expectedRealPart,
        string $expectedImaginaryPart,
    )
    {
        $answer = $a->round($decimals, $mode);
        $this->assertEquals($expectedComplex, $answer->getValue());
        $this->assertEquals($expectedRealPart, $answer->getRealPart()->getValue());
        $this->assertEquals($expectedImaginaryPart, $answer->getImaginaryPart()->getValue());
        $this->assertEquals(10, $answer->getScale());
    }

    /**
     * @dataProvider roundProvider
     */
    public function testRoundToScale(
        ImmutableComplexNumber $a,
        RoundingMode $mode,
        int $decimals,
        string $expectedComplex,
        string $expectedRealPart,
        string $expectedImaginaryPart,
    )
    {
        $answer = $a->roundToScale($decimals, $mode);
        $this->assertEquals($expectedComplex, $answer->getValue());
        $this->assertEquals($expectedRealPart, $answer->getRealPart()->getValue());
        $this->assertEquals($expectedImaginaryPart, $answer->getImaginaryPart()->getValue());
        $this->assertEquals($decimals, $answer->getScale());
        $this->assertEquals($decimals, $answer->getRealPart()->getScale());
        $this->assertEquals($decimals, $answer->getImaginaryPart()->getScale());
    }

    public function truncateProvider(): array
    {
        $aD = new ImmutableDecimal('2.44459');
        $bD = new ImmutableDecimal('1.44459i');

        $a = new ImmutableComplexNumber($aD, $bD, 10);

        return [
            'IComplex truncate 2.44459+1.44459i Half Even 4 places' => [
                $a,
                4,
                '2.4445+1.4445i',
                '2.4445',
                '1.4445i'
            ],
            'IComplex truncate 2.44459+1.44459i Half Even 3 places' => [
                $a,
                3,
                '2.444+1.444i',
                '2.444',
                '1.444i'
            ],
            'IComplex truncate 2.44459+1.44459i Half Even 2 places' => [
                $a,
                2,
                '2.44+1.44i',
                '2.44',
                '1.44i'
            ],
        ];
    }

    /**
     * @dataProvider truncateProvider
     */
    public function testTruncate(
        ImmutableComplexNumber $a,
        int $decimals,
        string $expectedComplex,
        string $expectedRealPart,
        string $expectedImaginaryPart,
    )
    {
        $answer = $a->truncate($decimals);
        $this->assertEquals($expectedComplex, $answer->getValue());
        $this->assertEquals($expectedRealPart, $answer->getRealPart()->getValue());
        $this->assertEquals($expectedImaginaryPart, $answer->getImaginaryPart()->getValue());
        $this->assertEquals(10, $answer->getScale());
    }

    /**
     * @dataProvider truncateProvider
     */
    public function testTruncateToScale(
        ImmutableComplexNumber $a,
        int $decimals,
        string $expectedComplex,
        string $expectedRealPart,
        string $expectedImaginaryPart,
    )
    {
        $answer = $a->truncateToScale($decimals);
        $this->assertEquals($expectedComplex, $answer->getValue());
        $this->assertEquals($expectedRealPart, $answer->getRealPart()->getValue());
        $this->assertEquals($expectedImaginaryPart, $answer->getImaginaryPart()->getValue());
        $this->assertEquals($decimals, $answer->getScale());
        $this->assertEquals($decimals, $answer->getRealPart()->getScale());
        $this->assertEquals($decimals, $answer->getImaginaryPart()->getScale());
    }

    public function ceilProvider(): array
    {
        $aD = new ImmutableDecimal('2.111');
        $bD = new ImmutableDecimal('1.111i');
        $cD = new ImmutableDecimal('2.999');
        $dD = new ImmutableDecimal('1.999i');

        $a = new ImmutableComplexNumber($aD, $bD, 10);
        $b = new ImmutableComplexNumber($cD, $dD, 10);

        return [
            'IComplex ceil 2.111+1.111i' => [
                $a,
                '3+2i',
                '3',
                '2i'
            ],
            'IComplex ceil 2.999+1.999i' => [
                $b,
                '3+2i',
                '3',
                '2i'
            ],
        ];
    }

    /**
     * @dataProvider ceilProvider
     */
    public function testCeil(
        ImmutableComplexNumber $a,
        string $expectedComplex,
        string $expectedRealPart,
        string $expectedImaginaryPart,
    )
    {
        $answer = $a->ceil();
        $this->assertEquals($expectedComplex, $answer->getValue());
        $this->assertEquals($expectedRealPart, $answer->getRealPart()->getValue());
        $this->assertEquals($expectedImaginaryPart, $answer->getImaginaryPart()->getValue());
    }

    public function floorProvider(): array
    {
        $aD = new ImmutableDecimal('2.111');
        $bD = new ImmutableDecimal('1.111i');
        $cD = new ImmutableDecimal('2.999');
        $dD = new ImmutableDecimal('1.999i');

        $a = new ImmutableComplexNumber($aD, $bD, 10);
        $b = new ImmutableComplexNumber($cD, $dD, 10);

        return [
            'IComplex ceil 2.111+1.111i' => [
                $a,
                '2+1i',
                '2',
                '1i'
            ],
            'IComplex ceil 2.999+1.999i' => [
                $b,
                '2+1i',
                '2',
                '1i'
            ],
        ];
    }

    /**
     * @dataProvider floorProvider
     */
    public function testFloor(
        ImmutableComplexNumber $a,
        string $expectedComplex,
        string $expectedRealPart,
        string $expectedImaginaryPart,
    )
    {
        $answer = $a->floor();
        $this->assertEquals($expectedComplex, $answer->getValue());
        $this->assertEquals($expectedRealPart, $answer->getRealPart()->getValue());
        $this->assertEquals($expectedImaginaryPart, $answer->getImaginaryPart()->getValue());
    }

}