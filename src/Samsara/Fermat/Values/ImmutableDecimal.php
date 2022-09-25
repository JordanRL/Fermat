<?php

namespace Samsara\Fermat\Values;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\BaseConversionProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Decimal;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

/**
 *
 */
class ImmutableDecimal extends Decimal
{

    /**
     * @param NumberInterface|string|int|float $mod
     * @return DecimalInterface
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public function continuousModulo(NumberInterface|string|int|float $mod): DecimalInterface
    {

        if (is_object($mod) && method_exists($mod, 'getScale')) {
            $scale = ($this->getScale() < $mod->getScale()) ? $mod->getScale() : $this->getScale();
        } else {
            $scale = $this->getScale();
        }

        $oldScale = $this->scale;
        $newScale = $scale+1;

        $this->scale = $newScale;

        if (is_object($mod) && method_exists($mod, 'truncateToScale')) {
            $mod = $mod->truncateToScale($newScale);
        } else {
            $mod = Numbers::make(Numbers::IMMUTABLE, $mod, $newScale);
        }

        $multiple = $this->divide($mod)->floor();

        $remainder = $this->subtract($mod->multiply($multiple));

        $this->scale = $oldScale;

        return $remainder->truncateToScale($oldScale);

    }

    /**
     * @param string $value
     * @param int|null $scale
     * @param NumberBase|null $base
     * @param bool $setToNewBase
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    protected function setValue(string $value, ?int $scale = null, ?NumberBase $base = null, bool $setToNewBase = false): ImmutableDecimal
    {
        //echo '>>START SET VALUE [From: '.debug_backtrace()[1]['function'].' > '.debug_backtrace()[2]['function'].']<<'.PHP_EOL;
        //echo 'Input Value: '.$value.PHP_EOL;
        //echo 'Input Base: '.($base ? $base->value : 'null').PHP_EOL;
        $imaginary = false;

        $scale = $scale ?? $this->getScale();

        if (str_contains($value, 'i')) {
            $value = str_replace('i', '', $value);
            $imaginary = true;
        }

        if ((!is_null($base) && $base != NumberBase::Ten)) {
            $value = BaseConversionProvider::convertStringToBaseTen($value, $base);
        }

        if ($setToNewBase) {
            $base = $base ?? NumberBase::Ten;
        } else {
            $base = $this->getBase();
        }

        if ($imaginary) {
            $value .= 'i';
        }

        //echo 'Creating Decimal With: V['.$value.'] B['.$base->value.']'.PHP_EOL;

        $return = new ImmutableDecimal($value, $scale, $base, true);

        if (isset($this->calcMode)) {
            $return->setMode($this->calcMode);
        }

        //echo '>>END SET VALUE<<'.PHP_EOL;

        return $return;
    }

}