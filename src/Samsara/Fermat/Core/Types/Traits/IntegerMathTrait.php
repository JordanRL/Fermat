<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\NumberCollection;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
trait IntegerMathTrait
{

    /**
     * Only valid for integer numbers. Takes the factorial of this number. The factorial is every number between 1 and
     * this number multiplied together.
     *
     * @return Decimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function factorial(): Decimal
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

        return $this->setValue(gmp_strval(gmp_fact($this->getValue(NumberBase::Ten))));
    }

    /**
     * Only valid for integer numbers. Takes the subfactorial of this number. The subfactorial is the number of
     * derangements of a set with n members.
     *
     * For more information, see: https://mathworld.wolfram.com/Subfactorial.html
     *
     * @return Decimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function subFactorial(): Decimal
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
        $num = new ImmutableDecimal($this->getAsBaseTenRealNumber());

        $num = $num->factorial();
        $num = $num->divide($e, 3)->add('0.5');

        return $num->floor();
    }

    /**
     * Only valid for integer numbers. Takes the double factorial of this number. Not to be confused with taking the
     * factorial twice which is (n!)!, the double factorial n!! multiplies all the numbers between 1 and n that share
     * the same parity (odd or even).
     *
     * For more information, see: https://mathworld.wolfram.com/DoubleFactorial.html
     *
     * @return Decimal
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function doubleFactorial(): Decimal
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

        $num = Numbers::make(Numbers::MUTABLE, $this->getValue(NumberBase::Ten), $this->getScale(), $this->getBase());

        $newVal = Numbers::makeOne();

        $continue = true;

        while ($continue) {
            $newVal = $newVal->multiply($num->getValue(NumberBase::Ten));
            $num = $num->subtract(2);

            if ($num->isLessThanOrEqualTo(1)) {
                $continue = false;
            }
        }

        return $this->setValue($newVal->getValue(NumberBase::Ten));

    }

    /**
     * Alias for doubleFactorial().
     *
     * @return Decimal
     * @throws IncompatibleObjectState
     */
    public function semiFactorial(): Decimal
    {
        return $this->doubleFactorial();
    }

    /**
     * Only valid for integer numbers. Returns the least common multiple of this number and the supplied number.
     *
     * @param $num
     * @return static
     * @throws IntegrityConstraint
     */
    public function getLeastCommonMultiple($num): static
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

        $value = gmp_lcm($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

        return $this->setValue(gmp_strval($value), $this->getScale(), $this->getBase());

    }

    /**
     * Only valid for integer numbers. Returns the greatest common divisor for this number and the supplied number.
     *
     * @param $num
     * @return Decimal
     * @throws IntegrityConstraint
     */
    public function getGreatestCommonDivisor($num): Decimal
    {
        /** @var ImmutableDecimal $num */
        $num = Numbers::make(Numbers::IMMUTABLE, $num)->abs();
        /** @var ImmutableDecimal $thisNum */
        $thisNum = Numbers::make(Numbers::IMMUTABLE, $this)->abs();

        if (!$this->isInt() || !$num->isInt()) {
            throw new IntegrityConstraint(
                'Both numbers must be integers',
                'Ensure that both numbers are integers before getting the GCD',
                'Both numbers being considered must be integers in order to calculate a Greatest Common Divisor'
            );
        }

        $val = gmp_strval(gmp_gcd($thisNum->getValue(NumberBase::Ten), $num->getValue(NumberBase::Ten)));

        return Numbers::make(Numbers::IMMUTABLE, $val, $this->getScale());

    }

    /**
     * Only valid for integer numbers. Uses the Miller-Rabin probabilistic primality test. The default "certainty" value of 20
     * results in a false-positive rate of 1 in 1.10 x 10^12.
     *
     * With high enough certainty values, the probability that the program returned an incorrect result due to errors in
     * the computer hardware begins to dominate. Typically, a certainty of around 40 is sufficient for a prime number used
     * in a cryptographic context.
     *
     * @param int|null $certainty The certainty level desired. False positive rate = 1 in 4^$certainty.
     * @return bool
     */
    public function isPrime(?int $certainty = 20): bool
    {
        return match ($this->_primeEarlyExit()) {
            1 => false,
            2 => true,
            default => (bool)gmp_prob_prime($this->getValue(NumberBase::Ten), $certainty),
        };

    }

    /**
     * Only valid for integer numbers. Returns a collections of all of the integer divisors of this number.
     *
     * @return NumberCollection
     * @throws IntegrityConstraint
     */
    public function getDivisors(): NumberCollection
    {
        $half = $this->divide(2);

        $collection = new NumberCollection();
        $current = Numbers::makeOne(2);
        while ($current->isLessThan($half)) {
            if ($this->modulo($current)->isEqual(0)) {
                $collection->push($current);
                $collection->push($this->divide($current));
            }
            $current = $current->add(1);
        }

        $collection->sort();

        return $collection;
    }

    /**
     * TODO: Implement
     *
     * @return NumberCollection
     */
    public function asPrimeFactors(): NumberCollection
    {

        return new NumberCollection();

    }

    private function _primeEarlyExit(): int
    {
        if (!$this->isInt()) {
            return 1;
        }

        if ($this->isEqual(2)) {
            return 2;
        } elseif ($this->isEqual(3)) {
            return 2;
        } elseif ($this->modulo(2)->isEqual(0)) {
            return 1;
        } elseif ($this->modulo(3)->isEqual(0)) {
            return 1;
        } elseif ($this->isEqual(1)) {
            return 1;
        }

        return 0;
    }

}