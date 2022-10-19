<?php

namespace Samsara\Fermat\Core\Provider;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @group Providers
 */
class BaseConversionProviderTest extends TestCase
{

    /*
     * FROM BASE 10
     */

    public function fromSmallInputProvider(): array
    {
        $a = new ImmutableDecimal('5');
        $b = new ImmutableDecimal('10');
        $c = new ImmutableDecimal('12');
        $d = new ImmutableDecimal('25');

        return [
            '5 base 5' => [$a, NumberBase::Five, '10.0'],
            '10 base 5' => [$b, NumberBase::Five, '20.0'],
            '12 base 5' => [$c, NumberBase::Five, '22.0'],
            '25 base 5' => [$d, NumberBase::Five, '100.0'],
            '5 base 16' => [$a, NumberBase::Sixteen, '5.0'],
            '10 base 16' => [$b, NumberBase::Sixteen, 'A.0'],
            '12 base 16' => [$c, NumberBase::Sixteen, 'C.0'],
            '25 base 16' => [$d, NumberBase::Sixteen, '19.0'],
        ];
    }

    public function fromLargeInputProvider(): array
    {
        $a = new ImmutableDecimal('766666');
        $b = new ImmutableDecimal('938249');
        $c = new ImmutableDecimal('88888888888888888888888888888888');

        return [
            '766666 base 5' => [$a, NumberBase::Five, '144013131.0'],
            '938249 base 5' => [$b, NumberBase::Five, '220010444.0'],
            '88888888888888888888888888888888 base 5' => [$c, NumberBase::Five, '3030432042431223421023421023421023421023421023.0'],
            '766666 base 16' => [$a, NumberBase::Sixteen, 'BB2CA.0'],
            '938249 base 16' => [$b, NumberBase::Sixteen, 'E5109.0'],
            '88888888888888888888888888888888 base 16' => [$c, NumberBase::Sixteen, '461EF7D8F6DCC27F15638E38E38.0'],
        ];
    }

    public function fromDecimalSmallProvider(): array
    {
        $a = new ImmutableDecimal('0.6');
        $b = new ImmutableDecimal('0.65');

        return [
            '0.6 base 5' => [$a, NumberBase::Five, '0.11'],
            '0.65 base 5' => [$b, NumberBase::Five, '0.112'],
        ];
    }

    /**
     * @dataProvider fromSmallInputProvider
     * @dataProvider fromLargeInputProvider
     * @dataProvider fromDecimalSmallProvider
     */
    public function testFromBase10(DecimalInterface $decimal, NumberBase $base, string $expected)
    {
        $this->assertEquals($expected, BaseConversionProvider::convertFromBaseTen($decimal, $base));
    }

    public function toLargeInputProvider(): array
    {
        return [
            '766666 base 5' => ['144013131.0', '766666.0', NumberBase::Five],
            '938249 base 5' => ['220010444.0', '938249.0', NumberBase::Five],
            '88888888888888888888888888888888 base 5' => ['3030432042431223421023421023421023421023421023.0', '88888888888888888888888888888888.0', NumberBase::Five],
            '766666 base 16' => ['BB2CA.0', '766666.0', NumberBase::Sixteen],
            '938249 base 16' => ['E5109.0', '938249.0', NumberBase::Sixteen],
            '88888888888888888888888888888888 base 16' => ['461EF7D8F6DCC27F15638E38E38.0', '88888888888888888888888888888888.0', NumberBase::Sixteen],
        ];
    }

    /**
     * @dataProvider toLargeInputProvider
     */
    public function testToBase10(string $input, string $expected, NumberBase $base)
    {
        $this->assertEquals($expected, BaseConversionProvider::convertStringToBaseTen($input, $base));
    }

}