<?php

namespace Samsara\Fermat\Types\Base;

use Ds\Hashable;
use Samsara\Fermat\ComplexNumbers;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\ComplexNumber;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticNativeTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticPrecisionTrait;
use Samsara\Fermat\Types\Traits\Arithmetic\ArithmeticSelectionTrait;
use Samsara\Fermat\Values\ImmutableComplexNumber;
use Samsara\Fermat\Values\ImmutableFraction;

abstract class Number implements Hashable, NumberInterface
{
    const INFINITY = 'INF';
    const NEG_INFINITY = '-INF';

    /** @var array */
    protected $value;
    /** @var bool  */
    protected $extensions = true;
    /** @var bool  */
    protected $imaginary;
    /** @var bool */
    protected $sign;

    use ArithmeticSelectionTrait;
    use ArithmeticPrecisionTrait;
    use ArithmeticNativeTrait;

    public function __construct($value)
    {
        $this->setMode(Selectable::MODE_PRECISION);
    }

    protected function translateToParts($left, $right, $identity = 0)
    {
        if (is_int($right) || is_float($right)) {
            $right = Numbers::make(Numbers::IMMUTABLE, $right);
        } elseif (is_string($right)) {
            $right = trim($right);
            if (strpos($right, '/') !== false) {
                $right = Numbers::makeFractionFromString(Numbers::IMMUTABLE_FRACTION, $right);
            } elseif (strrpos($right, '+') || strrpos($right, '-')) {
                $right = ComplexNumbers::make(ComplexNumbers::IMMUTABLE, $right);
            } else {
                $right = Numbers::make(Numbers::IMMUTABLE, $right);
            }
        } else {
            $right = Numbers::makeOrDont(Numbers::IMMUTABLE, $right);
        }

        if ($right->isComplex()) {
            /** @var ImmutableComplexNumber $right */
            $thatRealPart = $right->getRealPart();
            /** @var DecimalInterface|FractionInterface $thatImaginaryPart */
            $thatImaginaryPart = $right->getImaginaryPart();

            if ($left->isImaginary() && $thatImaginaryPart instanceof FractionInterface && !($left instanceof FractionInterface)) {
                /** @var FractionInterface $thatImaginaryPart */
                $thatImaginaryPart = $thatImaginaryPart->asDecimal();
            }

            if ($left->isReal() && $thatRealPart instanceof FractionInterface && !($left instanceof FractionInterface)) {
                /** @var FractionInterface $thatRealPart */
                $thatRealPart = $thatRealPart->asDecimal();
            }
        } elseif ($right instanceof FractionInterface) {
            if ($left instanceof FractionInterface) {
                $thatRealPart = $right->isReal() ? $right : new ImmutableFraction(Numbers::makeZero(), Numbers::makeOne());
                $thatImaginaryPart = $right->isImaginary() ? $right : new ImmutableFraction(Numbers::makeZero(), Numbers::makeOne());
            } else {
                $thatRealPart = $right->isReal() ? $right->asDecimal() : Numbers::make(Numbers::IMMUTABLE, $identity, $left->getPrecision());
                $thatImaginaryPart = $right->isImaginary() ? $right->asDecimal() : Numbers::make(Numbers::IMMUTABLE, $identity, $left->getPrecision());
            }
        } else {
            $thatRealPart = $right->isReal() ? $right : Numbers::make(Numbers::IMMUTABLE, $identity, $left->getPrecision());
            $thatImaginaryPart = $right->isImaginary() ? $right : Numbers::make(Numbers::IMMUTABLE, $identity, $left->getPrecision());
        }

        $thisRealPart = $left->isReal() ? $left : Numbers::make(Numbers::IMMUTABLE, $identity, $left->getPrecision());
        $thisImaginaryPart = $left->isImaginary() ? $left : Numbers::make(Numbers::IMMUTABLE, $identity, $left->getPrecision());

        return [$thatRealPart, $thatImaginaryPart, $thisRealPart, $thisImaginaryPart];
    }

    /**
     * Allows you to set a mode on a number to select the calculation methods.
     *
     * MODE_PRECISION: Use what is necessary to provide an answer that is accurate to the precision setting.
     * MODE_NATIVE: Use built-in functions to perform the math, and accept whatever rounding or truncation this might cause.
     * MODE_SIMPLE_TRIG: Use simpler versions of the trig functions, which lose accuracy as significant figures grows.
     *
     * @param int $mode
     * @return $this
     */
    public function setMode(int $mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Returns the current value as a string.
     *
     * @return string
     */
    abstract public function getValue();

    /**
     * Allows the object to ignore PHP extensions (such a GMP) and use only the Fermat implementations. NOTE: This does
     * not ignore ext-bcmath or ext-decimal, as those are necessary for the string math itself.
     *
     * @param bool $flag
     * @return $this
     */
    public function setExtensions(bool $flag)
    {

        $this->extensions = $flag;

        return $this;

    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * Implemented to satisfy Hashable implementation
     *
     * @return string
     */
    public function hash(): string
    {
        return get_class($this).$this->getValue();
    }

    /**
     * Implemented to satisfy Hashable implementation
     *
     * @param mixed $object
     * @return bool
     */
    public function equals($object): bool
    {
        try {
            $reflection = new \ReflectionClass($object);
        } catch (\ReflectionException $exception) {
            return false;
        }

        if ($reflection->implementsInterface(NumberInterface::class) || is_numeric($object)) {
            return $this->isEqual($object);
        } else {
            return false;
        }
    }

    /**
     * This function returns true if the number is imaginary, and false in the number is real or complex
     *
     * @return bool
     */
    public function isImaginary(): bool
    {
        return $this->imaginary;
    }

    /**
     * This function returns true if the number is real, and false if the number is imaginary or complex
     *
     * @return bool
     */
    public function isReal(): bool
    {
        return !$this->imaginary;
    }

    public function asReal(): string
    {
        return $this->getAsBaseTenRealNumber();
    }

    abstract public function getAsBaseTenRealNumber();

    abstract public function isComplex(): bool;

    public function asComplex(): ComplexNumber
    {
        if ($this->isReal()) {
            return new ImmutableComplexNumber(clone $this, Numbers::makeZero());
        } else {
            return new ImmutableComplexNumber(Numbers::makeZero(), clone $this);
        }
    }

}