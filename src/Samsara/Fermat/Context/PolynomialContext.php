<?php

namespace Samsara\Fermat\Context;

use RandomLib\Factory;
use Samsara\Fermat\Context\Base\BaseContext;
use Samsara\Fermat\Context\Base\ContextInterface;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\BCProvider;
use Samsara\Fermat\Types\NumberInterface;

/**
 * Used for operating on numbers withing a polynomial context.
 */
class PolynomialContext extends BaseContext implements ContextInterface
{

    /**
     * The exponents and coefficients present in this polynomial. This is ALWAYS represented in base10 regardless of
     * the context base, in order to take advantage of the fact that PHP and most computer programming languages are
     * base10 by default.
     *
     * The format for this array is:
     *
     * $equationDefinition[$exponent] => $coefficient
     *
     * Meaning that this equation:
     *
     * 2x^4 + x^2 - 7x + 3
     *
     * Would be represented by:
     *
     * [
     *  0 => 3,
     *  1 => -7,
     *  2 => 1,
     *  3 => 0, // This could also be omitted
     *  4 => 2
     * ]
     *
     * @var array                   $definition
     * @var string|NumberInterface  $type
     */
    protected $equationDefinition = [];

    public function __construct(array $definition, $type)
    {
        $this->numberType = $type;

        $this->equationDefinition = $definition;
    }

    public function random($min = 0, $max = PHP_INT_MAX)
    {
        $uniformContext = new UniformContext($this->numberType, $this->contextBase);

        $x = $uniformContext->random($min, $max);

        return $this->getY($x);
    }

    public function getY($x)
    {
        $value = 0;

        foreach ($this->equationDefinition as $exponent => $coefficient) {
            $value = BCProvider::add(
                $value,
                BCProvider::multiply(
                    $coefficient,
                    BCProvider::exp(
                        $x,
                        $exponent
                    )
                )
            );
        }

        return Numbers::make(
            $this->numberType,
            $value
        );
    }

    protected function transformBaseContext($base)
    {
        return $this;
    }

}