<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\NumberCollection;
use Samsara\Fermat\Values\ImmutableDecimal;

trait IntegerMathTrait
{

    public function factorial(): DecimalInterface
    {
        if ($this->isLessThan(1)) {
            if ($this->isEqual(0)) {
                return $this->setValue(1);
            }
            throw new IncompatibleObjectState(
                'Can only perform a factorial on a non-negative number.',
                'Ensure that the number is not negative before calling factorial().',
                'The factorial() method was called on a value that was negative.'
            );
        }

        if ($this->getDecimalPart() != 0) {
            throw new IncompatibleObjectState(
                'Can only perform a factorial on a whole number.',
                'Ensure that the number does not have any fractional value before calling factorial().',
                'The factorial() method was called on a value that was not a whole number.'
            );
        }

        if (function_exists('gmp_fact') && function_exists('gmp_strval') && $this->extensions) {
            return $this->setValue(gmp_strval(gmp_fact($this->getValue())));
        }

        $curVal = $this->getValue();
        $calcVal = Numbers::make(Numbers::IMMUTABLE, 1);

        for ($i = 1;$i <= $curVal;$i++) {
            $calcVal = $calcVal->multiply($i);
        }

        return $this->setValue($calcVal->getValue());

    }

    /**
     * @return DecimalInterface
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function subFactorial(): DecimalInterface
    {
        if ($this->isLessThan(1)) {
            if ($this->isEqual(0)) {
                return $this->setValue(1);
            }
            throw new IncompatibleObjectState(
                'Can only perform a sub-factorial on a non-negative number.',
                'Ensure that the number is not negative before calling subFactorial().',
                'The subFactorial() method was called on a value that was negative.'
            );
        }

        if (!$this->isInt()) {
            throw new IncompatibleObjectState(
                'Can only perform a sub-factorial on a whole number.',
                'Ensure that the number does not have any fractional value before calling subFactorial().',
                'The subFactorial() method was called on a value that was not a whole number.'
            );
        }

        $e = Numbers::makeE($this->getScale());
        $num = new ImmutableDecimal($this->getValue(10));

        $num = $num->factorial();
        $num = $num->divide($e, 3)->add('0.5');

        return $num->floor();
    }

    public function doubleFactorial(): DecimalInterface
    {
        if ($this->isWhole() && $this->isLessThanOrEqualTo(1)) {
            return $this->setValue('1');
        } elseif (!$this->isWhole()) {
            throw new IncompatibleObjectState(
                'Can only perform a double factorial on a whole number.',
                'Ensure that the number does not have any fractional value before calling doubleFactorial().',
                'The doubleFactorial() method was called on a value that was not a whole number.'
            );
        }

        $num = Numbers::make(Numbers::MUTABLE, $this->getValue(), $this->getScale(), $this->getBase());

        $newVal = Numbers::makeOne();

        $continue = true;

        while ($continue) {
            $newVal = $newVal->multiply($num->getValue());
            $num = $num->subtract(2);

            if ($num->isLessThanOrEqualTo(1)) {
                $continue = false;
            }
        }

        return $this->setValue($newVal->getValue());

    }

    public function semiFactorial(): DecimalInterface
    {
        return $this->doubleFactorial();
    }

    public function getLeastCommonMultiple($num): DecimalInterface
    {

        /** @var ImmutableDecimal $num */
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $num);

        if (!$this->isInt() || !$num->isInt()) {
            throw new IntegrityConstraint(
                'Both numbers must be integers',
                'Ensure that both numbers are integers before getting the LCM',
                'Both numbers being considered must be integers in order to calculate a Least Common Multiple'
            );
        }

        return $this->multiply($num)->abs()->divide($this->getGreatestCommonDivisor($num));

    }

    public function getGreatestCommonDivisor($num): DecimalInterface
    {
        /** @var ImmutableDecimal $num */
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $num)->abs();
        /** @var ImmutableDecimal $thisNum */
        $thisNum = Numbers::makeOrDont(Numbers::IMMUTABLE, $this)->abs();

        if (!$this->isInt() || !$num->isInt()) {
            throw new IntegrityConstraint(
                'Both numbers must be integers',
                'Ensure that both numbers are integers before getting the GCD',
                'Both numbers being considered must be integers in order to calculate a Greatest Common Divisor'
            );
        }

        if (function_exists('gmp_gcd') && function_exists('gmp_strval') && $this->extensions) {
            $val = gmp_strval(gmp_gcd($thisNum->getValue(), $num->getValue()));

            return Numbers::make(Numbers::IMMUTABLE, $val);
        } else {

            if ($thisNum->isLessThan($num)) {
                $greater = $num;
                $lesser = $thisNum;
            } else {
                $greater = $thisNum;
                $lesser = $num;
            }

            /** @var NumberInterface $remainder */
            $remainder = $greater->modulo($lesser);

            while ($remainder->isGreaterThan(0)) {
                $greater = $lesser;
                $lesser = $remainder;
                $remainder = $greater->modulo($lesser);
            }

            return $lesser;
        }
    }

    /**
     * This function is a PHP implementation of the function described at: http://stackoverflow.com/a/1801446
     *
     * It is relatively simple to understand, which is why it was chosen as the implementation. However in the future,
     * an implementation that is based on ECPP (such as the Goldwasser implementation) may be employed to improve speed.
     *
     * @return bool
     */
    public function isPrime(): bool
    {
        if (!$this->isInt()) {
            return false;
        }

        if ($this->isEqual(2)) {
            return true;
        } elseif ($this->isEqual(3)) {
            return true;
        } elseif ($this->modulo(2)->isEqual(0)) {
            return false;
        } elseif ($this->modulo(3)->isEqual(0)) {
            return false;
        }

        $i = Numbers::make(Numbers::IMMUTABLE, 5);
        $w = Numbers::make(Numbers::IMMUTABLE, 2);
        $k = Numbers::make(Numbers::IMMUTABLE, 6);

        while ($i->pow(2)->isLessThanOrEqualTo($this)) {
            if ($this->modulo($i)->isEqual(0)) {
                return false;
            }

            $i = $i->add($w);
            $w = $k->subtract($w);
        }

        return true;
    }

    public function asPrimeFactors(): NumberCollection
    {

        return new NumberCollection();

    }

}