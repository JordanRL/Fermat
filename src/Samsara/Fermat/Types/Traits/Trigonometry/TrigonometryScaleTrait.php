<?php

namespace Samsara\Fermat\Types\Traits\Trigonometry;

use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\SequenceProvider;
use Samsara\Fermat\Provider\SeriesProvider;
use Samsara\Fermat\Types\Base\Interfaces\Callables\ContinuedFractionTermInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 *
 */
trait TrigonometryScaleTrait
{

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws OptionalExit
     * @throws \ReflectionException
     */
    protected function sinScale(int $scale = null): string
    {
        if ($this->isEqual(0)) {
            return $this;
        }

        $scale = $scale ?? $this->getScale();
        $modScale = ($scale > $this->getScale()) ? $scale : $this->getScale();

        $twoPi = Numbers::make2Pi($modScale * 2);
        $pi = Numbers::makePi( $scale + 2 );

        if ($pi->truncate($scale)->isEqual($this) || $twoPi->truncate($scale)->isEqual($this)) {
            return '0';
        }

        $modulo = $this->continuousModulo($twoPi);
        $negOne = Numbers::make(Numbers::IMMUTABLE, -1, $scale+1);
        $one = Numbers::make(Numbers::IMMUTABLE, 1, $scale+1);

        $answer = SeriesProvider::maclaurinSeries(
            $modulo,
            function ($n) use ($scale, $negOne, $one) {

                return $n % 2 ? $negOne : $one;
            },
            function ($n) {
                return SequenceProvider::nthOddNumber($n);
            },
            function ($n) {
                return SequenceProvider::nthOddNumber($n)->factorial();
            },
            0,
            $scale+1
        );

        return $answer->getAsBaseTenRealNumber();
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws \ReflectionException
     * @throws OptionalExit
     */
    protected function cosScale(int $scale = null): string
    {
        if ($this->isEqual(0)) {
            return '1';
        }

        $scale = $scale ?? $this->getScale();
        $modScale = ($scale > $this->getScale()) ? $scale : $this->getScale();

        $twoPi = Numbers::make2Pi($modScale * 2);
        $pi = Numbers::makePi( $scale + 2 );

        if ($twoPi->truncate($scale)->isEqual($this)) {
            return '1';
        }

        if ($pi->truncate($scale)->isEqual($this)) {
            return '-1';
        }

        $modulo = $this->continuousModulo($twoPi);
        $negOne = Numbers::make(Numbers::IMMUTABLE, -1, $scale+1);
        $one = Numbers::make(Numbers::IMMUTABLE, 1, $scale+1);

        $answer = SeriesProvider::maclaurinSeries(
            $modulo,
            function ($n) use ($scale, $negOne, $one) {

                return $n % 2 ? $negOne : $one;
            },
            function ($n) {
                return SequenceProvider::nthEvenNumber($n);
            },
            function ($n) {
                return SequenceProvider::nthEvenNumber($n)->factorial();
            },
            0,
            $scale+1
        );

        return $answer->getAsBaseTenRealNumber();
    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function tanScale(int $scale = null): string
    {
        $scale = $scale ?? $this->getScale();
        $intScale = $scale + 2;

        $pi = Numbers::makePi($intScale);
        $piDivTwo = Numbers::makePi($intScale)->divide(2);
        $piDivFour = Numbers::makePi($intScale)->divide(4);
        $piDivEight = Numbers::makePi($intScale)->divide(8);
        $threePiDivTwo = Numbers::makePi($intScale)->multiply(3)->divide(2);
        $twoPi = Numbers::make2Pi($intScale);
        $two = Numbers::make(Numbers::IMMUTABLE, 2, $intScale);
        $one = Numbers::make(Numbers::IMMUTABLE, 1, $intScale);

        $exitModulo = $this->continuousModulo($pi);

        if ($exitModulo->truncate($intScale-2)->isEqual(0)) {
            return '0';
        }

        $modulo = $this->continuousModulo($twoPi);

        if (
            $modulo->truncate($intScale-2)->isEqual($piDivTwo->truncate($intScale-2)) ||
            ($this->isNegative() && $modulo->subtract($pi)->abs()->truncate($intScale-2)->isEqual($piDivTwo->truncate($intScale-2)))
        ) {
            return static::INFINITY;
        }

        if (
            $modulo->subtract($pi)->truncate($intScale-2)->isEqual($piDivTwo->truncate($intScale-2)) ||
            ($this->isNegative() && $modulo->truncate($intScale-2)->abs()->isEqual($piDivTwo->truncate($intScale-2)))
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
                        return SequenceProvider::nthOddNumber($n-1);
                    }
                }
            };

            $answer = SeriesProvider::generalizedContinuedFraction($aPart, $bPart, $intScale, $intScale, SeriesProvider::SUM_MODE_SUB);
        }

        if ($reciprocal) {
            $answer = $one->divide($answer);
        }

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
    protected function cotScale(int $scale = null): string
    {

        $pi = Numbers::makePi();
        $twoPi = Numbers::make2Pi();
        $one = Numbers::makeOne();
        $piDivTwo = $pi->divide(2);

        $scale = $scale ?? $this->getScale();

        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $scale+1);

        $modPi = $num->continuousModulo($pi)->truncate($scale);
        $mod2Pi = $num->continuousModulo($twoPi)->truncate($scale);

        if ($mod2Pi->isEqual(0)) {
            return static::INFINITY;
        } elseif($modPi->isEqual(0)) {
            return static::NEG_INFINITY;
        }

        $modPiDiv2 = $num->continuousModulo($piDivTwo)->truncate($scale);

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

        $cos = $num->cosScale($scale+2);

        if ($cos == 0) {
            return static::INFINITY;
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

        $sin = $num->sinScale($scale+2);

        if ($sin == '0') {
            return static::INFINITY;
        }

        $answer = $one->divide($sin, $scale+2);

        return $answer->getAsBaseTenRealNumber();

    }

    /**
     * @param int|null $scale
     * @return string
     * @throws IntegrityConstraint
     * @throws MissingPackage
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
     * @throws MissingPackage
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