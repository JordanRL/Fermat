<?php

namespace Samsara\Fermat\Types\Base;

use Samsara\Fermat\Provider\Distribution\Exponential;
use Samsara\Fermat\Provider\Distribution\Normal;
use Samsara\Fermat\Provider\Distribution\Poisson;

interface NumberCollectionInterface
{

    /**
     * @param array $numbers
     *
     * @return NumberCollectionInterface
     */
    public function collect(array $numbers): NumberCollectionInterface;

    /**
     * @param NumberInterface $number
     *
     * @return NumberCollectionInterface
     */
    public function push(NumberInterface $number): NumberCollectionInterface;

    /**
     * @return NumberInterface
     */
    public function pop(): NumberInterface;

    /**
     * @param NumberInterface $number
     *
     * @return NumberCollectionInterface
     */
    public function unshift(NumberInterface $number): NumberCollectionInterface;

    /**
     * @return NumberInterface
     */
    public function shift(): NumberInterface;

    /**
     * @return NumberCollectionInterface
     */
    public function sort(): NumberCollectionInterface;

    /**
     * @return NumberCollectionInterface
     */
    public function reverse(): NumberCollectionInterface;

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function multiply($number): NumberCollectionInterface;

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function divide($number): NumberCollectionInterface;

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function add($number): NumberCollectionInterface;

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function subtract($number): NumberCollectionInterface;

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function pow($number): NumberCollectionInterface;

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function exp($number): NumberCollectionInterface;

    /**
     * @param int $key
     *
     * @return NumberInterface
     */
    public function get(int $key): NumberInterface;

    /**
     * @return NumberInterface
     */
    public function getRandom(): NumberInterface;

    /**
     * @return NumberInterface
     */
    public function sum(): NumberInterface;

    /**
     * @return NumberInterface
     */
    public function mean(): NumberInterface;

    /**
     * @return Normal
     */
    public function makeNormalDistribution(): Normal;

    /**
     * @return Poisson
     */
    public function makePoissonDistribution(): Poisson;

    /**
     * @return Exponential
     */
    public function makeExponentialDistribution(): Exponential;

}