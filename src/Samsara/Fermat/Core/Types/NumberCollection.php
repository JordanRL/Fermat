<?php

namespace Samsara\Fermat\Core\Types;

use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Enums\RandomMode;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Provider\ArithmeticProvider;
use Samsara\Fermat\Core\Provider\RandomProvider;
use Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface;
use Ds\Vector;
use Samsara\Fermat\Expressions\Values\Algebra\PolynomialFunction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Stats\Values\Distribution\Normal;
use Samsara\Fermat\Stats\Values\Distribution\Exponential;
use Samsara\Fermat\Stats\Values\Distribution\Poisson;
use Traversable;

/**
 * @package Samsara\Fermat\Core
 */
class NumberCollection implements NumberCollectionInterface, \ArrayAccess, \IteratorAggregate
{

    /**
     * @var Vector
     */
    private Vector $collection;

    /**
     * NumberCollection constructor.
     *
     * @param array $numbers
     * @throws IntegrityConstraint
     */
    public function __construct(array $numbers = [])
    {
        $this->collection = new Vector();

        if (count($numbers)) {
            $this->collect($numbers);
        }
    }

    /**
     * @return Vector
     */
    private function getCollection(): Vector
    {
        return $this->collection;
    }

    /**
     * @param array $numbers
     *
     * @return NumberCollectionInterface
     * @throws IntegrityConstraint
     */
    public function collect(array $numbers): NumberCollectionInterface
    {
        if ($this->getCollection()->count()) {
            throw new IntegrityConstraint(
                'Collections cannot be overwritten',
                'Instantiate a new NumberCollection for these values',
                'An attempt was made to collect into a non-empty NumberCollection; use empty NumberCollections for new values, or push the values into the existing collection'
            );
        }

        $immutableNumbers = [];
        foreach ($numbers as $number) {
            $immutableNumbers[] = Numbers::makeOrDont(Numbers::IMMUTABLE, $number);
        }

        $this->collection = new Vector($immutableNumbers);

        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->getCollection()->count();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->collection->toArray();
    }

    /**
     * @return int
     */
    public function selectScale(): int
    {
        $scale = 0;

        foreach ($this->collection as $value) {
            if ($value->getScale() > $scale) {
                $scale = $value->getScale();
            }
        }

        return $scale;
    }

    /**
     * @param ImmutableDecimal $number
     *
     * @return NumberCollectionInterface
     */
    public function push(ImmutableDecimal $number): NumberCollectionInterface
    {
        $this->getCollection()->push($number);

        return $this;
    }

    /**
     * @return ImmutableDecimal
     */
    public function pop(): ImmutableDecimal
    {
        return $this->getCollection()->pop();
    }

    /**
     * @param ImmutableDecimal $number
     *
     * @return NumberCollectionInterface
     */
    public function unshift(ImmutableDecimal $number): NumberCollectionInterface
    {
        $this->getCollection()->unshift($number);

        return $this;
    }

    /**
     * @return ImmutableDecimal
     */
    public function shift(): ImmutableDecimal
    {
        return $this->getCollection()->shift();
    }

    /**
     * @param array $filters
     * @return NumberCollection
     */
    public function filterByKeys(array $filters): NumberCollection
    {

        $filteredCollection = new NumberCollection();

        foreach ($this->collection as $key => $value) {
            if (in_array($key, $filters)) {
                continue;
            }

            $filteredCollection->push($value);
        }

        return $filteredCollection;

    }

    /**
     * @return NumberCollectionInterface
     */
    public function sort(): NumberCollectionInterface
    {
        $this->getCollection()->sort(function($left, $right){
            return ArithmeticProvider::compare($left->getAsBaseTenRealNumber(), $right->getAsBaseTenRealNumber());
        });

        return $this;
    }

    /**
     * @return NumberCollectionInterface
     */
    public function reverse(): NumberCollectionInterface
    {
        $this->getCollection()->reverse();

        return $this;
    }

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function add($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableDecimal $value */
            return $value->add($number);
        });

        return $this;
    }

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function subtract($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableDecimal $value */
            return $value->subtract($number);
        });

        return $this;
    }

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function multiply($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableDecimal $value */
            return $value->multiply($number);
        });

        return $this;
    }

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function divide($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableDecimal $value */
            return $value->divide($number);
        });

        return $this;
    }

    /**
     * Raises each element in the collection to the exponent $number
     *
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function pow($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableDecimal $value */
            return $value->pow($number);
        });

        return $this;
    }

    /**
     * Replaces each element in the collection with $base to the power of that value. If no base is given, Euler's number
     * is assumed to be the base (as is assumed in most cases where an exp() function is encountered in math)
     *
     * @param $base
     *
     * @return NumberCollectionInterface
     * @throws IntegrityConstraint
     */
    public function exp($base = null): NumberCollectionInterface
    {
        if (is_null($base)) {
            $base = Numbers::makeE();
        } else {
            $base = Numbers::makeOrDont(Numbers::IMMUTABLE, $base);
        }
        $this->getCollection()->apply(function($value) use ($base){
            /** @var ImmutableDecimal $value */
            return $base->pow($value);
        });

        return $this;
    }

    /**
     * @param int $key
     *
     * @return ImmutableDecimal
     */
    public function get(int $key): ImmutableDecimal
    {
        return $this->getCollection()->get($key);
    }

    /**
     * @return ImmutableDecimal
     */
    public function getRandom(): ImmutableDecimal
    {
        $maxKey = $this->getCollection()->count() - 1;

        $key = RandomProvider::randomInt(0, $maxKey, RandomMode::Speed)->asInt();

        return $this->get($key);
    }

    /**
     * @return ImmutableDecimal
     */
    public function sum(): ImmutableDecimal
    {
        $sum = Numbers::makeZero();

        foreach ($this->getCollection() as $number) {
            $sum = $sum->add($number);
        }

        return $sum;
    }

    /**
     * @return ImmutableDecimal
     */
    public function mean(): ImmutableDecimal
    {
        return $this->sum()->divide($this->getCollection()->count());
    }

    /**
     * @return ImmutableDecimal
     */
    public function average(): ImmutableDecimal
    {
        return $this->mean();
    }

    /**
     * @return Normal
     * @throws IntegrityConstraint
     */
    public function makeNormalDistribution(): Normal
    {

        $mean = $this->mean();

        $squaredSum = Numbers::makeZero();

        /** @var ImmutableDecimal $number */
        foreach ($this->getCollection() as $number) {
            $squaredSum = $squaredSum->add($number->subtract($mean)->pow(2));
        }

        unset($number);

        $squaredAverage = $squaredSum->divide($this->collection->count());

        $sd = $squaredAverage->sqrt();

        return new Normal($mean, $sd);
    }

    /**
     * @return Poisson
     * @throws IntegrityConstraint
     */
    public function makePoissonDistribution(): Poisson
    {

        $sum = $this->sum();

        $events = Numbers::make(Numbers::IMMUTABLE, $this->getCollection()->count());

        $lambda = $events->divide($sum);

        return new Poisson($lambda);
    }

    /**
     * @return Exponential
     * @throws IntegrityConstraint
     */
    public function makeExponentialDistribution(): Exponential
    {
        $average = $this->mean();

        $one = Numbers::makeOne();

        $lambda = $one->divide($average);

        return new Exponential($lambda);
    }

    /**
     * @return PolynomialFunction
     */
    public function makePolynomialFunction(): PolynomialFunction
    {

        $coefs = $this->collection->toArray();

        return new PolynomialFunction($coefs);

    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->getCollection()->offsetExists($offset);
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->getCollection()->offsetGet($offset);
    }

    /**
     * @param $offset
     * @param $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->getCollection()->offsetSet($offset, $value);
    }

    /**
     * @param $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        $this->getCollection()->offsetUnset($offset);
    }

    /**
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return $this->getCollection()->getIterator();
    }
}