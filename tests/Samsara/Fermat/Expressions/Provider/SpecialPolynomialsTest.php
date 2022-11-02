<?php

namespace Samsara\Fermat\Expressions\Provider;

use PHPUnit\Framework\TestCase;

class SpecialPolynomialsTest extends TestCase
{

    public function risingFactorialProvider(): array
    {
        return [
            'Polynomial for rf(2)' => [2, ['1', '1']],
            'Polynomial for rf(3)' => [3, ['2', '3', '1']],
            'Polynomial for rf(4)' => [4, ['6', '11', '6', '1']],
        ];
    }

    /**
     * @dataProvider risingFactorialProvider
     */
    public function testRisingFactorial(int $n, array $expected)
    {
        $poly = SpecialPolynomials::risingFactorial($n);
        $shape = $poly->describeShape();

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $shape[$key+1]);
        }
    }

    public function fallingFactorialProvider(): array
    {
        return [
            'Polynomial for rf(2)' => [2, ['-1', '1']],
            'Polynomial for rf(3)' => [3, ['2', '-3', '1']],
            'Polynomial for rf(4)' => [4, ['-6', '11', '-6', '1']],
        ];
    }

    /**
     * @dataProvider fallingFactorialProvider
     */
    public function testFallingFactorial(int $n, array $expected)
    {
        $poly = SpecialPolynomials::fallingFactorial($n);
        $shape = $poly->describeShape();

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $shape[$key+1]);
        }
    }
}
