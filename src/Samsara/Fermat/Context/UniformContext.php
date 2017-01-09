<?php

namespace Samsara\Fermat\Context;

use RandomLib\Factory;
use Samsara\Fermat\Context\Base\BaseContext;
use Samsara\Fermat\Context\Base\ContextInterface;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\BCProvider;
use Samsara\Fermat\Values\ImmutableNumber;
use Samsara\Fermat\Types\Base\NumberInterface;

class UniformContext extends BaseContext implements ContextInterface
{
    public function __construct($type, $base = 10)
    {
        $this->numberType = $type;
        $this->contextBase = $base;
    }
    
    public function getByX($x)
    {
        return Numbers::makeOrDont($this->numberType, $x, null, $this->contextBase);
    }

    /**
     * Generates a random number in the provided range, where all possible values are equally likely. (Even distribution.)
     *
     * NOTE: If $max is more than PHP_INT_MAX or $min is less than PHP_INT_MIN, no additional entropy will be gained for
     * the random number, and the distribution will become less evenly distributed across all possible values due to
     * rounding.
     *
     * @param $min
     * @param $max
     * @return NumberInterface
     */
    public function random($min = 0, $max = PHP_INT_MAX)
    {
        $min = Numbers::makeOrDont(Numbers::IMMUTABLE, $min);
        $max = Numbers::makeOrDont(Numbers::IMMUTABLE, $max);
        $difference = new ImmutableNumber(BCProvider::add($max->absValue(), $min->absValue()));

        $randFactory = new Factory();

        if ($max->compare(PHP_INT_MAX) != 1 && $min->compare(PHP_INT_MIN) != -1 && $difference->compare(PHP_INT_MAX) != 1) {
            $x = $randFactory->getMediumStrengthGenerator()->generateInt($min, $max);

            return Numbers::makeFromBase10($this->numberType, $x, null, $this->contextBase);
        } else {
            $x = $randFactory->getMediumStrengthGenerator()->generateInt();

            $fraction = BCProvider::divide($x, PHP_INT_MAX);

            $addedValue = BCProvider::multiply($fraction, $difference->getValue());

            $randVal = Numbers::makeFromBase10($this->numberType, BCProvider::add($min->getValue(), $addedValue), null, $this->contextBase);

            return $randVal->round();
        }
    }

    protected function transformBaseContext($base)
    {
        return $this;
    }
}