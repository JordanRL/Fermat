<?php

namespace Samsara\Fermat\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Fermat\Enums\Currency;
use Samsara\Fermat\Enums\NumberFormat;
use Samsara\Fermat\Enums\NumberGrouping;
use Samsara\Fermat\Provider\NumberFormatProvider;

class DecimalFormattersTest extends TestCase
{

    /*
     * NUMBER FORMATS
     */

    public function formatEnglishProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');

        return [
            'English -0.0000062352' => [$a, '-0.0000062352', NumberFormat::English],
            'English 0.000024135' => [$b, '0.000024135', NumberFormat::English],
            'English 293847569' => [$c, '293,847,569', NumberFormat::English],
            'English -9847365.12938' => [$d, '-9,847,365.12938', NumberFormat::English],
        ];
    }

    public function formatEuropeanProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');

        return [
            'European -0.0000062352' => [$a, '-0,0000062352', NumberFormat::European],
            'European 0.000024135' => [$b, '0,000024135', NumberFormat::European],
            'European 293847569' => [$c, '293.847.569', NumberFormat::European],
            'European -9847365.12938' => [$d, '-9.847.365,12938', NumberFormat::European],
        ];
    }

    public function formatTechnicalProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');

        return [
            'Technical -0.0000062352' => [$a, '-0.0000062352', NumberFormat::Technical],
            'Technical 0.000024135' => [$b, '0.000024135', NumberFormat::Technical],
            'Technical 293847569' => [$c, '293 847 569', NumberFormat::Technical],
            'Technical -9847365.12938' => [$d, '-9 847 365.12938', NumberFormat::Technical],
        ];
    }

    public function formatEnglishFinanceProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');

        return [
            'English Finance -0.0000062352' => [$a, '(0.0000062352)', NumberFormat::EnglishFinance],
            'English Finance 0.000024135' => [$b, '+0.000024135', NumberFormat::EnglishFinance],
            'English Finance 293847569' => [$c, '+293,847,569', NumberFormat::EnglishFinance],
            'English Finance -9847365.12938' => [$d, '(9,847,365.12938)', NumberFormat::EnglishFinance],
        ];
    }

    public function formatEuropeanFinanceProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');

        return [
            'European Finance -0.0000062352' => [$a, '(0,0000062352)', NumberFormat::EuropeanFinance],
            'European Finance 0.000024135' => [$b, '+0,000024135', NumberFormat::EuropeanFinance],
            'European Finance 293847569' => [$c, '+293.847.569', NumberFormat::EuropeanFinance],
            'European Finance -9847365.12938' => [$d, '(9.847.365,12938)', NumberFormat::EuropeanFinance],
        ];
    }

    public function formatTechnicalFinanceProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');

        return [
            'Technical Finance -0.0000062352' => [$a, '(0.0000062352)', NumberFormat::TechnicalFinance],
            'Technical Finance 0.000024135' => [$b, '+0.000024135', NumberFormat::TechnicalFinance],
            'Technical Finance 293847569' => [$c, '+293 847 569', NumberFormat::TechnicalFinance],
            'Technical Finance -9847365.12938' => [$d, '(9 847 365.12938)', NumberFormat::TechnicalFinance],
        ];
    }

    /**
     * @dataProvider formatEnglishProvider
     * @dataProvider formatEnglishFinanceProvider
     * @dataProvider formatEuropeanProvider
     * @dataProvider formatEuropeanFinanceProvider
     * @dataProvider formatTechnicalProvider
     * @dataProvider formatTechnicalFinanceProvider
     */
    public function testFormatNumber(ImmutableDecimal $a, string $expected, NumberFormat $format)
    {
        $this->assertEquals($expected, $a->setFormat($format)->setGrouping(NumberGrouping::Standard)->getFormattedValue());
    }

    /*
     * CURRENCY FORMATS
     */

    public function currencyDollarProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');

        return [
            'Dollar English -0.0000062352' => [$a, '-$0.0000062352', NumberFormat::English, Currency::Dollar],
            'Dollar English 0.000024135' => [$b, '$0.000024135', NumberFormat::English, Currency::Dollar],
            'Dollar English 293847569' => [$c, '$293,847,569', NumberFormat::English, Currency::Dollar],
            'Dollar English -9847365.12938' => [$d, '-$9,847,365.12938', NumberFormat::English, Currency::Dollar],
            'Dollar English Finance -0.0000062352' => [$a, '($0.0000062352)', NumberFormat::EnglishFinance, Currency::Dollar],
            'Dollar English Finance 0.000024135' => [$b, '+$0.000024135', NumberFormat::EnglishFinance, Currency::Dollar],
            'Dollar English Finance 293847569' => [$c, '+$293,847,569', NumberFormat::EnglishFinance, Currency::Dollar],
            'Dollar English Finance -9847365.12938' => [$d, '($9,847,365.12938)', NumberFormat::EnglishFinance, Currency::Dollar],
        ];
    }

    public function currencyPoundProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');

        return [
            'Pound English -0.0000062352' => [$a, '-£0.0000062352', NumberFormat::English, Currency::Pound],
            'Pound English 0.000024135' => [$b, '£0.000024135', NumberFormat::English, Currency::Pound],
            'Pound English 293847569' => [$c, '£293,847,569', NumberFormat::English, Currency::Pound],
            'Pound English -9847365.12938' => [$d, '-£9,847,365.12938', NumberFormat::English, Currency::Pound],
            'Pound English Finance -0.0000062352' => [$a, '(£0.0000062352)', NumberFormat::EnglishFinance, Currency::Pound],
            'Pound English Finance 0.000024135' => [$b, '+£0.000024135', NumberFormat::EnglishFinance, Currency::Pound],
            'Pound English Finance 293847569' => [$c, '+£293,847,569', NumberFormat::EnglishFinance, Currency::Pound],
            'Pound English Finance -9847365.12938' => [$d, '(£9,847,365.12938)', NumberFormat::EnglishFinance, Currency::Pound],
        ];
    }

    public function currencyEuroProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');

        return [
            'Euro English -0.0000062352' => [$a, '-€0.0000062352', NumberFormat::English, Currency::Euro],
            'Euro English 0.000024135' => [$b, '€0.000024135', NumberFormat::English, Currency::Euro],
            'Euro English 293847569' => [$c, '€293,847,569', NumberFormat::English, Currency::Euro],
            'Euro English -9847365.12938' => [$d, '-€9,847,365.12938', NumberFormat::English, Currency::Euro],
            'Euro English Finance -0.0000062352' => [$a, '(€0.0000062352)', NumberFormat::EnglishFinance, Currency::Euro],
            'Euro English Finance 0.000024135' => [$b, '+€0.000024135', NumberFormat::EnglishFinance, Currency::Euro],
            'Euro English Finance 293847569' => [$c, '+€293,847,569', NumberFormat::EnglishFinance, Currency::Euro],
            'Euro English Finance -9847365.12938' => [$d, '(€9,847,365.12938)', NumberFormat::EnglishFinance, Currency::Euro],
        ];
    }

    /**
     * @dataProvider currencyDollarProvider
     * @dataProvider currencyPoundProvider
     * @dataProvider currencyEuroProvider
     */
    public function testFormatCurrency(ImmutableDecimal $a, string $expected, NumberFormat $format, Currency $currency)
    {
        $this->assertEquals($expected, $a->setFormat($format)->setGrouping(NumberGrouping::Standard)->getCurrencyValue($currency));
    }

    public function scientificProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');
        $e = new ImmutableDecimal('-0.0000062352i');
        $f = new ImmutableDecimal('0.000024135i');
        $g = new ImmutableDecimal('293847569i');
        $h = new ImmutableDecimal('-9847365.12938i');

        return [
            'Scientific -0.0000062352' => [$a, '-6.2352E-6', null],
            'Scientific 0.000024135' => [$b, '2.4135E-5', null],
            'Scientific 293847569' => [$c, '2.93847569E8', null],
            'Scientific -9847365.12938' => [$d, '-9.84736512938E6', null],
            'Scientific -0.0000062352 scale 2' => [$a, '-6.23E-6', 2],
            'Scientific 0.000024135 scale 3' => [$b, '2.413E-5', 3],
            'Scientific 293847569 scale 5' => [$c, '2.93847E8', 5],
            'Scientific -9847365.12938 scale 9' => [$d, '-9.847365129E6', 9],
            'Scientific Imaginary -0.0000062352 scale 2' => [$e, '-6.23E-6i', 2],
            'Scientific Imaginary 0.000024135 scale 3' => [$f, '2.413E-5i', 3],
            'Scientific Imaginary 293847569 scale 5' => [$g, '2.93847E8i', 5],
            'Scientific Imaginary -9847365.12938 scale 9' => [$h, '-9.847365129E6i', 9],
        ];
    }

    /**
     * @dataProvider scientificProvider
     */
    public function testFormatScientific(ImmutableDecimal $a, string $expected, ?int $scale)
    {
        $this->assertEquals($expected, $a->setFormat(NumberFormat::English)->setGrouping(NumberGrouping::Standard)->getScientificValue($scale));
    }

    public function indianGroupingProvider(): array
    {
        $a = new ImmutableDecimal('-0.0000062352');
        $b = new ImmutableDecimal('0.000024135');
        $c = new ImmutableDecimal('293847569');
        $d = new ImmutableDecimal('-9847365.12938');

        return [
            'Indian Grouping -0.0000062352' => [$a, '-0.0000062352'],
            'Indian Grouping 0.000024135' => [$b, '0.000024135'],
            'Indian Grouping 293847569' => [$c, '29,38,47,569'],
            'Indian Grouping -9847365.12938' => [$d, '-98,47,365.12938'],
        ];
    }

    /**
     * @dataProvider indianGroupingProvider
     */
    public function testIndianGrouping(ImmutableDecimal $a, string $expected)
    {
        $this->assertEquals($expected, $a->setFormat(NumberFormat::English)->setGrouping(NumberGrouping::Indian)->getFormattedValue());
    }

    public function importFormatProvider(): array
    {
        $a = '-0.0000062352';
        $b = '0.000024135';
        $c = '29,38,47,569';
        $d = '-98,47,365.12938';

        return [
            'Import Format -0.0000062352' => [$a, '-0.0000062352', '-0.0000062352', '-0.0000062352'],
            'Import Format 0.000024135' => [$b, '0.000024135', '0.000024135', '0.000024135'],
            'Import Format 29,38,47,569' => [$c, '293847569', '29,38,47,569', '293 847 569'],
            'Import Format -98,47,365.12938' => [$d, '-9847365.12938', '-98,47,365.12938', '-9 847 365.12938'],
        ];
    }

    /**
     * @dataProvider importFormatProvider
     */
    public function testCreateFromFormat(string $a, string $expected1, string $expected2, string $expected3)
    {
        $a = ImmutableDecimal::createFromFormat(NumberFormat::English, NumberGrouping::Indian, $a);
        $this->assertEquals($expected1, $a->getValue());
        $this->assertEquals($expected2, $a->getFormattedValue());
        $this->assertEquals($expected3, $a->setGrouping(NumberGrouping::Standard)->setFormat(NumberFormat::Technical)->getFormattedValue());
    }

}