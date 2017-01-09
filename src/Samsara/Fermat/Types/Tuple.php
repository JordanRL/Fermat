<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\ImmutableNumber;

class Tuple
{

    /**
     * @var int
     */
    private $size;

    /**
     * @var ImmutableNumber[]
     */
    private $data;


    public function __construct(...$data)
    {
        $this->data = Numbers::makeOrDont(Numbers::IMMUTABLE, $data);
        $this->size = count($this->data);
    }

    /**
     * @param $index
     * @return ImmutableNumber
     */
    public function get(int $index): ImmutableNumber
    {
        if (array_key_exists($index, $this->data)) {
            return $this->data[$index];
        }

        throw new \InvalidArgumentException('Undefined index requested on tuple.');
    }

    /**
     * @param int $index
     * @param ImmutableNumber $value
     * @return $this
     */
    public function set(int $index, ImmutableNumber $value)
    {
        if (array_key_exists($index, $this->data)) {
            $this->data[$index] = $value;

            return $this;
        }

        throw new \InvalidArgumentException('Cannot set value for index that does not exist in tuple.');
    }

    public function all(): array
    {
        return $this->data;
    }

    public function size(): int
    {
        return $this->size;
    }

}