<?php

namespace Samsara\Fermat\Types\Base;

use Samsara\Fermat\Provider\Distribution\Exponential;
use Samsara\Fermat\Provider\Distribution\Normal;
use Samsara\Fermat\Provider\Distribution\Poisson;

interface NumberCollectionInterface
{

    public function collect(array $numbers): NumberCollectionInterface;

    public function push(NumberInterface $number): NumberCollectionInterface;

    public function pop(): NumberInterface;

    public function unshift(NumberInterface $number): NumberCollectionInterface;

    public function shift(): NumberInterface;

    public function sort(): NumberCollectionInterface;

    public function reverse(): NumberCollectionInterface;

    public function multiply($number): NumberCollectionInterface;

    public function divide($number): NumberCollectionInterface;

    public function add($number): NumberCollectionInterface;

    public function subtract($number): NumberCollectionInterface;

    public function pow($number): NumberCollectionInterface;

    public function exp($number): NumberCollectionInterface;

    public function get(int $key): NumberInterface;

    public function sum(): NumberInterface;

    public function mean(): NumberInterface;

    public function makeNormalDistribution(): Normal;

    public function makePoissonDistribution(): Poisson;

    public function makeExponentialDistribution(): Exponential;

}