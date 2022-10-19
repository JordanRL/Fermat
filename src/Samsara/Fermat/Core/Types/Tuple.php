<?php

namespace Samsara\Fermat\Core\Types;

use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\IntegrityConstraint;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 *
 */
class Tuple
{

    /**
     * @var int
     */
    private int $size;

    /**
     * @var ImmutableDecimal[]
     */
    private array $data;


    /**
     * @param ...$data
     * @throws IntegrityConstraint
     */
    public function __construct(...$data)
    {
        if (is_array($data[0])) {
            $data = $data[0];
        }
        $this->data = Numbers::makeOrDont(Numbers::IMMUTABLE, $data);
        $this->size = count($this->data);
    }

    /**
     * @param int $index
     *
     * @return ImmutableDecimal
     * @throws IncompatibleObjectState
     */
    public function get(int $index): ImmutableDecimal
    {
        if ($this->hasIndex($index)) {
            return $this->data[$index];
        }

        throw new IncompatibleObjectState(
            'An index must be set on a tuple before it can be retrieved.',
            'Only request indexes that exist on the tuple you are using.',
            'Requested index '.$index.' is unset in tuple.'
        );
    }

    /**
     * @param int $index
     * @param ImmutableDecimal $value
     *
     * @return $this
     * @throws IncompatibleObjectState
     */
    public function set(int $index, ImmutableDecimal $value): self
    {
        if ($this->hasIndex($index)) {
            $this->data[$index] = $value;

            return $this;
        }

        throw new IncompatibleObjectState(
            'Tuples cannot create new indexes after construction.',
            'Only set values for indexes that already exist. To add an index, create a new tuple.',
            'Cannot set value for index '.$index.' that does not exist in tuple.'
        );
    }

    /**
     * @return array|ImmutableDecimal
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * @param int $index
     * @return bool
     */
    public function hasIndex(int $index): bool
    {
        return array_key_exists($index, $this->data);
    }

}