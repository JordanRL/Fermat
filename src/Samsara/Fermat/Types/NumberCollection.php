<?php

namespace Samsara\Fermat\Types;

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

    public function __construct(array $numbers = [])
    {
        if (count($numbers)) {
            $this->collect($numbers);
        }
    }

    private function getCollection(): Vector
    {
        return $this->collection;
    }

    public function collect(array $numbers): NumberCollectionInterface
    {
        $immutableNumbers = [];
        foreach ($numbers as $number) {
            $immutableNumbers[] = Numbers::makeOrDont(Numbers::IMMUTABLE, $number);
        }

        $this->collection = new Vector($immutableNumbers);

        return $this;
    }

    public function push(NumberInterface $number): NumberCollectionInterface
    {
        $this->getCollection()->push($number);

        return $this;
    }

    public function pop(): NumberInterface
    {
        return $this->getCollection()->pop();
    }

    public function unshift(NumberInterface $number): NumberCollectionInterface
    {
        $this->getCollection()->unshift($number);

        return $this;
    }

    public function shift(): NumberInterface
    {
        return $this->getCollection()->shift();
    }

    public function sort(): NumberCollectionInterface
    {
        $this->getCollection()->sort(function($left, $right){
            return ArithmeticProvider::compare($left, $right);
        });

        return $this;
    }

    public function reverse(): NumberCollectionInterface
    {
        $this->getCollection()->reverse();

        return $this;
    }

    public function add($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableNumber $value */
            return $value->add($number);
        });

        return $this;
    }

    public function subtract($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableNumber $value */
            return $value->subtract($number);
        });

        return $this;
    }

    public function multiply($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableNumber $value */
            return $value->multiply($number);
        });

        return $this;
    }

    public function divide($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableNumber $value */
            return $value->divide($number);
        });

        return $this;
    }

    public function pow($number): NumberCollectionInterface
    {
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableNumber $value */
            return $value->pow($number);
        });

        return $this;
    }

    public function exp($number): NumberCollectionInterface
    {
        $number = Numbers::makeOrDont(Numbers::IMMUTABLE, $number);
        $this->getCollection()->apply(function($value) use ($number){
            /** @var ImmutableNumber $value */
            return $number->pow($value);
        });

        return $this;
    }

    public function get(int $key): NumberInterface
    {
        return $this->getCollection()->get($key);
    }

    public function sum(): NumberInterface
    {
        $sum = Numbers::makeZero();

        foreach ($this->getCollection() as $number) {
            $sum = $sum->add($number);
        }

        return $sum;
    }

    public function mean(): NumberInterface
    {
        return $this->sum()->divide($this->getCollection()->count());
    }

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

    public function makePoissonDistribution(): Poisson
    {
        $sum = $this->sum();

        $events = Numbers::make(Numbers::IMMUTABLE, $this->getCollection()->count());

        $lambda = $events->divide($sum);

        return new Poisson($lambda);
    }

    public function makeExponentialDistribution(): Exponential
    {
        $average = $this->mean();

        $one = Numbers::makeOne();

        $lambda = $one->divide($average);

        return new Exponential($lambda);
    }

}