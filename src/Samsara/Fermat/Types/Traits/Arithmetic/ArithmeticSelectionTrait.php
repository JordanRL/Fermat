<?php


namespace Samsara\Fermat\Types\Traits\Arithmetic;


use ReflectionException;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\ComplexNumbers;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\ComplexNumberInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Base\Selectable;
use Samsara\Fermat\Values\ImmutableFraction;

trait ArithmeticSelectionTrait
{

    /** @var int */
    protected $mode;
    /** @var array */
    protected $modeRegister;

    /**
     * @param $left
     * @param $right
     * @param int $identity
     *
     * @return array
     * @throws ReflectionException
     * @throws IntegrityConstraint
     */
    protected function translateToParts($left, $right, $identity = 0)
    {
        if (is_numeric($right)) {
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
        } elseif (!($right instanceof NumberInterface)) {
            $right = Numbers::makeOrDont(Numbers::IMMUTABLE, $right);
        }

        if ($right instanceof FractionInterface) {
            if ($left instanceof FractionInterface) {
                $thatRealPart = $right->isReal() ? $right : new ImmutableFraction(Numbers::makeZero(), Numbers::makeOne());
                $thatImaginaryPart = $right->isImaginary() ? $right : new ImmutableFraction(Numbers::makeZero(), Numbers::makeOne());
            } else {
                $thatRealPart = $right->isReal() ? $right->asDecimal() : Numbers::make(Numbers::IMMUTABLE, $identity, $left->getPrecision());
                $thatImaginaryPart = $right->isImaginary() ? $right->asDecimal() : Numbers::make(Numbers::IMMUTABLE, $identity.'i', $left->getPrecision());
                $right = $right->asDecimal();
            }
        } elseif ($right instanceof ComplexNumberInterface) {
            /** @var ComplexNumberInterface $right */
            $thatRealPart = $right->getRealPart();
            /** @var ComplexNumberInterface $right */
            $thatImaginaryPart = $right->getImaginaryPart();
        } else {
            $thatRealPart = $right->isReal() ? $right : Numbers::make(Numbers::IMMUTABLE, $identity, $left->getPrecision());
            $thatImaginaryPart = $right->isImaginary() ? $right : Numbers::make(Numbers::IMMUTABLE, $identity.'i', $left->getPrecision());
        }

        if ($this instanceof ComplexNumberInterface) {
            $thisRealPart = $this->getRealPart();
            $thisImaginaryPart = $this->getImaginaryPart();
        } else {
            $thisRealPart = $left->isReal() ? $left : Numbers::make(Numbers::IMMUTABLE, $identity, $left->getPrecision());
            $thisImaginaryPart = $left->isImaginary() ? $left : Numbers::make(Numbers::IMMUTABLE, $identity.'i', $left->getPrecision());
        }

        return [$thatRealPart, $thatImaginaryPart, $thisRealPart, $thisImaginaryPart, $right];
    }

    protected function addSelector(DecimalInterface $num)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->addPrecision($num);
                break;

            case Selectable::MODE_NATIVE:
                return $this->addNative($num);
                break;

            default:
                return $this->{$this->modeRegister[Selectable::MODE_FALLBACK]['add']}($num);
                break;
        }
    }

    protected function subtractSelector(DecimalInterface $num)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->subtractPrecision($num);
                break;

            case Selectable::MODE_NATIVE:
                return $this->subtractNative($num);
                break;

            default:
                return $this->{$this->modeRegister[Selectable::MODE_FALLBACK]['subtract']}($num);
                break;
        }
    }

    protected function multiplySelector(DecimalInterface $num)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->multiplyPrecision($num);
                break;

            case Selectable::MODE_NATIVE:
                return $this->multiplyNative($num);
                break;

            default:
                return $this->{$this->modeRegister[Selectable::MODE_FALLBACK]['multiply']}($num);
                break;
        }
    }

    protected function divideSelector(DecimalInterface $num, int $precision)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->dividePrecision($num, $precision);
                break;

            case Selectable::MODE_NATIVE:
                return $this->divideNative($num);
                break;

            default:
                return $this->{$this->modeRegister[Selectable::MODE_FALLBACK]['divide']}($num, $precision);
                break;
        }
    }

    protected function powSelector(DecimalInterface $num)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->powPrecision($num);
                break;

            case Selectable::MODE_NATIVE:
                return $this->powNative($num);
                break;

            default:
                return $this->{$this->modeRegister[Selectable::MODE_FALLBACK]['pow']}($num);
                break;
        }
    }

    protected function sqrtSelector(int $precision)
    {
        switch ($this->mode) {
            case Selectable::MODE_PRECISION:
                return $this->sqrtPrecision($precision);
                break;

            case Selectable::MODE_NATIVE:
                return $this->sqrtNative();
                break;

            default:
                return $this->{$this->modeRegister[Selectable::MODE_FALLBACK]['sqrt']}($precision);
                break;
        }
    }

}