<?php

namespace Samsara\Fermat\Core\Values;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\BaseConversionProvider;
use Samsara\Fermat\Core\Types\Decimal;

/**
 * @package Samsara\Fermat\Core
 */
class MutableDecimal extends Decimal
{

    /**
     * @param Decimal|string|int|float $mod
     *
     * @return static
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function continuousModulo(Decimal|string|int|float $mod): static
    {

        $mod = Numbers::makeOrDont(Numbers::IMMUTABLE, $mod, $this->scale + 1);
        $oldNum = Numbers::make(Numbers::IMMUTABLE, $this->getValue(NumberBase::Ten), $this->scale + 1);

        $multiple = $oldNum->divide($mod)->floor();
        $multipleCeil = $multiple->ceil();
        $digits = $multipleCeil->subtract($multiple)->numberOfLeadingZeros();

        if ($digits >= $this->getScale()) {
            $multiple = $multipleCeil;
        } else {
            $multiple = $multiple->floor();
        }

        $remainder = $oldNum->subtract($mod->multiply($multiple));

        return new static($remainder->truncate($this->scale - 1)->getValue(NumberBase::Ten), $this->scale - 1, $this->getBase());

    }

    /**
     * @param string          $value
     * @param int|null        $scale
     * @param NumberBase|null $base
     * @param bool            $setToNewBase
     *
     * @return MutableDecimal
     */
    protected function setValue(string $value, ?int $scale = null, ?NumberBase $base = null, bool $setToNewBase = false): self
    {
        $imaginary = false;

        if (str_contains($value, 'i')) {
            $value = str_replace('i', '', $value);
            $imaginary = true;
        }

        if (!is_null($base) && $base != NumberBase::Ten) {
            $value = BaseConversionProvider::convertStringToBaseTen($value, $base);
        }

        $this->imaginary = $imaginary;

        if ($setToNewBase) {
            $this->base = $base ?? $this->getBase();
        }

        $this->value = $this->translateValue($value);

        $scale = $scale ?? $this->getScale();

        $this->scale = $this->determineScale($this->getDecimalPart(), $scale);

        return $this;
    }

}