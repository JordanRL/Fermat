<?php

namespace Samsara\Fermat\Provider\RoundingModeAdapters\Modes;

/**
 *
 */
abstract class BaseAdapter
{

    /**
     * @param bool $isNegative
     * @param string|null $remainder
     */
    public function __construct(protected bool $isNegative, protected ?string $remainder) {}

    /**
     * @param bool $isNegative
     * @return $this
     */
    public function setNegative(bool $isNegative): BaseAdapter
    {
        $this->isNegative = $isNegative;

        return $this;
    }

    /**
     * @param string|null $remainder
     * @return $this
     */
    public function setRemainder(?string $remainder): BaseAdapter
    {
        $this->remainder = $remainder;

        return $this;
    }

    /**
     * @return bool
     */
    protected function remainderCheck(): bool
    {
        if (is_null($this->remainder)) {
            return false;
        }

        $remainder = str_replace('0', '', $this->remainder);

        return !empty($remainder);
    }

    /**
     * @param int $digit
     * @return int
     */
    protected static function nonHalfEarlyReturn(int $digit): int
    {
        return $digit <=> 5;
    }

    /**
     * @param int $digit
     * @param int $nextDigit
     * @return int
     */
    abstract public function determineCarry(int $digit, int $nextDigit): int;

}