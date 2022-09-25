<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Numbers;

/**
 *
 */
interface SimpleNumberInterface extends NumberInterface
{

    /**
     * @param $value
     *
     * @return int
     */
    public function compare($value): int;

    /**
     * @return bool
     */
    public function isNegative(): bool;

    /**
     * @return bool
     */
    public function isPositive(): bool;

    /**
     * @return string
     */
    public function getAsBaseTenRealNumber(): string;

    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isGreaterThan($value): bool;

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isLessThan($value): bool;

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isGreaterThanOrEqualTo($value): bool;

    /**
     * @param int|string|NumberInterface $value
     *
     * @return bool
     */
    public function isLessThanOrEqualTo($value): bool;

}