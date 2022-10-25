<?php

namespace Samsara\Fermat\Core\Types\Base\Interfaces\Groups;

use Samsara\Fermat\Stats\Values\Distribution\Exponential;
use Samsara\Fermat\Stats\Values\Distribution\Normal;
use Samsara\Fermat\Stats\Values\Distribution\Poisson;
use Samsara\Fermat\Expressions\Values\Algebra\PolynomialFunction;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Core
 */
interface NumberCollectionInterface
{

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
     * @return array
     */
    public function toArray(): array;

    /**
     * @param ImmutableDecimal $number
     *
     * @return NumberCollectionInterface
     */
    public function push(ImmutableDecimal $number): NumberCollectionInterface;

    /**
     * @return ImmutableDecimal
     */
    public function pop(): ImmutableDecimal;

    /**
     * @param ImmutableDecimal $number
     *
     * @return NumberCollectionInterface
     */
    public function unshift(ImmutableDecimal $number): NumberCollectionInterface;

    /**
     * @return ImmutableDecimal
     */
    public function shift(): ImmutableDecimal;

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
     * @return ImmutableDecimal
     */
    public function get(int $key): ImmutableDecimal;

    /**
     * @return ImmutableDecimal
     */
    public function getRandom(): ImmutableDecimal;

    /**
     * @return ImmutableDecimal
     */
    public function sum(): ImmutableDecimal;

    /**
     * @return ImmutableDecimal
     */
    public function mean(): ImmutableDecimal;

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

    /**
     * @return PolynomialFunction
     */
    public function makePolynomialFunction(): PolynomialFunction;

}