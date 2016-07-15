<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\Base\NumberInterface;
use Samsara\Fermat\Values\ImmutableNumber;

abstract class Fraction
{

    protected $precision;

    protected $base;

    /**
     * @var ImmutableNumber
     */
    private $numerator;

    /**
     * @var ImmutableNumber
     */
    private $denominator;

    public function __construct($value, $precision = null, $base = 10)
    {

        $parts = explode('/', $value);

        if (count($parts) > 2) {
            throw new \Exception('Cannot construct Fraction with more than one division symbol');
        }

        $this->numerator = Numbers::make(Numbers::IMMUTABLE, trim(ltrim($parts[0])))->round();
        $this->denominator = Numbers::make(Numbers::IMMUTABLE, trim(ltrim($parts[1])))->round();

    }

    public function simplify()
    {
        $gcf = $this->getGreatestCommonFactor();

        $numerator = $this->numerator->divide($gcf);
        $denominator = $this->denominator->divide($gcf);

        return $this->setValue($numerator, $denominator);
    }

    /**
     * @return NumberInterface
     */
    protected function getGreatestCommonFactor()
    {

    }

    /**
     * @param ImmutableNumber $numerator
     * @param ImmutableNumber $denominator
     * @return Fraction
     */
    abstract protected function setValue(ImmutableNumber $numerator, ImmutableNumber $denominator);

}