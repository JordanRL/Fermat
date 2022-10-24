<?php


namespace Samsara\Fermat\Complex\Types\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Complex\Types\ComplexNumber;
use Samsara\Fermat\Complex\Values\ImmutableComplexNumber;
use Samsara\Fermat\Complex\Values\MutableComplexNumber;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\Fraction;
use Samsara\Fermat\Core\Types\Traits\InputNormalizationTrait;
use Samsara\Fermat\Core\Values\ImmutableFraction;
use Samsara\Fermat\Core\Values\MutableDecimal;
use Samsara\Fermat\Core\Values\MutableFraction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Complex
 */
trait ArithmeticComplexTrait
{

    use InputNormalizationTrait;
    use ArithmeticComplexHelperTrait;

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     *
     * @return MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
     */
    public function add(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {

        $scale = $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);
        [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 0, $this->getMode());
        [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thisNum, 0, $this->getMode());

        $newRealPart = $thisRealPart->add($thatRealPart);
        $newImaginaryPart = $thisImaginaryPart->add($thatImaginaryPart);

        return $this->helperComplexAddSub($newRealPart, $newImaginaryPart, $scale);

    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     *
     * @return MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
     */
    public function subtract(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        $scale = $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);
        [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 0, $this->getMode());
        [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thisNum, 0, $this->getMode());

        $newRealPart = $thisRealPart->subtract($thatRealPart);
        $newImaginaryPart = $thisImaginaryPart->subtract($thatImaginaryPart);

        return $this->helperComplexAddSub($newRealPart, $newImaginaryPart, $scale);

    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     */
    public function multiply(
        string|int|float|Decimal|Fraction|ComplexNumber $num
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        $scale = $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);
        [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 1, $this->getMode());
        [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thisNum, 1, $this->getMode());

        if ($thatNum->isComplex()) {
            return $this->helperMulComplex($thisRealPart, $thisImaginaryPart, $thatRealPart, $thatImaginaryPart, $scale);
        } else {
            $value1 = $thisRealPart->multiply($thatNum);
            $value2 = $thisImaginaryPart->multiply($thatNum);
        }

        return $this->helperMulDivPowReturn($value1, $value2, $scale);
    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @param int|null $scale
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     * @throws IncompatibleObjectState
     */
    public function divide(
        string|int|float|Decimal|Fraction|ComplexNumber $num,
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {

        $scale = $scale ?? $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);
        [$thisRealPart, $thisImaginaryPart] = self::partSelector($thisNum, $thatNum, 1, $this->getMode());
        [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thisNum, 1, $this->getMode());

        if ($thatNum->isComplex()) {
            $intScale = $scale + 2;
            $denominator = $thatRealPart->roundToScale($intScale)->pow(2)->add($thatImaginaryPart->asReal()->roundToScale($intScale)->pow(2));

            $partA = $thisRealPart->roundToScale($intScale)->multiply($thatRealPart->roundToScale($intScale))
                ->add($thisImaginaryPart->asReal()->roundToScale($intScale)->multiply($thatImaginaryPart->asReal()->roundToScale($intScale)))
                ->divide($denominator, $intScale);
            $partB = $thisImaginaryPart->asReal()->roundToScale($intScale)->multiply($thatRealPart->roundToScale($intScale))
                ->subtract($thisRealPart->roundToScale($intScale)->multiply($thatImaginaryPart->asReal()->roundToScale($intScale)))
                ->divide($denominator, $intScale)
                ->multiply('1i');
        } else {
            $partA = $thisRealPart->divide($thatNum, $scale+2);
            $partB = $thisImaginaryPart->divide($thatNum, $scale+2);
        }

        return $this->helperMulDivPowReturn($partA, $partB, $scale);

    }

    /**
     * @param string|int|float|Decimal|Fraction|ComplexNumber $num
     * @return static|ImmutableFraction|MutableFraction|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal|MutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function pow(
        string|int|float|Decimal|Fraction|ComplexNumber $num,
        ?int $scale = null
    ): MutableDecimal|ImmutableDecimal|MutableComplexNumber|ImmutableComplexNumber|MutableFraction|ImmutableFraction|static
    {
        $scale = $scale ?? $this->getScale();

        [$thisNum, $thatNum] = $this->translateToObjects($num);

        if ($thatNum->getValue(NumberBase::Ten) === '0') {
            return (new ImmutableDecimal('1', $scale))->setMode($this->getMode());
        }

        $internalScale = $scale ?? ($this->getScale() > $thatNum->getScale()) ? $this->getScale() : $thatNum->getScale();
        $internalScale += max($thisNum->getRealPart()->numberOfIntDigits(), $thisNum->getImaginaryPart()->numberOfIntDigits());
        $internalScale *= intval($thatNum->absValue());

        [$thatRealPart, $thatImaginaryPart] = self::partSelector($thatNum, $thisNum, 0, $this->getMode(), $internalScale);

        if ($thatNum->isReal() && $thatNum->isNatural() && $thatNum->isPositive()) {
            [$newRealPart, $newImaginaryPart] = $this->helperPowPolarRotate($thisNum, $thatNum, $scale);
        } else {
            [$newRealPart, $newImaginaryPart] = $this->helperPowPolar($thatRealPart, $thatImaginaryPart, $internalScale);
        }

        return $this->helperMulDivPowReturn($newRealPart, $newImaginaryPart, $scale);

    }

    /**
     * @param int|null $scale
     * @return static|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function sqrt(
        ?int $scale = null
    ): static|ImmutableComplexNumber|MutableComplexNumber|ImmutableDecimal
    {

        $scale = $scale ?? $this->getScale();

        $thisNum = self::normalizeObject($this, $this->getMode());

        [$newRealPart, $newImaginaryPart] = $this->helperSquareRoot($thisNum, $scale);

        $newRealPart = $newRealPart->roundToScale($scale);
        $newImaginaryPart = $newImaginaryPart->roundToScale($scale);

        if (!$newRealPart->isEqual(0) && !$newImaginaryPart->isEqual(0)) {
            return $this->setValue($newRealPart, $newImaginaryPart);
        }

        $newValue = $newRealPart->isEqual(0) ? $newImaginaryPart : $newRealPart;

        return new ImmutableDecimal($newValue->getValue(), $scale);

    }

    /**
     * @param int|ImmutableDecimal $root
     * @param int|null $scale
     * @return ImmutableComplexNumber[]
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws OptionalExit
     */
    public function nthRoots(int|ImmutableDecimal $root, ?int $scale): array
    {

        [$thisNum, $root] = $this->translateToObjects($root);
        $scale = $scale ?? $this->getScale();

        if (!$root->isNatural() || $root->isNegative()) {
            throw new IntegrityConstraint(
                'You may only provide positive integer inputs for the root index',
                'None'
            );
        }

        $roots = [];

        for ($i=0;$root->isGreaterThan($i);$i++) {
            [$newRealPart, $newImaginaryPart] = $this->helperRootsPolarRotate($thisNum, $root, $i, $scale);

            $roots[] = (new ImmutableComplexNumber($newRealPart, $newImaginaryPart))->setMode($this->getMode());
        }

        return $roots;

    }

}