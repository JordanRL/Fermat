<?php

namespace Samsara\Fermat\Types;

use Composer\InstalledVersions;
use Samsara\Exceptions\SystemError\PlatformError\MissingPackage;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Provider\Distribution\Exponential;
use Samsara\Fermat\Provider\Distribution\Normal;
use Samsara\Fermat\Provider\Distribution\Poisson;
use Samsara\Fermat\Provider\RandomProvider;
use Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;
use Ds\Vector;
use Samsara\Fermat\Values\Algebra\PolynomialFunction;
use Samsara\Fermat\Values\ImmutableDecimal;

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
        if (!is_null($this->collection) && $this->getCollection()->count()) {
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

    public function count(): int
    {
        return $this->getCollection()->count();
    }

    public function toArray(): array
    {
        return $this->collection->toArray();
    }

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
     * @param NumberInterface $number
     *
     * @return NumberCollectionInterface
     */
    public function push(NumberInterface $number): NumberCollectionInterface
    {
        $this->getCollection()->push($number);

        return $this;
    }

    /**
     * @return NumberInterface
     */
    public function pop(): NumberInterface
    {
        return $this->getCollection()->pop();
    }

    /**
     * @param NumberInterface $number
     *
     * @return NumberCollectionInterface
     */
    public function unshift(NumberInterface $number): NumberCollectionInterface
    {
        $this->getCollection()->unshift($number);

        return $this;
    }

    /**
     * @return NumberInterface
     */
    public function shift(): NumberInterface
    {
        return $this->getCollection()->shift();
    }

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
     * @return NumberInterface
     */
    public function get(int $key): NumberInterface
    {
        return $this->getCollection()->get($key);
    }

    /**
     * @return NumberInterface
     */
    public function getRandom(): NumberInterface
    {
        $maxKey = $this->getCollection()->count() - 1;

        $key = RandomProvider::randomInt(0, $maxKey, RandomProvider::MODE_SPEED)->asInt();

        return $this->get($key);
    }

    /**
     * @return NumberInterface
     */
    public function sum(): NumberInterface
    {
        $sum = Numbers::makeZero();

        foreach ($this->getCollection() as $number) {
            $sum = $sum->add($number);
        }

        return $sum;
    }

    /**
     * @return NumberInterface
     */
    public function mean(): NumberInterface
    {
        return $this->sum()->divide($this->getCollection()->count());
    }

    /**
     * @return Normal
     * @throws IntegrityConstraint|MissingPackage
     */
    public function makeNormalDistribution(): Normal
    {
        if (!(InstalledVersions::isInstalled("samsara/fermat-stats"))) {
            throw new MissingPackage(
                'Creating distributions is unsupported in Fermat without modules.',
                'Install the samsara/fermat-stats package using composer.',
                'An attempt was made to create a Distribution instance without having the Stats module. Please install the samsara/fermat-stats package using composer.'
            );
        }

        /** @var ImmutableDecimal $mean */
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
     * @throws IntegrityConstraint|MissingPackage
     */
    public function makePoissonDistribution(): Poisson
    {
        if (!(InstalledVersions::isInstalled("samsara/fermat-stats"))) {
            throw new MissingPackage(
                'Creating distributions is unsupported in Fermat without modules.',
                'Install the samsara/fermat-stats package using composer.',
                'An attempt was made to create a Distribution instance without having the Stats module. Please install the samsara/fermat-stats package using composer.'
            );
        }

        $sum = $this->sum();

        $events = Numbers::make(Numbers::IMMUTABLE, $this->getCollection()->count());

        $lambda = $events->divide($sum);

        return new Poisson($lambda);
    }

    /**
     * @return Exponential
     * @throws IntegrityConstraint|MissingPackage
     */
    public function makeExponentialDistribution(): Exponential
    {
        if (!(InstalledVersions::isInstalled("samsara/fermat-stats"))) {
            throw new MissingPackage(
                'Creating distributions is unsupported in Fermat without modules.',
                'Install the samsara/fermat-stats package using composer.',
                'An attempt was made to create a Distribution instance without having the Stats module. Please install the samsara/fermat-stats package using composer.'
            );
        }

        $average = $this->mean();

        $one = Numbers::makeOne();

        $lambda = $one->divide($average);

        return new Exponential($lambda);
    }

    /**
     * @return PolynomialFunction
     * @throws IntegrityConstraint
     */
    public function makePolynomialFunction(): PolynomialFunction
    {
        if (!(InstalledVersions::isInstalled("samsara/fermat-algebra-expressions"))) {
            throw new MissingPackage(
                'Creating expressions is unsupported in Fermat without modules.',
                'Install the samsara/fermat-algebra-expressions package using composer.',
                'An attempt was made to create an Expression instance without having the Algebra Expressions module. Please install the samsara/fermat-algebra-expressions package using composer.'
            );
        }


        $coefs = $this->collection->toArray();

        return new PolynomialFunction($coefs);

    }

    public function offsetExists($offset)
    {
        return $this->getCollection()->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getCollection()->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->getCollection()->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->getCollection()->offsetUnset($offset);
    }

    public function getIterator()
    {
        return $this->getCollection()->getIterator();
    }
}