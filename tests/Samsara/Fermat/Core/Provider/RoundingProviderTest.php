<?php

namespace Samsara\Fermat\Core\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @group Providers
 */
class RoundingProviderTest extends TestCase
{

    protected static RoundingMode $roundingMode;

    public static function setUpBeforeClass(): void
    {
        static::$roundingMode = RoundingProvider::getRoundingMode();
    }

    public static function tearDownAfterClass(): void
    {
        RoundingProvider::setRoundingMode(static::$roundingMode);
    }

    public function halfEvenProvider(): array
    {
        $a = new ImmutableDecimal('1.111111');
        $b = new ImmutableDecimal('1.555555');
        $c = new ImmutableDecimal('555555');
        $d = new ImmutableDecimal('9999.9999');
        $e = new ImmutableDecimal('2.222222');
        $f = new ImmutableDecimal('2.522225');
        $g = new ImmutableDecimal('0.70710678118655');
        $h = new ImmutableDecimal('-1.111111');
        $i = new ImmutableDecimal('-1.555555');
        $j = new ImmutableDecimal('-555555');
        $k = new ImmutableDecimal('-9999.9999');
        $l = new ImmutableDecimal('-2.222222');
        $m = new ImmutableDecimal('-2.522225');

        return [
            'Half Even 1.111111 places 5' => [$a, '1.11111', RoundingMode::HalfEven, 5],
            'Half Even 1.555555 places 5' => [$b, '1.55556', RoundingMode::HalfEven, 5],
            'Half Even 1.555555 places 0' => [$b, '2.0', RoundingMode::HalfEven, 0],
            'Half Even 555555 places 0' => [$c, '556000.0', RoundingMode::HalfEven, -3],
            'Half Even 9999.9999 places 0' => [$d, '10000.0', RoundingMode::HalfEven, 0],
            'Half Even 9999.9999 places 3' => [$d, '10000.0', RoundingMode::HalfEven, 3],
            'Half Even 2.222222 places 2' => [$e, '2.22', RoundingMode::HalfEven, 2],
            'Half Even 2.522225 places 0' => [$f, '3.0', RoundingMode::HalfEven, 0],
            'Half Even 2.5 places 0' => [$f->truncateToScale(1), '2.0', RoundingMode::HalfEven, 0],
            'Half Even 2.522225 places 5' => [$f, '2.52222', RoundingMode::HalfEven, 5],
            'Half Even 0.70710678118655 places 10' => [$g, '0.7071067812', RoundingMode::HalfEven, 10],
            'Half Even -1.111111 places 5' => [$h, '-1.11111', RoundingMode::HalfEven, 5],
            'Half Even -1.555555 places 5' => [$i, '-1.55556', RoundingMode::HalfEven, 5],
            'Half Even -1.555555 places 0' => [$i, '-2.0', RoundingMode::HalfEven, 0],
            'Half Even -555555 places 0' => [$j, '-556000.0', RoundingMode::HalfEven, -3],
            'Half Even -9999.9999 places 0' => [$k, '-10000.0', RoundingMode::HalfEven, 0],
            'Half Even -9999.9999 places 3' => [$k, '-10000.0', RoundingMode::HalfEven, 3],
            'Half Even -2.222222 places 2' => [$l, '-2.22', RoundingMode::HalfEven, 2],
            'Half Even -2.522225 places 0' => [$m, '-3.0', RoundingMode::HalfEven, 0],
            'Half Even -2.5 places 0' => [$m->truncateToScale(1), '-2.0', RoundingMode::HalfEven, 0],
            'Half Even -2.522225 places 5' => [$m, '-2.52222', RoundingMode::HalfEven, 5],
        ];
    }

    public function halfUpProvider(): array
    {
        $a = new ImmutableDecimal('1.111111');
        $b = new ImmutableDecimal('1.555555');
        $c = new ImmutableDecimal('555555');
        $d = new ImmutableDecimal('9999.9999');
        $e = new ImmutableDecimal('2.222222');
        $f = new ImmutableDecimal('2.522225');
        $h = new ImmutableDecimal('-1.111111');
        $i = new ImmutableDecimal('-1.555555');
        $j = new ImmutableDecimal('-555555');
        $k = new ImmutableDecimal('-9999.9999');
        $l = new ImmutableDecimal('-2.222222');
        $m = new ImmutableDecimal('-2.522225');

        return [
            'Half Up 1.111111 places 5' => [$a, '1.11111', RoundingMode::HalfUp, 5],
            'Half Up 1.555555 places 5' => [$b, '1.55556', RoundingMode::HalfUp, 5],
            'Half Up 1.555555 places 0' => [$b, '2.0', RoundingMode::HalfUp, 0],
            'Half Up 555555 places 0' => [$c, '556000.0', RoundingMode::HalfUp, -3],
            'Half Up 9999.9999 places 0' => [$d, '10000.0', RoundingMode::HalfUp, 0],
            'Half Up 9999.9999 places 3' => [$d, '10000.0', RoundingMode::HalfUp, 3],
            'Half Up 2.222222 places 2' => [$e, '2.22', RoundingMode::HalfUp, 2],
            'Half Up 2.522225 places 0' => [$f, '3.0', RoundingMode::HalfUp, 0],
            'Half Up 2.5 places 0' => [$f->truncateToScale(1), '3.0', RoundingMode::HalfUp, 0],
            'Half Up 2.522225 places 5' => [$f, '2.52223', RoundingMode::HalfUp, 5],
            'Half Up -1.111111 places 5' => [$h, '-1.11111', RoundingMode::HalfUp, 5],
            'Half Up -1.555555 places 5' => [$i, '-1.55555', RoundingMode::HalfUp, 5],
            'Half Up -1.555555 places 0' => [$i, '-2.0', RoundingMode::HalfUp, 0],
            'Half Up -555555 places 0' => [$j, '-556000.0', RoundingMode::HalfUp, -3],
            'Half Up -9999.9999 places 0' => [$k, '-10000.0', RoundingMode::HalfUp, 0],
            'Half Up -9999.9999 places 3' => [$k, '-10000.0', RoundingMode::HalfUp, 3],
            'Half Up -2.222222 places 2' => [$l, '-2.22', RoundingMode::HalfUp, 2],
            'Half Up -2.522225 places 0' => [$m, '-3.0', RoundingMode::HalfUp, 0],
            'Half Up -2.5 places 0' => [$m->truncateToScale(1), '-2.0', RoundingMode::HalfUp, 0],
            'Half Up -2.522225 places 5' => [$m, '-2.52222', RoundingMode::HalfUp, 5],
        ];
    }

    public function halfDownProvider(): array
    {
        $a = new ImmutableDecimal('1.111111');
        $b = new ImmutableDecimal('1.555555');
        $c = new ImmutableDecimal('555555');
        $d = new ImmutableDecimal('9999.9999');
        $e = new ImmutableDecimal('2.222222');
        $f = new ImmutableDecimal('2.522225');
        $h = new ImmutableDecimal('-1.111111');
        $i = new ImmutableDecimal('-1.555555');
        $j = new ImmutableDecimal('-555555');
        $k = new ImmutableDecimal('-9999.9999');
        $l = new ImmutableDecimal('-2.222222');
        $m = new ImmutableDecimal('-2.522225');

        return [
            'Half Down 1.111111 places 5' => [$a, '1.11111', RoundingMode::HalfDown, 5],
            'Half Down 1.555555 places 5' => [$b, '1.55555', RoundingMode::HalfDown, 5],
            'Half Down 1.555555 places 0' => [$b, '2.0', RoundingMode::HalfDown, 0],
            'Half Down 555555 places 0' => [$c, '556000.0', RoundingMode::HalfDown, -3],
            'Half Down 9999.9999 places 0' => [$d, '10000.0', RoundingMode::HalfDown, 0],
            'Half Down 9999.9999 places 3' => [$d, '10000.0', RoundingMode::HalfDown, 3],
            'Half Down 2.222222 places 2' => [$e, '2.22', RoundingMode::HalfDown, 2],
            'Half Down 2.522225 places 0' => [$f, '3.0', RoundingMode::HalfDown, 0],
            'Half Down 2.5 places 0' => [$f->truncateToScale(1), '2.0', RoundingMode::HalfDown, 0],
            'Half Down 2.522225 places 5' => [$f, '2.52222', RoundingMode::HalfDown, 5],
            'Half Down -1.111111 places 5' => [$h, '-1.11111', RoundingMode::HalfDown, 5],
            'Half Down -1.555555 places 5' => [$i, '-1.55556', RoundingMode::HalfDown, 5],
            'Half Down -1.555555 places 0' => [$i, '-2.0', RoundingMode::HalfDown, 0],
            'Half Down -555555 places 0' => [$j, '-556000.0', RoundingMode::HalfDown, -3],
            'Half Down -9999.9999 places 0' => [$k, '-10000.0', RoundingMode::HalfDown, 0],
            'Half Down -9999.9999 places 3' => [$k, '-10000.0', RoundingMode::HalfDown, 3],
            'Half Down -2.222222 places 2' => [$l, '-2.22', RoundingMode::HalfDown, 2],
            'Half Down -2.522225 places 0' => [$m, '-3.0', RoundingMode::HalfDown, 0],
            'Half Down -2.5 places 0' => [$m->truncateToScale(1), '-3.0', RoundingMode::HalfDown, 0],
            'Half Down -2.522225 places 5' => [$m, '-2.52223', RoundingMode::HalfDown, 5],
        ];
    }

    public function halfOddProvider(): array
    {
        $a = new ImmutableDecimal('1.111111');
        $b = new ImmutableDecimal('1.555555');
        $c = new ImmutableDecimal('555555');
        $d = new ImmutableDecimal('9999.9999');
        $e = new ImmutableDecimal('2.222222');
        $f = new ImmutableDecimal('2.522225');
        $h = new ImmutableDecimal('-1.111111');
        $i = new ImmutableDecimal('-1.555555');
        $j = new ImmutableDecimal('-555555');
        $k = new ImmutableDecimal('-9999.9999');
        $l = new ImmutableDecimal('-2.222222');
        $m = new ImmutableDecimal('-2.522225');

        return [
            'Half Odd 1.111111 places 5' => [$a, '1.11111', RoundingMode::HalfOdd, 5],
            'Half Odd 1.555555 places 5' => [$b, '1.55555', RoundingMode::HalfOdd, 5],
            'Half Odd 1.555555 places 0' => [$b, '2.0', RoundingMode::HalfOdd, 0],
            'Half Odd 555555 places 0' => [$c, '556000.0', RoundingMode::HalfOdd, -3],
            'Half Odd 9999.9999 places 0' => [$d, '10000.0', RoundingMode::HalfOdd, 0],
            'Half Odd 9999.9999 places 3' => [$d, '10000.0', RoundingMode::HalfOdd, 3],
            'Half Odd 2.222222 places 2' => [$e, '2.22', RoundingMode::HalfOdd, 2],
            'Half Odd 2.522225 places 0' => [$f, '3.0', RoundingMode::HalfOdd, 0],
            'Half Odd 2.5 places 0' => [$f->truncateToScale(1), '3.0', RoundingMode::HalfOdd, 0],
            'Half Odd 2.522225 places 5' => [$f, '2.52223', RoundingMode::HalfOdd, 5],
            'Half Odd -1.111111 places 5' => [$h, '-1.11111', RoundingMode::HalfOdd, 5],
            'Half Odd -1.555555 places 5' => [$i, '-1.55555', RoundingMode::HalfOdd, 5],
            'Half Odd -1.555555 places 0' => [$i, '-2.0', RoundingMode::HalfOdd, 0],
            'Half Odd -555555 places 0' => [$j, '-556000.0', RoundingMode::HalfOdd, -3],
            'Half Odd -9999.9999 places 0' => [$k, '-10000.0', RoundingMode::HalfOdd, 0],
            'Half Odd -9999.9999 places 3' => [$k, '-10000.0', RoundingMode::HalfOdd, 3],
            'Half Odd -2.222222 places 2' => [$l, '-2.22', RoundingMode::HalfOdd, 2],
            'Half Odd -2.522225 places 0' => [$m, '-3.0', RoundingMode::HalfOdd, 0],
            'Half Odd -2.5 places 0' => [$m->truncateToScale(1), '-3.0', RoundingMode::HalfOdd, 0],
            'Half Odd -2.522225 places 5' => [$m, '-2.52223', RoundingMode::HalfOdd, 5],
        ];
    }

    public function halfZeroProvider(): array
    {
        $a = new ImmutableDecimal('1.111111');
        $b = new ImmutableDecimal('1.555555');
        $c = new ImmutableDecimal('555555');
        $d = new ImmutableDecimal('9999.9999');
        $e = new ImmutableDecimal('2.222222');
        $f = new ImmutableDecimal('2.522225');
        $h = new ImmutableDecimal('-1.111111');
        $i = new ImmutableDecimal('-1.555555');
        $j = new ImmutableDecimal('-555555');
        $k = new ImmutableDecimal('-9999.9999');
        $l = new ImmutableDecimal('-2.222222');
        $m = new ImmutableDecimal('-2.522225');

        return [
            'Half Zero 1.111111 places 5' => [$a, '1.11111', RoundingMode::HalfZero, 5],
            'Half Zero 1.555555 places 5' => [$b, '1.55555', RoundingMode::HalfZero, 5],
            'Half Zero 1.555555 places 0' => [$b, '2.0', RoundingMode::HalfZero, 0],
            'Half Zero 555555 places 0' => [$c, '556000.0', RoundingMode::HalfZero, -3],
            'Half Zero 9999.9999 places 0' => [$d, '10000.0', RoundingMode::HalfZero, 0],
            'Half Zero 9999.9999 places 3' => [$d, '10000.0', RoundingMode::HalfZero, 3],
            'Half Zero 2.222222 places 2' => [$e, '2.22', RoundingMode::HalfZero, 2],
            'Half Zero 2.522225 places 0' => [$f, '3.0', RoundingMode::HalfZero, 0],
            'Half Zero 2.5 places 0' => [$f->truncateToScale(1), '2.0', RoundingMode::HalfZero, 0],
            'Half Zero 2.522225 places 5' => [$f, '2.52222', RoundingMode::HalfZero, 5],
            'Half Zero -1.111111 places 5' => [$h, '-1.11111', RoundingMode::HalfZero, 5],
            'Half Zero -1.555555 places 5' => [$i, '-1.55555', RoundingMode::HalfZero, 5],
            'Half Zero -1.555555 places 0' => [$i, '-2.0', RoundingMode::HalfZero, 0],
            'Half Zero -555555 places 0' => [$j, '-556000.0', RoundingMode::HalfZero, -3],
            'Half Zero -9999.9999 places 0' => [$k, '-10000.0', RoundingMode::HalfZero, 0],
            'Half Zero -9999.9999 places 3' => [$k, '-10000.0', RoundingMode::HalfZero, 3],
            'Half Zero -2.222222 places 2' => [$l, '-2.22', RoundingMode::HalfZero, 2],
            'Half Zero -2.522225 places 0' => [$m, '-3.0', RoundingMode::HalfZero, 0],
            'Half Zero -2.5 places 0' => [$m->truncateToScale(1), '-2.0', RoundingMode::HalfZero, 0],
            'Half Zero -2.522225 places 5' => [$m, '-2.52222', RoundingMode::HalfZero, 5],
        ];
    }

    public function halfInfProvider(): array
    {
        $a = new ImmutableDecimal('1.111111');
        $b = new ImmutableDecimal('1.555555');
        $c = new ImmutableDecimal('555555');
        $d = new ImmutableDecimal('9999.9999');
        $e = new ImmutableDecimal('2.222222');
        $f = new ImmutableDecimal('2.522225');
        $h = new ImmutableDecimal('-1.111111');
        $i = new ImmutableDecimal('-1.555555');
        $j = new ImmutableDecimal('-555555');
        $k = new ImmutableDecimal('-9999.9999');
        $l = new ImmutableDecimal('-2.222222');
        $m = new ImmutableDecimal('-2.522225');

        return [
            'Half Infinity 1.111111 places 5' => [$a, '1.11111', RoundingMode::HalfInf, 5],
            'Half Infinity 1.555555 places 5' => [$b, '1.55556', RoundingMode::HalfInf, 5],
            'Half Infinity 1.555555 places 0' => [$b, '2.0', RoundingMode::HalfInf, 0],
            'Half Infinity 555555 places 0' => [$c, '556000.0', RoundingMode::HalfInf, -3],
            'Half Infinity 9999.9999 places 0' => [$d, '10000.0', RoundingMode::HalfInf, 0],
            'Half Infinity 9999.9999 places 3' => [$d, '10000.0', RoundingMode::HalfInf, 3],
            'Half Infinity 2.222222 places 2' => [$e, '2.22', RoundingMode::HalfInf, 2],
            'Half Infinity 2.522225 places 0' => [$f, '3.0', RoundingMode::HalfInf, 0],
            'Half Infinity 2.5 places 0' => [$f->truncateToScale(1), '3.0', RoundingMode::HalfInf, 0],
            'Half Infinity 2.522225 places 5' => [$f, '2.52223', RoundingMode::HalfInf, 5],
            'Half Infinity -1.111111 places 5' => [$h, '-1.11111', RoundingMode::HalfInf, 5],
            'Half Infinity -1.555555 places 5' => [$i, '-1.55556', RoundingMode::HalfInf, 5],
            'Half Infinity -1.555555 places 0' => [$i, '-2.0', RoundingMode::HalfInf, 0],
            'Half Infinity -555555 places 0' => [$j, '-556000.0', RoundingMode::HalfInf, -3],
            'Half Infinity -9999.9999 places 0' => [$k, '-10000.0', RoundingMode::HalfInf, 0],
            'Half Infinity -9999.9999 places 3' => [$k, '-10000.0', RoundingMode::HalfInf, 3],
            'Half Infinity -2.222222 places 2' => [$l, '-2.22', RoundingMode::HalfInf, 2],
            'Half Infinity -2.522225 places 0' => [$m, '-3.0', RoundingMode::HalfInf, 0],
            'Half Infinity -2.5 places 0' => [$m->truncateToScale(1), '-3.0', RoundingMode::HalfInf, 0],
            'Half Infinity -2.522225 places 5' => [$m, '-2.52223', RoundingMode::HalfInf, 5],
        ];
    }

    public function ceilProvider(): array
    {
        $a = new ImmutableDecimal('1.111111');
        $b = new ImmutableDecimal('1.555555');
        $c = new ImmutableDecimal('555555');
        $d = new ImmutableDecimal('9999.9999');
        $e = new ImmutableDecimal('2.222222');
        $f = new ImmutableDecimal('2.522225');
        $h = new ImmutableDecimal('-1.111111');
        $i = new ImmutableDecimal('-1.555555');
        $j = new ImmutableDecimal('-555555');
        $k = new ImmutableDecimal('-9999.9999');
        $l = new ImmutableDecimal('-2.222222');
        $m = new ImmutableDecimal('-2.522225');

        return [
            'Ceil 1.111111 places 5' => [$a, '1.11112', RoundingMode::Ceil, 5],
            'Ceil 1.555555 places 5' => [$b, '1.55556', RoundingMode::Ceil, 5],
            'Ceil 1.555555 places 0' => [$b, '2.0', RoundingMode::Ceil, 0],
            'Ceil 555555 places 0' => [$c, '556000.0', RoundingMode::Ceil, -3],
            'Ceil 9999.9999 places 0' => [$d, '10000.0', RoundingMode::Ceil, 0],
            'Ceil 9999.9999 places 3' => [$d, '10000.0', RoundingMode::Ceil, 3],
            'Ceil 2.222222 places 2' => [$e, '2.23', RoundingMode::Ceil, 2],
            'Ceil 2.522225 places 0' => [$f, '3.0', RoundingMode::Ceil, 0],
            'Ceil 2.5 places 0' => [$f->truncateToScale(1), '3.0', RoundingMode::Ceil, 0],
            'Ceil 2.522225 places 5' => [$f, '2.52223', RoundingMode::Ceil, 5],
            'Ceil -1.111111 places 5' => [$h, '-1.11111', RoundingMode::Ceil, 5],
            'Ceil -1.555555 places 5' => [$i, '-1.55555', RoundingMode::Ceil, 5],
            'Ceil -1.555555 places 0' => [$i, '-1.0', RoundingMode::Ceil, 0],
            'Ceil -555555 places 0' => [$j, '-555000.0', RoundingMode::Ceil, -3],
            'Ceil -9999.9999 places 0' => [$k, '-9999.0', RoundingMode::Ceil, 0],
            'Ceil -9999.9999 places 3' => [$k, '-9999.999', RoundingMode::Ceil, 3],
            'Ceil -2.222222 places 2' => [$l, '-2.22', RoundingMode::Ceil, 2],
            'Ceil -2.522225 places 0' => [$m, '-2.0', RoundingMode::Ceil, 0],
            'Ceil -2.5 places 0' => [$m->truncateToScale(1), '-2.0', RoundingMode::Ceil, 0],
            'Ceil -2.522225 places 5' => [$m, '-2.52222', RoundingMode::Ceil, 5],
        ];
    }

    public function floorProvider(): array
    {
        $a = new ImmutableDecimal('1.111111');
        $b = new ImmutableDecimal('1.555555');
        $c = new ImmutableDecimal('555555');
        $d = new ImmutableDecimal('9999.9999');
        $e = new ImmutableDecimal('2.222222');
        $f = new ImmutableDecimal('2.522225');
        $h = new ImmutableDecimal('-1.111111');
        $i = new ImmutableDecimal('-1.555555');
        $j = new ImmutableDecimal('-555555');
        $k = new ImmutableDecimal('-9999.9999');
        $l = new ImmutableDecimal('-2.222222');
        $m = new ImmutableDecimal('-2.522225');

        return [
            'Floor 1.111111 places 5' => [$a, '1.11111', RoundingMode::Floor, 5],
            'Floor 1.555555 places 5' => [$b, '1.55555', RoundingMode::Floor, 5],
            'Floor 1.555555 places 0' => [$b, '1.0', RoundingMode::Floor, 0],
            'Floor 555555 places 0' => [$c, '555000.0', RoundingMode::Floor, -3],
            'Floor 9999.9999 places 0' => [$d, '9999.0', RoundingMode::Floor, 0],
            'Floor 9999.9999 places 3' => [$d, '9999.999', RoundingMode::Floor, 3],
            'Floor 2.222222 places 2' => [$e, '2.22', RoundingMode::Floor, 2],
            'Floor 2.522225 places 0' => [$f, '2.0', RoundingMode::Floor, 0],
            'Floor 2.5 places 0' => [$f->truncateToScale(1), '2.0', RoundingMode::Floor, 0],
            'Floor 2.522225 places 5' => [$f, '2.52222', RoundingMode::Floor, 5],
            'Floor -1.111111 places 5' => [$h, '-1.11112', RoundingMode::Floor, 5],
            'Floor -1.555555 places 5' => [$i, '-1.55556', RoundingMode::Floor, 5],
            'Floor -1.555555 places 0' => [$i, '-2.0', RoundingMode::Floor, 0],
            'Floor -555555 places 0' => [$j, '-556000.0', RoundingMode::Floor, -3],
            'Floor -9999.9999 places 0' => [$k, '-10000.0', RoundingMode::Floor, 0],
            'Floor -9999.9999 places 3' => [$k, '-10000.0', RoundingMode::Floor, 3],
            'Floor -2.222222 places 2' => [$l, '-2.23', RoundingMode::Floor, 2],
            'Floor -2.522225 places 0' => [$m, '-3.0', RoundingMode::Floor, 0],
            'Floor -2.5 places 0' => [$m->truncateToScale(1), '-3.0', RoundingMode::Floor, 0],
            'Floor -2.522225 places 5' => [$m, '-2.52223', RoundingMode::Floor, 5],
        ];
    }

    public function truncateProvider(): array
    {
        $a = new ImmutableDecimal('1.111111');
        $b = new ImmutableDecimal('1.555555');
        $c = new ImmutableDecimal('555555');
        $d = new ImmutableDecimal('9999.9999');
        $e = new ImmutableDecimal('2.222222');
        $f = new ImmutableDecimal('2.522225');
        $h = new ImmutableDecimal('-1.111111');
        $i = new ImmutableDecimal('-1.555555');
        $j = new ImmutableDecimal('-555555');
        $k = new ImmutableDecimal('-9999.9999');
        $l = new ImmutableDecimal('-2.222222');
        $m = new ImmutableDecimal('-2.522225');

        return [
            'Truncate 1.111111 places 5' => [$a, '1.11111', RoundingMode::Truncate, 5],
            'Truncate 1.555555 places 5' => [$b, '1.55555', RoundingMode::Truncate, 5],
            'Truncate 1.555555 places 0' => [$b, '1.0', RoundingMode::Truncate, 0],
            'Truncate 555555 places 0' => [$c, '555000.0', RoundingMode::Truncate, -3],
            'Truncate 9999.9999 places 0' => [$d, '9999.0', RoundingMode::Truncate, 0],
            'Truncate 9999.9999 places 3' => [$d, '9999.999', RoundingMode::Truncate, 3],
            'Truncate 2.222222 places 2' => [$e, '2.22', RoundingMode::Truncate, 2],
            'Truncate 2.522225 places 0' => [$f, '2.0', RoundingMode::Truncate, 0],
            'Truncate 2.5 places 0' => [$f->truncateToScale(1), '2.0', RoundingMode::Truncate, 0],
            'Truncate 2.522225 places 5' => [$f, '2.52222', RoundingMode::Truncate, 5],
            'Truncate -1.111111 places 5' => [$h, '-1.11111', RoundingMode::Truncate, 5],
            'Truncate -1.555555 places 5' => [$i, '-1.55555', RoundingMode::Truncate, 5],
            'Truncate -1.555555 places 0' => [$i, '-1.0', RoundingMode::Truncate, 0],
            'Truncate -555555 places 0' => [$j, '-555000.0', RoundingMode::Truncate, -3],
            'Truncate -9999.9999 places 0' => [$k, '-9999.0', RoundingMode::Truncate, 0],
            'Truncate -9999.9999 places 3' => [$k, '-9999.999', RoundingMode::Truncate, 3],
            'Truncate -2.222222 places 2' => [$l, '-2.22', RoundingMode::Truncate, 2],
            'Truncate -2.522225 places 0' => [$m, '-2.0', RoundingMode::Truncate, 0],
            'Truncate -2.5 places 0' => [$m->truncateToScale(1), '-2.0', RoundingMode::Truncate, 0],
            'Truncate -2.522225 places 5' => [$m, '-2.52222', RoundingMode::Truncate, 5],
        ];
    }

    /**
     * @dataProvider halfEvenProvider
     * @dataProvider halfUpProvider
     * @dataProvider halfDownProvider
     * @dataProvider halfOddProvider
     * @dataProvider halfZeroProvider
     * @dataProvider halfInfProvider
     * @dataProvider ceilProvider
     * @dataProvider floorProvider
     * @dataProvider truncateProvider
     */
    public function testRound(ImmutableDecimal $a, string $expected, RoundingMode $mode, int $places)
    {
        $oldMode = RoundingProvider::getRoundingMode();
        RoundingProvider::setRoundingMode($mode);
        $this->assertEquals($mode, RoundingProvider::getRoundingMode());
        $this->assertEquals($expected, RoundingProvider::round($a->getValue(NumberBase::Ten), $places));
        RoundingProvider::setRoundingMode($oldMode);
        $this->assertEquals($oldMode, RoundingProvider::getRoundingMode());
    }

    public function testRoundHalfRandom()
    {

        RoundingProvider::setRoundingMode(RoundingMode::HalfRandom);

        $num1 = new ImmutableDecimal('1.5');

        $this->assertEquals(RoundingMode::HalfRandom, RoundingProvider::getRoundingMode());
        for ($i=0;$i<20;$i++) {
            $this->assertEqualsWithDelta(1.5, RoundingProvider::round($num1), 0.5);
        }

        $num1 = new ImmutableDecimal('-1.5');

        for ($i=0;$i<20;$i++) {
            $this->assertEqualsWithDelta(-1.5, RoundingProvider::round($num1), 0.5);
        }

        $num1 = new ImmutableDecimal('-1.53245');
        $this->assertEqualsWithDelta(-1.5, RoundingProvider::round($num1), 0.5);

    }

    public function testRoundHalfAlternating()
    {

        RoundingProvider::setRoundingMode(RoundingMode::HalfAlternating);

        $num1 = new ImmutableDecimal('1.5');

        $this->assertEquals(RoundingMode::HalfAlternating, RoundingProvider::getRoundingMode());
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

        $num1 = new ImmutableDecimal('-1.54325');
        $result = (int)RoundingProvider::round($num1);
        $this->assertEqualsWithDelta(-1.5, $result, 0.5);

    }

    public function testRoundHalfStochastic()
    {

        RoundingProvider::setRoundingMode(RoundingMode::Stochastic);

        $num1 = new ImmutableDecimal('1.5');

        $this->assertEquals(RoundingMode::Stochastic, RoundingProvider::getRoundingMode());
        $sum = 0;
        for ($i=0;$i<20;$i++) {
            $result = (int)RoundingProvider::round($num1);
            $this->assertEqualsWithDelta(1.5, $result, 0.5);
            $sum += $result;
        }

        $this->assertEqualsWithDelta(30, $sum, 10);

        $num1 = new ImmutableDecimal('-1.53674');

        $sum = 0;
        for ($i=0;$i<20;$i++) {
            $result = (int)RoundingProvider::round($num1);
            $this->assertEqualsWithDelta(-1.5, $result, 0.5);
            $sum += $result;
        }

        $this->assertEqualsWithDelta(-30, $sum, 10);

    }

}