<?php

namespace Samsara\Fermat\Core\Types\Traits\Arithmetic;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcMode;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\CalculationModeProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 *
 */
trait ArithmeticSelectionTrait
{

    /**
     * @param DecimalInterface $num
     * @return CalcMode
     * @throws IntegrityConstraint
     */
    protected function modeSelectorForArithmetic(DecimalInterface $num, CalcOperation $operation): CalcMode
    {
        $thisAbs = Numbers::makeOrDont(Numbers::IMMUTABLE, $this->absValue());
        $thatAbs = Numbers::make(Numbers::IMMUTABLE, $num->absValue());
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
        $nativeInt = $nativeInt && $thisAbs->isLessThan(CalculationModeProvider::PHP_INT_MAX_HALF);
        $nativeInt = $nativeInt && $thatAbs->isLessThan(CalculationModeProvider::PHP_INT_MAX_HALF);

        if (
            ($nativeInt && ($operation == CalcOperation::Addition || $operation == CalcOperation::Subtraction))
            || ($nativeScale && $nativeValues)
        ) {
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
        $calcMode = $this->getResolvedMode();
        if ($calcMode == CalcMode::Auto) {
            $value = $this->addGMP($num);

            if ($value !== false) {
                return $value;
            }

            $calcMode = $this->modeSelectorForArithmetic($num, CalcOperation::Addition);
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
        $calcMode = $this->getResolvedMode();
        if ($calcMode == CalcMode::Auto) {
            $value = $this->subtractGMP($num);

            if ($value !== false) {
                return $value;
            }

            $calcMode = $this->modeSelectorForArithmetic($num, CalcOperation::SquareRoot);
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
        $calcMode = $this->getResolvedMode();
        if ($calcMode == CalcMode::Auto) {
            $value = $this->multiplyGMP($num);

            if ($value !== false) {
                return $value;
            }

            $calcMode = $this->modeSelectorForArithmetic($num, CalcOperation::Multiplication);
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
        $calcMode = $this->getResolvedMode();
        if ($calcMode == CalcMode::Auto) {
            $value = $this->divideGMP($num);

            if ($value !== false) {
                return $value;
            }

            $calcMode = $this->modeSelectorForArithmetic($num, CalcOperation::Division);
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
        $calcMode = $this->getResolvedMode();
        if ($num->isEqual(0)) {
            return '1';
        }

        if ($calcMode == CalcMode::Auto) {
            $value = $this->powGMP($num);

            if ($value !== false) {
                return $value;
            }

            $calcMode = $this->modeSelectorForArithmetic($num, CalcOperation::Power);
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
        $calcMode = $this->getResolvedMode();

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