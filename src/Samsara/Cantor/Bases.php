<?php

namespace Samsara\Cantor;

use Samsara\Cantor\Provider\BCProvider;

class Bases
{

    public static $chars = [
        '0' => 0,
        '1' => 1,
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        'A' => 10,
        'B' => 11,
        'C' => 12,
        'D' => 13,
        'E' => 14,
        'F' => 15,
        'G' => 16,
        'H' => 17,
        'I' => 18,
        'J' => 19,
        'K' => 20,
        'L' => 21,
        'M' => 22,
        'N' => 23,
        'O' => 24,
        'P' => 25,
        'Q' => 26,
        'R' => 27,
        'S' => 28,
        'T' => 29,
        'U' => 30,
        'V' => 31,
        'W' => 32,
        'X' => 33,
        'Y' => 34,
        'Z' => 35
    ];

    public static function charFromVal($val)
    {
        if ($val > 35 || $val < 0) {
            throw new \InvalidArgumentException('Only bases up to base 36 are supported.');
        }

        return array_search((int)$val, self::$chars);
    }

    public static function valFromChar($char)
    {
        $char = strtoupper($char);

        if (array_key_exists($char, self::$chars)) {
            return self::$chars[$char];
        } else {
            throw new \InvalidArgumentException('Only alphanumeric characters are valid numbers.');
        }
    }

    public static function doBaseConvMath($num, $oldBase, $newBase)
    {
        $numArr = array_reverse(str_split($num));

        $i = 1;
        $d = 0;

        $sum = '0';

        foreach ($numArr as $digit) {
            $sum = BCProvider::add($sum, BCProvider::multiply(
                Bases::valFromChar($digit),
                BCProvider::exp($oldBase, $d)
            ));
            $d++;
        }

        $converted = '';
        do {
            if (BCProvider::compare(0, $sum) == 0 || BCProvider::compare(0, $sum) == 1) {
                break;
            }
            $newVal = BCProvider::modulo($sum, BCProvider::exp($newBase, $i));
            $newDigit = Bases::charFromVal(BCProvider::divide($newVal, BCProvider::exp($newBase, $i-1)));
            $sum = BCProvider::subtract($sum, $newVal);
            $converted = $newDigit . $converted;
            $i++;
        } while (true);

        return $converted;
    }

    public static function doFractBaseConvMath($num, $oldBase, $newBase)
    {
        // TODO: Do
    }

}