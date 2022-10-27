<?php

namespace Samsara\Fermat\Core\Values;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\BaseConversionProvider;
use Samsara\Fermat\Core\Types\Decimal;

/**
 * @package Samsara\Fermat\Core
 */
class ImmutableDecimal extends Decimal
{

    /**
     * @param Decimal|string|int|float $mod
     * @return static
     * @throws IntegrityConstraint
     */
    public function continuousModulo(Decimal|string|int|float $mod): static
    {

        /** @var ImmutableDecimal $mod */
        $mod = Numbers::makeOrDont(Numbers::IMMUTABLE, $mod);

        $scale = ($this->getScale() < $mod->getScale()) ? $mod->getScale() : $this->getScale();

        $newScale = $scale+2;
        $thisNum = new ImmutableDecimal($this->getValue(NumberBase::Ten), $newScale);

        $mod = $mod->truncateToScale($newScale);

        $multiple = $thisNum->divide($mod, $newScale);
        $multipleCeil = $multiple->ceil();
        $digits = $multipleCeil->subtract($multiple)->numberOfLeadingZeros();

        if ($digits >= $this->getScale()) {
            $multiple = $multipleCeil;
        } else {
            $multiple = $multiple->floor();
        }

        $subtract = $mod->multiply($multiple);

        /** @var static $remainder */
        $remainder = $thisNum->subtract($subtract);

        return $remainder->truncateToScale($this->getScale()-1);

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
        $imaginary = false;

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

        $sign = $this->sign;

        $translated = $this->translateValue($value);
        $determinedScale = $this->determineScale($translated[1]);

        $this->sign = $sign;

        $determinedScale = $this->getScale() > $determinedScale ? $this->getScale() : $determinedScale;

        $scale = $scale ?? $determinedScale;

        if ($imaginary) {
            $value .= 'i';
        }

        $return = new ImmutableDecimal($value, $scale, $base, true);

        if (isset($this->calcMode)) {
            $return->setMode($this->calcMode);
        }

        return $return;
    }

}