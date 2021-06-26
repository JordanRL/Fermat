<?php


namespace Samsara\Fermat\Types\Traits\Arithmetic;


use Composer\InstalledVersions;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\ComplexNumbers;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\ComplexNumberInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Base\Selectable;
use Samsara\Fermat\Types\ComplexNumber;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate;
use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\MutableDecimal;
use Samsara\Fermat\Values\MutableFraction;

trait ArithmeticSelectionTrait
{

    /** @var int */
    protected $calcMode;
    /** @var array */
    protected $modeRegister;

    /**
     * @param $left
     * @param $right
     * @param int $identity
     *
     * @return array
     * @throws IntegrityConstraint
     */
    protected function translateToParts($left, $right, $identity = 0): array
    {
        switch (gettype($right)) {
            case 'integer':
            case 'double':
                $right = Numbers::make(Numbers::IMMUTABLE, $right);
                break;

            case 'string':
                $right = self::stringSelector($right);
                break;

            case 'object':
                $right = !($right instanceof NumberInterface) ? Numbers::makeOrDont(Numbers::IMMUTABLE, $right) : $right;
                break;
        }

        [$thatRealPart, $thatImaginaryPart, $right] = self::rightSelector($left, $right, $identity);

        [$thisRealPart, $thisImaginaryPart] = self::leftSelector($left, $identity);

        return [$thatRealPart, $thatImaginaryPart, $thisRealPart, $thisImaginaryPart, $right];
    }

    /**
     * @param string $input
     * @return CoordinateInterface|FractionInterface|NumberInterface|ComplexNumber|CartesianCoordinate|ImmutableDecimal|ImmutableFraction|MutableDecimal|MutableFraction
     * @throws IntegrityConstraint
     */
    protected static function stringSelector(string $input)
    {

        $input = trim($input);
        if (strpos($input, '/') !== false) {
            $input = Numbers::makeFractionFromString(Numbers::IMMUTABLE_FRACTION, $input);
        } elseif (strrpos($input, '+') || strrpos($input, '-')) {
            if (!(InstalledVersions::isInstalled("samsara/fermat-complex-numbers"))) {
                throw new MissingPackage(
                    'Creating complex numbers is unsupported in Fermat without modules.',
                    'Install the samsara/fermat-complex-numbers package using composer.',
                    'An attempt was made to create a ComplexNumber instance without having the Complex Numbers module. Please install the samsara/fermat-complex-numbers package using composer.'
                );
            }

            $input = ComplexNumbers::make(ComplexNumbers::IMMUTABLE_COMPLEX, $input);
        } else {
            $input = Numbers::make(Numbers::IMMUTABLE, $input);
        }

        return $input;

    }

    protected static function rightSelector($left, $right, $identity)
    {

        if ($right instanceof ComplexNumberInterface) {
            /** @var ComplexNumberInterface $right */
            $thatRealPart = $right->getRealPart();
            /** @var ComplexNumberInterface $right */
            $thatImaginaryPart = $right->getImaginaryPart();
        } else {
            if ($right instanceof FractionInterface) {
                if ($left instanceof FractionInterface) {
                    $rightPart = $right;
                    $otherPart = new ImmutableFraction(Numbers::makeZero(), Numbers::makeOne());
                } else {
                    $rightPart = $right->asDecimal();
                    $otherPart = Numbers::make(Numbers::IMMUTABLE, $identity, $left->getScale());

                    $right = $right->asDecimal();
                }
            } else {
                $rightPart = $right;
                $otherPart = Numbers::make(Numbers::IMMUTABLE, $identity, $left->getScale());
            }

            $thatRealPart = $right->isReal() ? $rightPart : $otherPart;
            $thatImaginaryPart = $right->isImaginary() ? $rightPart : $otherPart->multiply('i');
        }

        return [$thatRealPart, $thatImaginaryPart, $right];

    }

    protected static function leftSelector($left, $identity)
    {

        if ($left instanceof ComplexNumberInterface) {
            $thisRealPart = $left->getRealPart();
            $thisImaginaryPart = $left->getImaginaryPart();
        } else {
            $thisRealPart = $left->isReal() ? $left : Numbers::make(Numbers::IMMUTABLE, $identity, $left->getScale());
            $thisImaginaryPart = $left->isImaginary() ? $left : Numbers::make(Numbers::IMMUTABLE, $identity.'i', $left->getScale());
        }

        return [$thisRealPart, $thisImaginaryPart];

    }

    protected function addSelector(DecimalInterface $num)
    {
        return match ($this->calcMode) {
            Selectable::CALC_MODE_PRECISION => $this->addScale($num),
            Selectable::CALC_MODE_NATIVE => $this->addNative($num),
            default => $this->{$this->modeRegister[Selectable::CALC_MODE_FALLBACK]['add']}($num),
        };
    }

    protected function subtractSelector(DecimalInterface $num)
    {
        return match ($this->calcMode) {
            Selectable::CALC_MODE_PRECISION => $this->subtractScale($num),
            Selectable::CALC_MODE_NATIVE => $this->subtractNative($num),
            default => $this->{$this->modeRegister[Selectable::CALC_MODE_FALLBACK]['subtract']}($num),
        };
    }

    protected function multiplySelector(DecimalInterface $num)
    {
        return match ($this->calcMode) {
            Selectable::CALC_MODE_PRECISION => $this->multiplyScale($num),
            Selectable::CALC_MODE_NATIVE => $this->multiplyNative($num),
            default => $this->{$this->modeRegister[Selectable::CALC_MODE_FALLBACK]['multiply']}($num),
        };
    }

    protected function divideSelector(DecimalInterface $num, int $scale)
    {
        return match ($this->calcMode) {
            Selectable::CALC_MODE_PRECISION => $this->divideScale($num, $scale),
            Selectable::CALC_MODE_NATIVE => $this->divideNative($num),
            default => $this->{$this->modeRegister[Selectable::CALC_MODE_FALLBACK]['divide']}($num, $scale),
        };
    }

    protected function powSelector(DecimalInterface $num)
    {
        return match ($this->calcMode) {
            Selectable::CALC_MODE_PRECISION => $this->powScale($num),
            Selectable::CALC_MODE_NATIVE => $this->powNative($num),
            default => $this->{$this->modeRegister[Selectable::CALC_MODE_FALLBACK]['pow']}($num),
        };
    }

    protected function sqrtSelector(int $scale)
    {
        return match ($this->calcMode) {
            Selectable::CALC_MODE_PRECISION => $this->sqrtScale($scale),
            Selectable::CALC_MODE_NATIVE => $this->sqrtNative(),
            default => $this->{$this->modeRegister[Selectable::CALC_MODE_FALLBACK]['sqrt']}($scale),
        };
    }

}