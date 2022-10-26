<?php

namespace Samsara\Fermat\Core\Types\Base\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\SequenceProvider;
use Samsara\Fermat\Core\Provider\SeriesProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Values\MutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
trait InverseTrigonometryHelpersTrait
{

    /**
     * @param ImmutableDecimal $num
     * @param int|null $scale
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    protected function helperArctanGCF(ImmutableDecimal $num, ?int $scale): ImmutableDecimal
    {
        $scale = $scale ?? $this->getScale();
        $intScale = $scale + 3;
        $terms = $intScale * 2;
        $x = new ImmutableDecimal($num, $intScale);
        /**
         * @package Samsara\Fermat\Core
         */
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

        /**
         * @package Samsara\Fermat\Core
         */
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

                return SequenceProvider::nthOddNumber($n - 1, $this->intScale);
            }
        };

        return SeriesProvider::generalizedContinuedFraction($aPart, $bPart, $terms, $intScale);
    }

    /**
     * @param ImmutableDecimal $num
     * @param int $scale
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     */
    protected function helperArcsinGCF(ImmutableDecimal $num, int $scale): ImmutableDecimal
    {

        $x = $num;
        $x2 = $x->pow(2);
        $one = new ImmutableDecimal(1, $scale);

        /**
         * @package Samsara\Fermat\Core
         */
        $aPart = new class($x2, $scale) implements ContinuedFractionTermInterface{
            private ImmutableDecimal $x2;
            private ImmutableDecimal $negTwo;

            /**
             * @param ImmutableDecimal $x2
             * @param int $scale
             */
            public function __construct(ImmutableDecimal $x2, int $scale)
            {
                $this->x2 = $x2;
                $this->negTwo = new ImmutableDecimal(-2, $scale);
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

        /**
         * @package Samsara\Fermat\Core
         */
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

        $answer = SeriesProvider::generalizedContinuedFraction($aPart, $bPart, $scale, $scale);

        return $x->multiply($one->subtract($x2)->sqrt($scale))->divide($answer, $scale);

    }

    /**
     * @param CalcOperation $operation
     * @param int|null $scale
     * @param string $oneInputVal
     * @param string $negOneInputVal
     * @param bool $round
     * @return static|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    protected function helperArcsecArccscSimple(
        CalcOperation $operation,
        ?int $scale,
        string $oneInputVal,
        string $negOneInputVal,
        bool $round
    ): static|ImmutableDecimal|MutableDecimal
    {
        $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
        if ($abs->isLessThan(1)) {
            throw new IntegrityConstraint(
                'The input for arcsec and arccsc must have an absolute value greater than or equal to 1',
                'Only calculate arcsec and arccsc for values greater than 1',
                'The arcsec and arccsc function only have real values for inputs which have an absolute value greater than or equal to 1'
            );
        }

        $finalScale = $scale ?? $this->getScale();

        if ($this->isEqual(1)) {
            $answer = $oneInputVal;
        } elseif ($this->isEqual(-1)) {
            $answer = $negOneInputVal;
        } else {
            $answer = match ($operation) {
                CalcOperation::ArcSec => $this->arcsecSelector($scale),
                CalcOperation::ArcCsc => $this->arccscSelector($scale),
            };
        }

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    /**
     * @param CalcOperation $operation
     * @param int|null $scale
     * @param bool $round
     * @return ImmutableDecimal|MutableDecimal|static
     * @throws IntegrityConstraint
     */
    protected function helperArcsinArccosSimple(
        CalcOperation $operation,
        ?int $scale = null,
        bool $round = true
    ): ImmutableDecimal|MutableDecimal|static
    {
        $abs = $this instanceof ImmutableDecimal ? $this->abs() : new ImmutableDecimal($this->absValue());
        if ($abs->isGreaterThan(1)) {
            throw new IntegrityConstraint(
                'The input for arcsin and arccos must have an absolute value of 1 or smaller',
                'Only calculate arcsin and arccos for values of 1 or smaller',
                'The arcsin and arccos function only have real values for inputs which have an absolute value of 1 or smaller'
            );
        }

        return $this->helperArcBasicSimple($operation, $scale, $round);
    }

    /**
     * @param CalcOperation $operation
     * @param int|null $scale
     * @param bool $round
     * @return static|ImmutableDecimal|MutableDecimal
     * @throws IntegrityConstraint
     */
    protected function helperArcBasicSimple(
        CalcOperation $operation,
        ?int $scale,
        bool $round
    ): static|ImmutableDecimal|MutableDecimal
    {
        $finalScale = $scale ?? $this->getScale();

        $answer = match ($operation) {
            CalcOperation::ArcTan => $this->arctanSelector($scale),
            CalcOperation::ArcCot => $this->arccotSelector($scale),
            CalcOperation::ArcSin => $this->arcsinSelector($scale),
            CalcOperation::ArcCos => $this->arccosSelector($scale),
        };

        if ($round) {
            $result = $this->setValue($answer)->roundToScale($finalScale);
        } else {
            $result = $this->setValue($answer)->truncateToScale($finalScale);
        }

        return $result;
    }

    /**
     * @param ImmutableDecimal $zeroTerm
     * @param CalcOperation $operation
     * @param int $scale
     * @return string
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    protected function helperArcsecArccscScale(ImmutableDecimal $zeroTerm, CalcOperation $operation, int $scale): string
    {
        $one = Numbers::makeOne($scale);
        $z = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);
        $aPart = $this->helperArcsecArccscAPart($one, $z, $scale);

        $bPart = $this->helperArcsecArccscBPart($scale, $zeroTerm);

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
    protected function helperArcsecArccscAPart(
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
    protected function helperArcsecArccscBPart(
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

            /**
             * @param int $scale
             * @param ImmutableDecimal $zeroTerm
             */
            public function __construct(int $scale, ImmutableDecimal $zeroTerm)
            {
                $this->zeroTerm = $zeroTerm;
                $this->scale = $scale;
            }

            /**
             * @param int $n
             * @return ImmutableDecimal
             */
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