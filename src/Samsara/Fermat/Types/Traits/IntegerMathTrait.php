<?php

namespace Samsara\Fermat\Types\Traits;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Enums\NumberBase;
use Samsara\Fermat\Enums\RandomMode;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\RandomProvider;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface;
use Samsara\Fermat\Types\NumberCollection;
use Samsara\Fermat\Values\ImmutableDecimal;

/**
 *
 */
trait IntegerMathTrait
{

    /**
     * @return DecimalInterface
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     */
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
            return $this->setValue(gmp_strval(gmp_fact($this->getValue(NumberBase::Ten))));
        }

        $curVal = $this->getValue(NumberBase::Ten);
        $calcVal = Numbers::make(Numbers::IMMUTABLE, 1);

        for ($i = 1;$i <= $curVal;$i++) {
            $calcVal = $calcVal->multiply($i);
        }

        return $this->setValue($calcVal->getValue(NumberBase::Ten));

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
        $num = new ImmutableDecimal($this->getAsBaseTenRealNumber());

        $num = $num->factorial();
        $num = $num->divide($e, 3)->add('0.5');

        return $num->floor();
    }

    /**
     * @return DecimalInterface
     * @throws IncompatibleObjectState
     * @throws IntegrityConstraint
     * @throws MissingPackage
     */
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
     * @return DecimalInterface
     * @throws IncompatibleObjectState
     */
    public function semiFactorial(): DecimalInterface
    {
        return $this->doubleFactorial();
    }

    /**
     * @param $num
     * @return DecimalInterface
     * @throws IntegrityConstraint
     */
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

        if (extension_loaded('gmp')) {
            $value = gmp_lcm($this->getAsBaseTenRealNumber(), $num->getAsBaseTenRealNumber());

            return $this->setValue(gmp_strval($value), $this->getScale(), $this->getBase());
        }

        return $this->multiply($num)->abs()->divide($this->getGreatestCommonDivisor($num));

    }

    /**
     * @param $num
     * @return DecimalInterface
     * @throws IntegrityConstraint
     */
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

        if (extension_loaded('gmp')) {
            $val = gmp_strval(gmp_gcd($thisNum->getValue(NumberBase::Ten), $num->getValue(NumberBase::Ten)));

            return Numbers::make(Numbers::IMMUTABLE, $val, $this->getScale());
        }

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

    /**
     * This function is a PHP implementation of the Miller-Rabin primality test. The default "certainty" value of 40
     * results in a false-positive rate of 1 in 1.21 x 10^24.
     *
     * Presumably, the probability of your hardware failing while this code is running is higher, meaning this should be
     * statistically as certain as a deterministic algorithm on normal computer hardware.
     *
     * @param int|null $certainty The certainty level desired. False positive rate = 1 in 4^$certainty.
     * @return bool
     */
    public function isPrime(?int $certainty = 40): bool
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
        } elseif ($this->isEqual(1)) {
            return false;
        }

        if (function_exists('gmp_prob_prime')) {
            return (bool)gmp_prob_prime($this->getValue(NumberBase::Ten), $certainty);
        }

        $thisNum = Numbers::makeOrDont(Numbers::IMMUTABLE, $this, $this->getScale());

        $s = $thisNum->subtract(1);
        $d = $thisNum->subtract(1);
        $r = Numbers::makeZero();

        while ($d->modulo(2)->isEqual(0)) {
            $r = $r->add(1);
            $d = $d->divide(2);
        }

        $r = $r->subtract(1);

        for ($i = 0;$i < $certainty;$i++) {
            $a = RandomProvider::randomInt(2, $s, RandomMode::Speed);
            $x = $a->pow($d)->modulo($thisNum);
            if ($x->isEqual(1) || $x->isEqual($s)) {
                continue;
            }
            for ($j = 0;$j < $r->asInt();$j++) {
                $x = $x->pow(2)->modulo($thisNum);
                if ($x->isEqual($s)) {
                    continue 2;
                }
            }
            return false;
        }

        return true;
    }

    /**
     * @return NumberCollection
     * @throws IntegrityConstraint
     * @throws MissingPackage
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
     * @return NumberCollection
     */
    public function asPrimeFactors(): NumberCollection
    {

        return new NumberCollection();

    }

}