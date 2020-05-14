<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\NumberCollection;

class MutableMatrixTest extends TestCase
{

    public function testSubtractScalarAsJ()
    {
        $matrixData = [
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '3'), Numbers::make(Numbers::IMMUTABLE, '5')]), // row 1
            new NumberCollection([Numbers::make(Numbers::IMMUTABLE, '4'), Numbers::make(Numbers::IMMUTABLE, '2')]), // row 2
        ];

        $matrix = new MutableMatrix($matrixData);

        $matrix->subtractScalarAsJ(Numbers::make(Numbers::IMMUTABLE, '1'));

        $this->assertEquals('2', $matrix->getRow(0)->get(0)->getValue());
        $this->assertEquals('4', $matrix->getRow(0)->get(1)->getValue());
        $this->assertEquals('3', $matrix->getRow(1)->get(0)->getValue());
        $this->assertEquals('1', $matrix->getRow(1)->get(1)->getValue());
    }

}
