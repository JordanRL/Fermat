<?php

namespace Samsara\Fermat;

use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Types\Base\Interfaces\Groups\MatrixInterface;
use Samsara\Fermat\Types\NumberCollection;
use Samsara\Fermat\Values\ImmutableMatrix;
use Samsara\Fermat\Values\MutableMatrix;

class Matrices
{
    const IMMUTABLE_MATRIX = ImmutableMatrix::class;
    const MUTABLE_MATRIX = MutableMatrix::class;

    public static function zeroMatrix(string $type, int $rows, int $columns): MatrixInterface
    {
        $matrixData = [];

        for ($i = 0;$i < $rows;$i++) {
            $matrixData[$i] = new NumberCollection();
            for ($n = 0;$n < $columns;$n++) {
                $matrixData[$i]->push(Numbers::makeZero());
            }
        }

        return ($type === Matrices::IMMUTABLE_MATRIX) ? new ImmutableMatrix($matrixData) : new MutableMatrix($matrixData);
    }

    public static function onesMatrix(string $type, int $rows, int $columns): MatrixInterface
    {
        $matrixData = [];

        for ($i = 0;$i < $rows;$i++) {
            $matrixData[$i] = new NumberCollection();
            for ($n = 0;$n < $columns;$n++) {
                $matrixData[$i]->push(Numbers::makeOne());
            }
        }

        return ($type === Matrices::IMMUTABLE_MATRIX) ? new ImmutableMatrix($matrixData) : new MutableMatrix($matrixData);
    }

    public static function identityMatrix(string $type, int $size): MatrixInterface
    {
        $matrixData = [];

        for ($i = 0;$i < $size;$i++) {
            $matrixData[$i] = new NumberCollection();
            for ($n = 0;$n < $size;$n++) {
                if ($n === $i) {
                    $matrixData[$i]->push(Numbers::makeOne());
                } else {
                    $matrixData[$i]->push(Numbers::makeZero());
                }
            }
        }

        return ($type === Matrices::IMMUTABLE_MATRIX) ? new ImmutableMatrix($matrixData) : new MutableMatrix($matrixData);
    }

    public static function cofactorMatrix(string $type, int $size)
    {

        $matrixData = [];

        $p = 0;

        for ($i = 0;$i < $size;$i++) {
            $matrixData[$i] = SequenceProvider::nthPowerNegativeOne($p, true, $size);
            $p = ($p === 0 ? 1 : 0);
        }

        return ($type === Matrices::IMMUTABLE_MATRIX) ? new ImmutableMatrix($matrixData) : new MutableMatrix($matrixData);
    }

}