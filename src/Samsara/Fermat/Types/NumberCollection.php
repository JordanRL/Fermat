<?php

namespace Samsara\Fermat\Types;

use RandomLib\Factory;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\ArithmeticProvider;
use Samsara\Fermat\Provider\Distribution\Exponential;
use Samsara\Fermat\Provider\Distribution\Normal;
use Samsara\Fermat\Provider\Distribution\Poisson;
use Samsara\Fermat\Types\Base\NumberCollectionInterface;
use Ds\Vector;
use Samsara\Fermat\Types\Base\NumberInterface;
use Samsara\Fermat\Values\ImmutableNumber;

class NumberCollection implements NumberCollectionInterface
{

    private $collection;

    /**
     * NumberCollection constructor.
     *
     * @param array $numbers
     * @throws IntegrityConstraint
     */
    public function __construct(array $numbers = [])
    {
        if (count($numbers)) {
            $this->collect($numbers);
        } else {
            $this->collection = new Vector();
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
        $immutableNumbers = [];
        foreach ($numbers as $number) {
            $immutableNumbers[] = Numbers::makeOrDont(Numbers::IMMUTABLE, $number);
        }

        $this->collection = new Vector($immutableNumbers);

        return $this;
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

    /**
     * @return NumberCollectionInterface
     */
    public function sort(): NumberCollectionInterface
    {
        $this->getCollection()->sort(function($left, $right){
            return ArithmeticProvider::compare($left, $right);
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
            /** @var ImmutableNumber $value */
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
            /** @var ImmutableNumber $value */
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
            /** @var ImmutableNumber $value */
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
            /** @var ImmutableNumber $value */
            return $value->divide($number);
        });

        return $this;
    }

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function pow($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableNumber $value */
            return $value->pow($number);
        });

        return $this;
    }

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     * @throws IntegrityConstraint
     */
    public function exp($number): NumberCollectionInterface
    {
        $number = Numbers::makeOrDont(Numbers::IMMUTABLE, $number);
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableNumber $value */
            return $number->pow($value);
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

        try {
            $key = random_int(0, $maxKey);
        } catch (\Exception $exception) {
            $randFactory = new Factory();
            $generator = $randFactory->getMediumStrengthGenerator();
            $key = $generator->generateInt(0, $maxKey);
        }

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
     * @throws IntegrityConstraint
     */
    public function makeNormalDistribution(): Normal
    {
        /** @var ImmutableNumber $mean */
        $mean = $this->mean();

        $squaredSum = Numbers::makeZero();

        /** @var ImmutableNumber $number */
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

}