<?php

namespace Samsara\Fermat\Types\Traits\Trigonometry;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 *
 */
trait InverseTrigonometryHelpersTrait
{

    /**
     * @param ImmutableDecimal $num
     * @param int|null $scale
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function helperArctanGCF(ImmutableDecimal $num, ?int $scale): ImmutableDecimal
    {
        $scale = $scale ?? $this->getScale();
        $intScale = $scale + 2;
        $terms = $intScale + 2;
        $x = new ImmutableDecimal($num, $intScale);
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

                return SequenceProvider::nthOddNumber($n - 1);
            }
        };

        return SeriesProvider::generalizedContinuedFraction($aPart, $bPart, $terms, $intScale);
    }

    /**
     * @param ImmutableDecimal $num
     * @param int $scale
     * @return ImmutableDecimal
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function helperArcsinGCF(ImmutableDecimal $num, int $scale): ImmutableDecimal
    {

        $x = $num;
        $x2 = $x->pow(2);
        $one = new ImmutableDecimal(1, $scale);

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

}