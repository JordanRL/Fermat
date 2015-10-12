<?php

namespace Samsara\Cantor\Context;

use Samsara\Cantor\Context\Base\BaseContext;
use Samsara\Cantor\Numbers;
use Samsara\Cantor\Provider\BCProvider;
use Samsara\Cantor\Provider\GaussianProvider;
use Samsara\Cantor\Values\Base\NumberInterface;

/**
 * Used for operating on numbers within the context of a Gaussian (or normal) distribution.
 */
class GaussianContext extends BaseContext
{

    /**
     * @var NumberInterface
     */
    protected $mean;

    /**
     * @var NumberInterface
     */
    protected $standardDev;

    /**
     * @var NumberInterface
     */
    protected $variance;

    /**
     * @param $mean
     * @param $standardDev
     * @param string $type The number type (either Immutable or Fluent)
     */
    public function __construct($mean, $standardDev, $type)
    {
        $this->mean = Numbers::makeOrDont($type, $mean);
        $this->standardDev = Numbers::makeOrDont($type, $mean);
        $this->variance = Numbers::make($type, BCProvider::exp($this->standardDev->getValue(), 2), $this->standardDev->getPrecision(), $this->standardDev->getBase());
        $this->numberType = $type;
    }

    public function getMean()
    {
        return $this->mean;
    }

    public function getSD()
    {
        return $this->standardDev;
    }

    public function getVariance()
    {
        return $this->variance;
    }

    public function valueToSD($value)
    {
        $value = Numbers::makeOrDont($this->numberType, $value);

        return Numbers::make(
            $this->numberType,
            BCProvider::divide($value->getValue(), $this->standardDev->getValue()),
            $value->getPrecision(),
            $value->getBase()
        );
    }

    public function SDToValue($standardDev)
    {
        $standardDev = Numbers::makeOrDont($this->numberType, $standardDev);

        return BCProvider::multiply($standardDev->getValue(), $this->standardDev->getValue());
    }

    public function PDFBySD($standardDev)
    {
        $standardDev = Numbers::makeOrDont($this->numberType, $standardDev);

        $value = Numbers::make($this->numberType, $this->SDToValue($standardDev), $standardDev->getPrecision(), $standardDev->getBase());

        return $this->PDFByValue($value);
    }

    public function PDFByValue($value)
    {
        $value = Numbers::makeOrDont($this->numberType, $value);

        return Numbers::make(
            $this->numberType,
            BCProvider::multiply(
                BCProvider::divide(
                    1,
                    BCProvider::multiply(
                        $this->standardDev->getValue(),
                        BCProvider::squareRoot(
                            M_2_PI
                        )
                    )
                ), // Fraction
                BCProvider::exp(
                    M_E,
                    BCProvider::multiply(
                        -1,
                        BCProvider::divide(
                            BCProvider::exp(
                                BCProvider::subtract($value->getValue(), $this->mean->getValue()),
                                2
                            ),
                            BCProvider::multiply(
                                2,
                                $this->variance->getValue()
                            )
                        )
                    )
                ) // e
            ),
            $value->getPrecision(),
            $value->getBase()
        );
    }

    public function random()
    {
        $rand = GaussianProvider::random($this->getMean()->getValue(), $this->getSD()->getValue());

        return Numbers::make($this->numberType, $rand);
    }

    public function transformBaseContext($base)
    {
        return $this;
    }

}