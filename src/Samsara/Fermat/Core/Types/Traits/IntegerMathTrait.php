<?php

namespace Samsara\Fermat\Core\Types\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\SequenceProvider;
use Samsara\Fermat\Core\Types\Decimal;
use Samsara\Fermat\Core\Types\NumberCollection;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @package Samsara\Fermat\Core
 */
trait IntegerMathTrait
{

    /**
     * Retrieves the divisors of the current object.
     *
     * This method calculates the divisors of the current object by iterating from 2 to (current value / 2).
     * For each divisor, if the current object is divisible by the divisor without remainder, the divisor
     * and the quotient of the division are added to the resulting NumberCollection. The resulting NumberCollection
     * is then sorted in ascending order.
     *
     * @return NumberCollection The divisors of the current object.
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
     * Retrieves the greatest common divisor (GCD) between the current object and a given number.
     *
     * This method calculates the GCD between the current object and the given number. It uses the gmp_gcd function from the GMP extension
     * to perform the actual calculation. The GCD is calculated using the absolute values of the numbers, ensuring that negative numbers
     * do not affect the result.
     *
     * @param mixed $num The number to calculate the GCD with.
     *
     * @return static A new instance of the current object with the GCD as its value.
     * @throws IntegrityConstraint If either the current object or the given number is not an integer.
     */
    public function getGreatestCommonDivisor($num): static
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

        return new static($val, $this->getScale());

    }

    /**
     * Retrieves the least common multiple (LCM) of the current object and the specified number.
     *
     * This method calculates the LCM of the current object and the specified number using the gmp_lcm function from the GMP extension.
     * It ensures that both numbers are integers before performing the calculation. If either number is not an integer, an IntegrityConstraint
     * exception is thrown.
     *
     * @param mixed $num The number to calculate the LCM with.
     *
     * @return static The result as a new ImmutableDecimal object.
     * @throws IntegrityConstraint if either number is not an integer.
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
     * Retrieves the prime factors of the current object.
     *
     * This method calculates the prime factors of the current object by repeatedly dividing the object by prime numbers starting from 2.
     * Each prime factor is added to the resulting NumberCollection. The loop continues until the remaining value of the current object
     * is no longer greater than 1.
     *
     * @return NumberCollection The prime factors of the current object.
     */
    public function getPrimeFactors(): NumberCollection
    {
        $factor = (new ImmutableDecimal(2))->setMode($this->getMode());
        $thisNum = (new ImmutableDecimal($this, $this->getScale()))->setMode($this->getMode());

        $primeFactors = new NumberCollection();

        while ($thisNum->isGreaterThan(1)) {
            if ($thisNum->modulo($factor)->isEqual(0)) {
                $primeFactors->push($factor);
                $thisNum = $thisNum->divide($factor);
            } else {
                $factor = SequenceProvider::nextPrimeNumber($factor);
            }
        }

        return $primeFactors;
    }

    /**
     * Only valid for integer numbers. Uses the Miller-Rabin probabilistic primality test. The default "certainty" value of 20
     * results in a false-positive rate of 1 in 1.10 x 10^12.
     *
     * First, the function performs a quick check using the `_primeEarlyExit` method. This test will immediately return if
     * the value is 2 or 3 (in which case it is prime), is not an integer, or is divisible by two or three (in which case
     * it is not prime).
     *
     * If the value passes this preliminary test, the function then proceeds to the Miller-Rabin probabilistic primality test.
     *
     * With high enough certainty values, the probability that the program returned an incorrect result due to errors in
     * the computer hardware begins to dominate. Typically, a certainty of around 40 is sufficient for a prime number used
     * in a cryptographic context.
     *
     * @param int|null $certainty The certainty level desired. False positive rate = 1 in 4^$certainty.
     *
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
     * Only valid for integer numbers. Takes the double factorial of this number. Not to be confused with taking the
     * factorial twice which is (n!)!, the double factorial n!! multiplies all the numbers between 1 and n that share
     * the same parity (odd or even).
     *
     * For more information, see: https://mathworld.wolfram.com/DoubleFactorial.html
     *
     * @return static
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function doubleFactorial(): static
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
     * Calculates the factorial of the current object.
     *
     * This method returns the factorial of the current object if the object is a non-negative whole number. The factorial of a non-negative
     * whole number is calculated by multiplying all the positive integers from 1 to that number. For example, the factorial of 5 is
     * calculated as 5 * 4 * 3 * 2 * 1 = 120.
     *
     * @return static The factorial of the current object.
     *
     * @throws IncompatibleObjectState If the current object is a negative number, an exception is thrown with a descriptive error message.
     * @throws IncompatibleObjectState If the current object has a fractional value, an exception is thrown with a descriptive error message.
     */
    public function factorial(): static
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
     * Calculates the falling factorial of a given number.
     *
     * The falling factorial is a mathematical operation that calculates the product of a given number and all positive integers
     * less than it, down to the specified number of terms.
     *
     * This method calculates the falling factorial of the provided $num by calling the risingFallingFactorialHelper method with a
     * negative value for the number of terms, indicating that the factorial should be calculated in descending order.
     *
     * @param int|float|string|Decimal $num The number to calculate the falling factorial.
     *
     * @return static The falling factorial of the given number.
     */
    public function fallingFactorial(int|float|string|Decimal $num): static
    {
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $num);

        return $this->risingFallingFactorialHelper($num, -1);
    }

    /**
     * Calculates the rising factorial of a given number.
     *
     * @param int|float|string|Decimal $num The number for which to calculate the rising factorial.
     *
     * @return static Returns the resulting rising factorial value.
     */
    public function risingFactorial(int|float|string|Decimal $num): static
    {
        $num = Numbers::makeOrDont(Numbers::IMMUTABLE, $num);

        return $this->risingFallingFactorialHelper($num, 1);
    }

    /**
     * Alias for doubleFactorial().
     *
     * @return static
     * @throws IncompatibleObjectState
     */
    public function semiFactorial(): static
    {
        return $this->doubleFactorial();
    }

    /**
     * Only valid for integer numbers. Takes the subfactorial of this number. The subfactorial is the number of
     * derangements of a set with n members.
     *
     * For more information, see: https://mathworld.wolfram.com/Subfactorial.html
     *
     * @return static
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
    public function subFactorial(): static
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

        return $this->setValue($num->floor());
    }

    /**
     * Helper method for calculating the rising or falling factorial of a given number.
     *
     * @param ImmutableDecimal $num The number for which to calculate the rising or falling factorial.
     * @param int              $signum The signum value to determine whether to calculate rising or falling factorial.
     *
     * @return static Returns the resulting rising or falling factorial value.
     * @throws IncompatibleObjectState If the current object is not in a valid state.
     *
     */
    protected function risingFallingFactorialHelper(ImmutableDecimal $num, int $signum): static
    {
        if (!$this->isWhole()) {
            throw new IncompatibleObjectState(
                '',
                ''
            );
        }

        if ($num->isEqual(0)) {
            return $this->setValue('0');
        }

        $thisNum = (new ImmutableDecimal($this, $this->getScale()))->setMode($this->getMode());
        $answer = Numbers::makeOne($this->getScale());

        for ($i = 0; $num->isGreaterThan($i); $i++) {
            $answer = $answer->multiply($thisNum->add($i * $signum));
        }

        return $this->setValue($answer);
    }

    /**
     * Determines if the given number is a prime number and returns early if possible.
     *
     * @return int Returns 0 if the number is not a prime number and continues with further checks.
     *             Returns 1 if the number is not a prime number and exits early.
     *             Returns 2 if the number is a prime number and exits early.
     */
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