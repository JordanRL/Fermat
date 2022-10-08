<?php

namespace Samsara\Fermat\Values;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Provider\BaseConversionProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Decimal;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;

/**
 *
 */
class MutableDecimal extends Decimal
{

    /**
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    public function continuousModulo(NumberInterface|string|int|float $mod): DecimalInterface
    {

        $mod = Numbers::makeOrDont(Numbers::IMMUTABLE, $mod, $this->scale+1);
        $oldNum = Numbers::make(Numbers::IMMUTABLE, $this->getValue(NumberBase::Ten), $this->scale+1);

        $multiple = $oldNum->divide($mod)->floor();

        $remainder = $oldNum->subtract($mod->multiply($multiple));

        return Numbers::make(Numbers::MUTABLE, $remainder->truncate($this->scale-1)->getValue(NumberBase::Ten), $this->scale-1, $this->getBase());

    }

    /**
     * @param string $value
     * @param int|null $scale
     * @param NumberBase|null $base
     * @param bool $setToNewBase
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

        if ($imaginary) {
            $value .= 'i';
        }

        if (is_null($scale)) {
            $this->scale = $this->getScale();
        }

        if ($setToNewBase) {
            $this->base = $base ?? $this->getBase();
        }

        $this->value = $this->translateValue($value);

        return $this;
    }

}