<?php

namespace Samsara\Fermat\Core\Types\Traits\Trigonometry;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Enums\RoundingMode;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\SequenceProvider;
use Samsara\Fermat\Core\Provider\SeriesProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
trait InverseTrigonometryScaleTrait
{

    use InverseTrigonometryHelpersTrait;

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

        $thisNum = Numbers::makeOrDont(Numbers::IMMUTABLE, $this->absValue());

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

        return $this->helperArcsecArccsc($zeroTerm, CalcOperation::ArcSec, $intScale);
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

        return $this->helperArcsecArccsc($zeroTerm, CalcOperation::ArcCsc, $intScale);
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

    protected function helperArcsecArccsc(ImmutableDecimal $zeroTerm, CalcOperation $operation, int $scale): string
    {
        $one = Numbers::makeOne($scale);
        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);
        $aPart = $this->helperSecCscAPart($one, $z, $scale);

        $bPart = $this->helperSecCscBPart($scale, $zeroTerm);

        $answer = SeriesProvider::generalizedContinuedFraction(
            $aPart,
            $bPart,
            $scale * 2,
            $scale,
            match ($operation) {
                CalcOperation::ArcCsc => SeriesProvider::SUM_MODE_ALT_FIRST_ADD,
                CalcOperation::ArcSec => SeriesProvider::SUM_MODE_SUB,
            }
        );

        return $answer->getAsBaseTenRealNumber();
    }

    /**
     * @param ImmutableDecimal $one
     * @param ImmutableDecimal $z
     * @param int|null $intScale
     * @return callable|ContinuedFractionTermInterface
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperSecCscAPart(
        ImmutableDecimal $one, 
        ImmutableDecimal $z, 
        ?int $intScale): callable|ContinuedFractionTermInterface
    {
        $oneDivZSquared = $one->divide($z->pow(2));
        $oneDivZ = $one->divide($z, $intScale);

        /**
         * @package Samsara\Fermat\Core
         */
        return new class($oneDivZSquared, $oneDivZ, $one) implements ContinuedFractionTermInterface {
            private ImmutableDecimal $oneDivZSq;
            private ImmutableDecimal $oneDivZ;
            private ImmutableDecimal $one;

            /**
             * @param ImmutableDecimal $oneDivZSq
             * @param ImmutableDecimal $oneDivZ
             * @param ImmutableDecimal $one
             */
            public function __construct(ImmutableDecimal $oneDivZSq, ImmutableDecimal $oneDivZ, ImmutableDecimal $one)
            {
                $this->oneDivZSq = $oneDivZSq;
                $this->oneDivZ = $oneDivZ;
                $this->one = $one;
            }

            /**
             * @param int $n
             * @return ImmutableDecimal
             */
            public function __invoke(int $n): ImmutableDecimal
            {
                if ($n == 1) {
                    return $this->oneDivZ->multiply($this->one->subtract($this->oneDivZSq)->sqrt());
                }

                $subterm = floor($n / 2);

                return $this->oneDivZSq->multiply(
                    2 * $subterm - 1
                )->multiply(2 * $subterm);
            }
        };
    }

    /**
     * @param int $intScale
     * @param ImmutableDecimal $zeroTerm
     * @return callable|ContinuedFractionTermInterface
     */
    protected function helperSecCscBPart(
        int $intScale,
        ImmutableDecimal $zeroTerm
    ): callable|ContinuedFractionTermInterface
    {
        /**
         * @package Samsara\Fermat\Core
         */
        return new class($intScale, $zeroTerm) implements ContinuedFractionTermInterface {
            private ImmutableDecimal $zeroTerm;
            private int $scale;

            public function __construct(int $scale, ImmutableDecimal $zeroTerm)
            {
                $this->zeroTerm = $zeroTerm;
                $this->scale = $scale;
            }

            public function __invoke(int $n): ImmutableDecimal
            {
                if ($n == 0) {
                    return $this->zeroTerm;
                }

                return new ImmutableDecimal(($n * 2) - 1, $this->scale);
            }
        };
    }

}