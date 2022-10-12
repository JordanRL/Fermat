<?php

namespace Samsara\Fermat\Values;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
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

        $mod = Numbers::makeOrDont(Numbers::IMMUTABLE, $mod);

        $scale = ($this->getScale() < $mod->getScale()) ? $mod->getScale() : $this->getScale();

        $newScale = $scale+2;
        $thisNum = Numbers::make(Numbers::IMMUTABLE, $this->getValue(NumberBase::Ten), $newScale);

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