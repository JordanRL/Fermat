<?php /** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

/** @noinspection ALL */


namespace Samsara\Fermat\Types\Traits\Arithmetic;


use Composer\InstalledVersions;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\ComplexNumbers;
use Samsara\Fermat\Enums\CalcMode;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\CalculationModeProvider;
use Samsara\Fermat\Provider\RoundingProvider;
use Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\ComplexNumberInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\ComplexNumber;
use Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate;
use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Values\ImmutableFraction;
use Samsara\Fermat\Values\MutableDecimal;
use Samsara\Fermat\Values\MutableFraction;

/**
 *
 */
trait ArithmeticSelectionTrait
{

    /**
     * @param $left
     * @param $right
     * @param int $identity
     *
     * @return array
     * @throws IntegrityConstraint
     */
    protected function translateToParts($left, $right, int $identity = 0): array
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
                if ($right instanceof MutableDecimal) {
                    $right = Numbers::makeOrDont(Numbers::IMMUTABLE, $right);
                } elseif ($right instanceof MutableFraction) {
                    $right = Numbers::makeOrDont(Numbers::IMMUTABLE_FRACTION, $right);
                } else {
                    $right = !($right instanceof NumberInterface) ? Numbers::makeOrDont(Numbers::IMMUTABLE, $right) : $right;
                }
                break;
        }

        [$thatRealPart, $thatImaginaryPart, $right] = self::rightSelector($left, $right, $identity);

        [$thisRealPart, $thisImaginaryPart] = self::leftSelector($left, $identity);

        return [$thatRealPart, $thatImaginaryPart, $thisRealPart, $thisImaginaryPart, $right];
    }

    /**
     * @param string $input
     * @return CoordinateInterface|FractionInterface|NumberInterface|ComplexNumber|CartesianCoordinate|ImmutableDecimal|ImmutableFraction|MutableDecimal|MutableFraction
     * @throws IntegrityConstraint|MissingPackage
     */
    protected static function stringSelector(string $input)
    {

        $input = trim($input);
        if (str_contains($input, '/')) {
            $input = Numbers::makeFractionFromString(Numbers::IMMUTABLE_FRACTION, $input);
        } elseif (strrpos($input, '+') || strrpos($input, '-')) {
            if (!(InstalledVersions::isInstalled('samsara/fermat-complex-numbers'))) {
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

    /**
     * @param $left
     * @param $right
     * @param $identity
     * @return array
     * @throws IntegrityConstraint
     */
    protected static function rightSelector($left, $right, $identity): array
    {

        if ($right instanceof ComplexNumberInterface) {
            $thatRealPart = $right->getRealPart();
            $thatImaginaryPart = $right->getImaginaryPart();
        } else {
            if ($right instanceof FractionInterface) {
                if ($left instanceof FractionInterface) {
                    $rightPart = $right;

                    $thatRealPart = $right->isReal() ? $rightPart : new ImmutableFraction(new ImmutableDecimal($identity), new ImmutableDecimal(1));
                    $thatImaginaryPart = $right->isImaginary() ? $rightPart : new ImmutableFraction(new ImmutableDecimal($identity.'i'), new ImmutableDecimal(1));
                } else {
                    $rightPart = $right->asDecimal();
                    $right = $right->asDecimal();

                    $thatRealPart = $right->isReal() ? $rightPart : new ImmutableDecimal($identity, $left->getScale());
                    $thatImaginaryPart = $right->isImaginary() ? $rightPart : new ImmutableDecimal($identity.'i', $left->getScale());
                }
            } else {
                $rightPart = $right;

                $thatRealPart = $right->isReal() ? $rightPart : new ImmutableDecimal($identity, $left->getScale());
                $thatImaginaryPart = $right->isImaginary() ? $rightPart : new ImmutableDecimal($identity.'i', $left->getScale());
            }
        }

        return [$thatRealPart, $thatImaginaryPart, $right];

    }

    /**
     * @param $left
     * @param $identity
     * @return array
     * @throws IntegrityConstraint
     */
    protected static function leftSelector($left, $identity): array
    {

        if ($left instanceof ComplexNumberInterface) {
            $thisRealPart = $left->getRealPart();
            $thisImaginaryPart = $left->getImaginaryPart();
        } else {
            $thisRealPart = $left->isReal() ? $left : new ImmutableDecimal($identity, $left->getScale());
            $thisImaginaryPart = $left->isImaginary() ? $left : new ImmutableDecimal($identity.'i', $left->getScale());
        }

        return [$thisRealPart, $thisImaginaryPart];

    }

    /**
     * @param DecimalInterface $num
     * @return CalcMode
     * @throws IntegrityConstraint
     */
    protected function modeSelectorForArithmetic(DecimalInterface $num): CalcMode
    {
        $thisNum = Numbers::makeOrDont(Numbers::IMMUTABLE, $this->getValue(NumberBase::Ten));
        /*
         * Floats have variable density depending on where the exponent is. However, the exponent is also
         * base-2, while our scale is base-10. Thus, in order to determine if the requested scale is within
         * the range of float to be accurate, we would ideally need to look at the log2() value of the result.
         *
         * In practice however this is a very expensive operation to perform twice for every calculation, and we
         * might have overflows or underflows that are difficult to determine.
         *
         * So, to compromise we are going to set the maximum scale for native within 'auto' to 10, and then cap
         * result at the maximum value of 10,000, which is one order of magnitude below the maximum rounding error
         * of a double precision float as implemented in PHP.
         */
        $nativeScale = $this->getScale() <= 10 && $num->getScale() <= 10;
        $nativeValues = $this->isLessThan(10000) && $num->isLessThan(10000) && ($this->isFloat() || $num->isFloat());

        /*
         * We still need to check for integers, since it's possible the GMP extension won't be installed.
         */
        $nativeInt = $this->isInt() && $num->isInt();
        $nativeInt = $nativeInt && $thisNum->abs()->isLessThan(CalculationModeProvider::PHP_INT_MAX_HALF);
        $nativeInt = $nativeInt && $num->abs()->isLessThan(CalculationModeProvider::PHP_INT_MAX_HALF);

        if ($nativeInt || ($nativeScale && $nativeValues)) {
            return CalcMode::Native;
        } else {
            return CalcMode::Precision;
        }
    }

    /**
     * @param DecimalInterface $num
     * @return string
     */
    protected function addSelector(DecimalInterface $num): string
    {
        $calcMode = $this->getMode();
        if ($calcMode == CalcMode::Auto) {
            $value = $this->addGMP($num);

            if ($value !== false) {
                return $value;
            }

            $calcMode = $this->modeSelectorForArithmetic($num);
        }

        return match ($calcMode) {
            CalcMode::Native => $this->addNative($num),
            default => $this->addScale($num),
        };
    }

    /**
     * @param DecimalInterface $num
     * @return string
     */
    protected function subtractSelector(DecimalInterface $num): string
    {
        $calcMode = $this->getMode();
        if ($calcMode == CalcMode::Auto) {
            $value = $this->subtractGMP($num);

            if ($value !== false) {
                return $value;
            }

            $calcMode = $this->modeSelectorForArithmetic($num);
        }

        return match ($calcMode) {
            CalcMode::Native => $this->subtractNative($num),
            default => $this->subtractScale($num),
        };
    }

    /**
     * @param DecimalInterface $num
     * @return string
     */
    protected function multiplySelector(DecimalInterface $num): string
    {
        $calcMode = $this->getMode();
        if ($calcMode == CalcMode::Auto) {
            $value = $this->multiplyGMP($num);

            if ($value !== false) {
                return $value;
            }

            $calcMode = $this->modeSelectorForArithmetic($num);
        }

        return match ($calcMode) {
            CalcMode::Native => $this->multiplyNative($num),
            default => $this->multiplyScale($num),
        };
    }

    /**
     * @param DecimalInterface $num
     * @param int|null $scale
     * @return string
     */
    protected function divideSelector(DecimalInterface $num, ?int $scale): string
    {
        $calcMode = $this->getMode();
        if ($calcMode == CalcMode::Auto) {
            $value = $this->divideGMP($num);

            if ($value !== false) {
                return $value;
            }

            $calcMode = $this->modeSelectorForArithmetic($num);
        }

        return match ($calcMode) {
            CalcMode::Native => $this->divideNative($num),
            default => $this->divideScale($num, $scale),
        };
    }

    /**
     * @param DecimalInterface $num
     * @return string
     * @throws IntegrityConstraint
     */
    protected function powSelector(DecimalInterface $num): string
    {
        $calcMode = $this->getMode();
        if ($num->isEqual(0)) {
            return '1';
        }

        if ($calcMode == CalcMode::Auto) {
            $value = $this->powGMP($num);

            if ($value !== false) {
                return $value;
            }

            $calcMode = $this->modeSelectorForArithmetic($num);
        }

        return match ($calcMode) {
            CalcMode::Native => $this->powNative($num),
            default => $this->powScale($num),
        };
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function sqrtSelector(?int $scale): string
    {
        $calcMode = $this->getMode();
        if ($calcMode == CalcMode::Auto) {
            $value = $this->sqrtGMP();


            if ($value !== false) {
                return $value;
            }

            $scale = $scale ?? $this->getScale();

            if ($scale > 10 || $this->isGreaterThan(10000) || $this->isLessThan(0)) {
                $calcMode = CalcMode::Precision;
            } else {
                $calcMode = CalcMode::Native;
            }
        }

        return match ($calcMode) {
            CalcMode::Native => $this->sqrtNative(),
            default => $this->sqrtScale($scale),
        };
    }

}