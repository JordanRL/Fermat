<?php

namespace Samsara\Fermat\Core\Types\Base\Traits;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\CalcOperation;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\SequenceProvider;
use Samsara\Fermat\Core\Provider\SeriesProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
trait TrigonometryScaleTrait
{

    use TrigonometryHelpersTrait;

    /**
     * @param int|null $scale
     * @return string
     */
    protected function sinScale(int $scale = null): string
    {
        return $this->helperSinCosScale(
            CalcOperation::Sin,
            '1',
            '0',
            '-1',
            '0',
            $scale
        );
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws \ReflectionException
     */
    protected function cosScale(?int $scale = null): string
    {
        return $this->helperSinCosScale(
            CalcOperation::Cos,
            '0',
            '-1',
            '0',
            '1',
            $scale
        );
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function tanScale(int $scale = null): string
    {
        $scale = $scale ?? $this->getScale();
        $intScale = $scale + 4;
        $intScale = $intScale+$this->numberOfIntDigits()+$this->numberOfLeadingZeros();

        $thisNum = Numbers::make(Numbers::IMMUTABLE, $this->getValue(NumberBase::Ten), $intScale);
        $thisNumNonScaled = Numbers::make(Numbers::IMMUTABLE, $this->getValue(NumberBase::Ten), $scale);

        $pi = Numbers::makePi($intScale);
        $piDivTwo = Numbers::makePi($intScale)->divide(2);
        $piDivFour = Numbers::makePi($intScale)->divide(4);
        $piDivEight = Numbers::makePi($intScale)->divide(8);
        $threePiDivTwo = Numbers::makePi($intScale)->multiply(3)->divide(2);
        $twoPi = Numbers::make2Pi($intScale);
        $two = Numbers::make(Numbers::IMMUTABLE, 2, $intScale);
        $one = Numbers::make(Numbers::IMMUTABLE, 1, $intScale);

        $exitModulo = $thisNumNonScaled->continuousModulo($pi);

        if ($exitModulo->truncate($scale)->isEqual(0) || $thisNum->truncate($scale)->isEqual($pi->truncate($scale))) {
            return '0';
        }

        $modulo = $thisNum->continuousModulo($twoPi);

        if (
            $modulo->truncate($scale)->isEqual($piDivTwo->truncate($scale)) ||
            ($this->isNegative() && $modulo->subtract($pi)->abs()->truncate($scale)->isEqual($piDivTwo->truncate($scale)))
        ) {
            return static::INFINITY;
        }

        if (
            $modulo->truncate($scale)->isEqual($threePiDivTwo->truncate($scale)) ||
            ($this->isNegative() && $modulo->truncate($scale)->abs()->isEqual($threePiDivTwo->truncate($scale)))
        ) {
            return static::NEG_INFINITY;
        }

        if ($modulo->abs()->isGreaterThan($piDivTwo)) {
            if ($this->isNegative()) {
                if ($modulo->abs()->isGreaterThan($threePiDivTwo)) {
                    $modulo = $modulo->add($twoPi);
                } else {
                    $modulo = $modulo->add($pi);
                }
            } else {
                if ($modulo->isGreaterThan($threePiDivTwo)) {
                    $modulo = $modulo->subtract($twoPi);
                } else {
                    $modulo = $modulo->subtract($pi);
                }
            }
        }

        $reciprocal = false;

        if ($modulo->abs()->isGreaterThan($piDivFour)) {
            $modulo = $piDivTwo->subtract($modulo);
            $reciprocal = true;
        }

        if ($modulo->abs()->isGreaterThan($piDivEight)) {
            /** @var ImmutableDecimal $halfModTan */
            $halfModTan = Numbers::make(Numbers::IMMUTABLE, $modulo->divide(2)->tanScale($intScale+1));
            $answer = $two->multiply($halfModTan)->divide($one->subtract($halfModTan->pow(2)));
        } else {
            /**
             * @package Samsara\Fermat\Core
             */
            $aPart = new class($modulo) implements ContinuedFractionTermInterface {
                private ImmutableDecimal $modulo;

                /**
                 * @param ImmutableDecimal $modulo
                 */
                public function __construct(ImmutableDecimal $modulo) {
                    $this->modulo = $modulo;
                }

                /**
                 * @param int $n
                 * @return ImmutableDecimal
                 */
                public function __invoke(int $n): ImmutableDecimal
                {
                    if ($n > 1) {
                        return $this->modulo->pow(2);
                    } else {
                        return $this->modulo;
                    }
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
                public function __construct(int $intScale) {
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
                    } else {
                        return SequenceProvider::nthOddNumber($n-1, $this->intScale);
                    }
                }
            };

            $answer = SeriesProvider::generalizedContinuedFraction($aPart, $bPart, $intScale, $intScale, SeriesProvider::SUM_MODE_ALT_FIRST_ADD);
        }

        if ($reciprocal) {
            $answer = $one->divide($answer, $intScale);
        }

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function cotScale(int $scale = null): string
    {

        $pi = Numbers::makePi();
        $twoPi = Numbers::make2Pi();
        $one = Numbers::makeOne();
        $piDivTwo = $pi->divide(2);

        $scale = $scale ?? $this->getScale();

        $num = Numbers::make(Numbers::IMMUTABLE, $this, $scale+1);
        $numNonScaled = Numbers::make(Numbers::IMMUTABLE, $this, $scale);

        $modPi = $numNonScaled->continuousModulo($pi)->truncate($scale);
        $mod2Pi = $numNonScaled->continuousModulo($twoPi)->truncate($scale);

        if ($mod2Pi->isEqual(0)) {
            return static::INFINITY;
        } elseif($modPi->isEqual(0)) {
            return static::NEG_INFINITY;
        }

        $modPiDiv2 = $numNonScaled->continuousModulo($piDivTwo)->truncate($scale);

        if ($modPiDiv2->isEqual(0)) {
            return '0';
        }

        $tan = $num->tanScale($scale);

        $answer = $one->divide($tan, $scale+2);

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function secScale(int $scale = null): string
    {

        $one = Numbers::makeOne();

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale+1);

        $cos = $num->cos($scale+2);

        if ($cos->isPositive() && $cos->numberOfLeadingZeros() >= $scale) {
            return static::INFINITY;
        } elseif ($cos->isNegative() && $cos->numberOfLeadingZeros() >= $scale) {
            return static::NEG_INFINITY;
        }

        $answer = $one->divide($cos, $scale+2);

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function cscScale(int $scale = null): string
    {

        $one = Numbers::makeOne();

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $sin = $num->sin($scale+2);

        if ($sin->isPositive() && $sin->numberOfLeadingZeros() >= $scale) {
            return static::INFINITY;
        } elseif ($sin->isNegative() && $sin->numberOfLeadingZeros() >= $scale) {
            return static::NEG_INFINITY;
        }

        $answer = $one->divide($sin, $scale+2);

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function sinhScale(int $scale = null): string
    {

        $two = Numbers::make(Numbers::IMMUTABLE, 2);

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale+2);

        $answer = $num->multiply(2)->exp($scale+2)->subtract(1)->divide($two->multiply($num->exp($scale+2)), $scale+2);

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function coshScale(int $scale = null): string
    {

        $two = Numbers::make(Numbers::IMMUTABLE, 2);

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $answer = $num->multiply(2)->exp()->add(1)->divide($two->multiply($num->exp()));

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function tanhScale(int $scale = null): string
    {

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $answer = Numbers::make(Numbers::IMMUTABLE, $num->sinhScale($scale+2))->divide($num->coshScale($scale+2), $scale+2);

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function cothScale(int $scale = null): string
    {

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale+2);

        $answer = Numbers::make(Numbers::IMMUTABLE, $num->coshScale($scale+1))->divide($num->sinh($scale+1), $scale+2);

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function sechScale(int $scale = null): string
    {

        $scale = $scale ?? $this->getScale();

        $one = Numbers::makeOne();
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $answer = $one->divide($num->coshScale($scale+2), $scale+2);

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     */
    protected function cschScale(int $scale = null): string
    {

        $scale = $scale ?? $this->getScale();

        $one = Numbers::makeOne();
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale);

        $answer = $one->divide($num->sinhScale($scale+2), $scale+2);

        return $answer->getAsBaseTenRealNumber();

    }

}