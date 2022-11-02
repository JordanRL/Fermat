<?php

namespace Samsara\Fermat\Core\Types\Base\Interfaces\Groups;

use Samsara\Fermat\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Expressions\Values\Algebra\PolynomialFunction;
use Samsara\Fermat\Stats\Distribution\Continuous\Exponential;
use Samsara\Fermat\Stats\Distribution\Continuous\Normal;
use Samsara\Fermat\Stats\Distribution\Discrete\Poisson;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Core
 */
interface NumberCollectionInterface
{

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function add($number): NumberCollectionInterface;

    /**
     * @param array $numbers
     *
     * @return NumberCollectionInterface
     */
    public function collect(array $numbers): NumberCollectionInterface;

    /**
     * @return int
     */
    public function count(): int;

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
    public function exp($number): NumberCollectionInterface;

    /**
     * @param int $key
     *
     * @return ImmutableDecimal
     */
    public function get(int $key): ImmutableDecimal;

    /**
     * @return ImmutableDecimal
     */
    public function getRandom(): ImmutableDecimal;

    /**
     * @return Exponential
     */
    public function makeExponentialDistribution(): Exponential;

    /**
     * @return Normal
     */
    public function makeNormalDistribution(): Normal;

    /**
     * @return Poisson
     */
    public function makePoissonDistribution(): Poisson;

    /**
     * @return PolynomialFunction
     */
    public function makePolynomialFunction(): PolynomialFunction;

    /**
     * @return ImmutableDecimal
     */
    public function mean(): ImmutableDecimal;

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function multiply($number): NumberCollectionInterface;

    /**
     * @return ImmutableDecimal
     */
    public function pop(): ImmutableDecimal;

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function pow($number): NumberCollectionInterface;

    /**
     * @param ImmutableDecimal $number
     *
     * @return NumberCollectionInterface
     */
    public function push(ImmutableDecimal $number): NumberCollectionInterface;

    /**
     * @return NumberCollectionInterface
     */
    public function reverse(): NumberCollectionInterface;

    /**
     * @return ImmutableDecimal
     */
    public function shift(): ImmutableDecimal;

    /**
     * @return NumberCollectionInterface
     */
    public function sort(): NumberCollectionInterface;

    /**
     * @param $number
     *
     * @return NumberCollectionInterface
     */
    public function subtract($number): NumberCollectionInterface;

    /**
     * @return ImmutableDecimal
     */
    public function sum(): ImmutableDecimal;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param ImmutableDecimal $number
     *
     * @return NumberCollectionInterface
     */
    public function unshift(ImmutableDecimal $number): NumberCollectionInterface;

}