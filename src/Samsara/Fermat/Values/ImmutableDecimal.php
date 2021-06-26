<?php

namespace Samsara\Fermat\Values;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Decimal;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

class ImmutableDecimal extends Decimal
{

    public function continuousModulo($mod): DecimalInterface
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
     * @param int $base
     *
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    protected function setValue(string $value, int $scale = null, int $base = 10)
    {
        $imaginary = false;

        $scale = $scale ?? $this->getScale();

        if (str_contains($value, 'i')) {
            $value = str_replace('i', '', $value);
            $imaginary = true;
        }

        if ($base !== 10 || $this->getBase() !== 10) {
            $base = $base === 10 ? $this->getBase() : $base;
            $value = $this->convertValue($value, 10, $base);
        }

        if ($imaginary) {
            $value .= 'i';
        }

        return new ImmutableDecimal($value, $scale, $base);
    }

}