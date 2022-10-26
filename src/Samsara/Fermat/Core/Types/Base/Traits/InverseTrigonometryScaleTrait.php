<?php

namespace Samsara\Fermat\Core\Types\Base\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Base\Traits\InverseTrigonometryHelpersTrait;
use Samsara\Fermat\Core\Types\Traits\NumberNormalizationTrait;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
trait InverseTrigonometryScaleTrait
{

    use InverseTrigonometryHelpersTrait;
    use NumberNormalizationTrait;

    /**
     * @param int|null $scale
     * @return string
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function arcsinScale(int $scale = null): string
    {

        $scale = $scale ?? $this->getScale();

        if ($this->isEqual(1) || $this->isEqual(-1)) {
            $pi = Numbers::makePi();
            $answer = $pi->divide(2, $scale+2);
            if ($this->isNegative()) {
                $answer = $answer->multiply(-1);
            }
        } elseif ($this->isEqual(0)) {
            $answer = Numbers::makeZero();
        } else {
            $intScale = $scale + 2;
            $num = new ImmutableDecimal($this->getValue(NumberBase::Ten), $intScale);

            $answer = $this->helperArcsinGCF($num, $intScale);
        }

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function arccosScale(int $scale = null): string
    {

        $scale = $scale ?? $this->getScale();
        $pi = Numbers::makePi($scale+2);
        $piDivTwo = $pi->divide(2, $scale+2);

        if ($this->isEqual(-1)) {
            $answer = Numbers::makePi($scale+1);
        } elseif ($this->isEqual(0)) {
            $answer = $piDivTwo;
        } elseif ($this->isEqual(1)) {
            $answer = Numbers::makeZero();
        } else {
            $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale + 2);

            $answer = $piDivTwo->subtract($z->arcsin($scale+2));
        }

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function arctanScale(int $scale = null): string
    {
        $intScale = ($scale ?? $this->getScale()) + 2;

        $thisNum = self::normalizeObject(new ImmutableDecimal($this->absValue()), $this->getMode(), $intScale);

        if ($thisNum->isGreaterThan(1)) {
            $one = Numbers::makeOne($intScale);
            $adjustedNum = $one->divide($thisNum, $intScale);
        } else {
            $adjustedNum = $thisNum;
        }

        $answer = $this->helperArctanGCF($adjustedNum, $scale);

        if ($thisNum->isGreaterThan(1)) {
            $piDiv2 = Numbers::makePi($intScale)->multiply('0.5');
            $answer = $piDiv2->subtract($answer);
        }

        if ($this->isNegative()) {
            $answer = $answer->multiply(-1);
        }

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function arccotScale(int $scale = null): string
    {

        $scale = $scale ?? $this->getScale();

        $piDivTwo = Numbers::makePi($scale + 2)->divide(2, $scale + 2);

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this->absValue(), $scale + 2);

        $arctan = $z->arctan($scale+2, false);

        $answer = $piDivTwo->subtract($arctan);

        if ($this->isNegative()) {
            $answer = $answer->multiply(-1);
        }

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function arcsecScale(int $scale = null): string
    {
        $scale = $scale ?? $this->getScale();
        $intScale = $scale + 2;
        $zeroTerm = Numbers::makePi($intScale)->divide(2, $intScale);

        return $this->helperArcsecArccscScale($zeroTerm, CalcOperation::ArcSec, $intScale);
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function arccscScale(int $scale = null): string
    {
        $scale = $scale ?? $this->getScale();
        $intScale = $scale + 2;
        $zeroTerm = Numbers::makeZero($this->scale);

        return $this->helperArcsecArccscScale($zeroTerm, CalcOperation::ArcCsc, $intScale);
    }

    /**
     * @param int $scale
     * @param RoundingMode|null $mode
     * @return ImmutableDecimal|MutableDecimal|static
     */
    abstract public function roundToScale(int $scale, ?RoundingMode $mode = null): ImmutableDecimal|MutableDecimal|static;

    /**
     * @param int $scale
     * @return ImmutableDecimal|MutableDecimal|static
     */
    abstract public function truncateToScale(int $scale): ImmutableDecimal|MutableDecimal|static;

    /**
     * @return int|null
     */
    abstract public function getScale(): ?int;

}