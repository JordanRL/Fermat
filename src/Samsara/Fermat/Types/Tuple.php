<?php

namespace Samsara\Fermat\Types;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
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
     *
     * @throws IncompatibleObjectState
     * @return ImmutableNumber
     */
    public function get(int $index): ImmutableNumber
    {
        if (array_key_exists($index, $this->data)) {
            return $this->data[$index];
        }

        throw new IncompatibleObjectState('Requested index '.$index.' is unset in tuple');
    }

    /**
     * @param int $index
     * @param ImmutableNumber $value
     *
     * @throws IncompatibleObjectState
     * @return $this
     */
    public function set(int $index, ImmutableNumber $value)
    {
        if (array_key_exists($index, $this->data)) {
            $this->data[$index] = $value;

            return $this;
        }

        throw new IncompatibleObjectState('Cannot set value for index '.$index.' that does not exist in tuple');
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