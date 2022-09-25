<?php

namespace Samsara\Fermat\Types\Traits\Trigonometry;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Enums\RoundingMode;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 *
 */
trait InverseTrigonometryScaleTrait
{

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
            $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
            $addScale = $abs->asInt() > $abs->getScale() ? $abs->asInt() : $abs->getScale();
            $intScale = $scale + $addScale;
            $x = new ImmutableDecimal($this->getValue(NumberBase::Ten), $intScale);
            $x2 = $x->pow(2);
            $one = new ImmutableDecimal(1, $intScale);

            $aPart = new class($x2, $intScale) implements ContinuedFractionTermInterface{
                private ImmutableDecimal $x2;
                private ImmutableDecimal $negTwo;

                /**
                 * @param ImmutableDecimal $x2
                 * @param int $intScale
                 */
                public function __construct(ImmutableDecimal $x2, int $intScale)
                {
                    $this->x2 = $x2;
                    $this->negTwo = new ImmutableDecimal(-2, $intScale);
                }

                /**
                 * @param int $n
                 * @return ImmutableDecimal
                 */
                public function __invoke(int $n): ImmutableDecimal
                {
                    $subterm = floor(($n+1)/2);

                    return $this->negTwo->multiply(
                        2*$subterm-1
                    )->multiply($subterm)->multiply($this->x2);
                }
            };

            $bPart = new class() implements ContinuedFractionTermInterface{
                /**
                 * @param int $n
                 * @return ImmutableDecimal
                 */
                public function __invoke(int $n): ImmutableDecimal
                {
                    return SequenceProvider::nthOddNumber($n);
                }
            };

            $answer = SeriesProvider::generalizedContinuedFraction($aPart, $bPart, $intScale, $intScale);

            $answer = $x->multiply($one->subtract($x2)->sqrt($intScale))->divide($answer, $intScale);

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
     * @throws IncompatibleObjectState
     */
    protected function arctanScale(int $scale = null): string
    {

        $scale = $scale ?? $this->getScale();
        $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
        $intScale = $scale + 2;
        $terms = $abs->multiply($intScale+8)->asInt();
        $x = new ImmutableDecimal($this->getValue(NumberBase::Ten), $intScale);
        $aPart = new class($x) implements ContinuedFractionTermInterface {
            private ImmutableDecimal $x;

            /**
             * @param ImmutableDecimal $x
             */
            public function __construct(ImmutableDecimal $x)
            {
                $this->x = $x;
            }

            /**
             * @param int $n
             * @return ImmutableDecimal
             */
            public function __invoke(int $n): ImmutableDecimal
            {
                if ($n == 1) {
                    return $this->x;
                }

                return $this->x->multiply($n-1)->pow(2);
            }
        };

        $bPart = new class($intScale) implements ContinuedFractionTermInterface {
            private int $intScale;

            /**
             * @param int $intScale
             */
            public function __construct(int $intScale)
            {
                $this->intScale = $intScale;
            }

            /**
             * @param int $n
             * @return ImmutableDecimal
             */
            public function __invoke(int $n): ImmutableDecimal
            {
                if ($n == 0) {
                    return Numbers::makeZero($this->intScale);
                }

                return SequenceProvider::nthOddNumber($n - 1)->truncateToScale($this->intScale);
            }
        };

        $answer = SeriesProvider::generalizedContinuedFraction($aPart, $bPart, $terms, $intScale);

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

        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale + 2);

        $arctan = $z->arctan($scale+2, false);

        $answer = $piDivTwo->subtract($arctan);

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

        $one = Numbers::makeOne($intScale);
        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this->getValue(NumberBase::Ten), $intScale);

        //$answer = $one->divide($z, $scale + 2)->arccos($scale + 2);

        $oneDivZSquared = $one->divide($z->pow(2));
        $piDivTwo = Numbers::makePi($intScale)->divide(2, $intScale);
        $oneDivZ = $one->divide($z, $intScale);

        $aPart = new class($oneDivZSquared, $oneDivZ, $one) implements ContinuedFractionTermInterface {
            private ImmutableDecimal $oneDivZSq;
            private ImmutableDecimal $oneDivZ;
            private ImmutableDecimal $one;

            public function __construct(ImmutableDecimal $oneDivZSq, ImmutableDecimal $oneDivZ, ImmutableDecimal $one) {
                $this->oneDivZSq = $oneDivZSq;
                $this->oneDivZ = $oneDivZ;
                $this->one = $one;
            }

            public function __invoke(int $n): ImmutableDecimal
            {
                if ($n == 1) {
                    return $this->oneDivZ->multiply($this->one->subtract($this->oneDivZSq)->sqrt());
                }

                $subterm = floor($n/2);

                return $this->oneDivZSq->multiply(
                    2*$subterm-1
                )->multiply(2*$subterm);
            }
        };

        $bPart = new class($piDivTwo, $intScale) implements ContinuedFractionTermInterface {
            private ImmutableDecimal $piDivTwo;
            private int $scale;

            public function __construct(ImmutableDecimal $piDivTwo, int $scale) {
                $this->piDivTwo = $piDivTwo;
                $this->scale = $scale;
            }

            public function __invoke(int $n): ImmutableDecimal
            {
                if ($n == 0) {
                    return $this->piDivTwo;
                }

                return new ImmutableDecimal(($n*2)-1, $this->scale);
            }
        };

        $answer = SeriesProvider::generalizedContinuedFraction(
            $aPart,
            $bPart,
            $intScale * 2,
            $intScale,
            SeriesProvider::SUM_MODE_SUB
        );

        return $answer->getAsBaseTenRealNumber();

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

        $one = Numbers::makeOne($intScale);
        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $intScale);

        //$answer = $one->divide($z, $scale + 2)->arcsin($scale + 2);

        $oneDivZSquared = $one->divide($z->pow(2));
        $oneDivZ = $one->divide($z, $intScale);

        $aPart = new class($oneDivZSquared, $oneDivZ, $one) implements ContinuedFractionTermInterface {
            private ImmutableDecimal $oneDivZSq;
            private ImmutableDecimal $oneDivZ;
            private ImmutableDecimal $one;

            public function __construct(ImmutableDecimal $oneDivZSq, ImmutableDecimal $oneDivZ, ImmutableDecimal $one) {
                $this->oneDivZSq = $oneDivZSq;
                $this->oneDivZ = $oneDivZ;
                $this->one = $one;
            }

            public function __invoke(int $n): ImmutableDecimal
            {
                if ($n == 1) {
                    return $this->oneDivZ->multiply($this->one->subtract($this->oneDivZSq)->sqrt());
                }

                $subterm = floor($n/2);

                return $this->oneDivZSq->multiply(
                    2*$subterm-1
                )->multiply(2*$subterm);
            }
        };

        $bPart = new class($intScale) implements ContinuedFractionTermInterface {
            private int $scale;

            public function __construct(int $scale) {
                $this->scale = $scale;
            }

            public function __invoke(int $n): ImmutableDecimal
            {
                if ($n == 0) {
                    return Numbers::makeZero($this->scale);
                }

                return new ImmutableDecimal(($n*2)-1, $this->scale);
            }
        };

        $answer = SeriesProvider::generalizedContinuedFraction(
            $aPart,
            $bPart,
            $intScale * 2,
            $intScale,
            SeriesProvider::SUM_MODE_ALT_FIRST_ADD
        );

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int $scale
     * @param RoundingMode|null $mode
     * @return DecimalInterface
     */
    abstract public function roundToScale(int $scale, ?RoundingMode $mode = null): DecimalInterface;

    /**
     * @param int $scale
     * @return DecimalInterface
     */
    abstract public function truncateToScale(int $scale): DecimalInterface;

    /**
     * @return int|null
     */
    abstract public function getScale(): ?int;

}