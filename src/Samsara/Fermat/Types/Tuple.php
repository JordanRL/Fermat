<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Values\Base\NumberInterface;

class Tuple
{

    /**
     * @var NumberInterface[]
     */
    private $data;


    public function __construct(...$data)
    {
        $this->data = Numbers::makeOrDont(Numbers::IMMUTABLE, $data);
    }

    /**
     * @param $index
     * @return NumberInterface
     */
    public function get($index)
    {
        if (array_key_exists($index, $this->data)) {
            return $this->data[$index];
        }

        throw new \InvalidArgumentException('Undefined index requested on tuple.');
    }

    /**
     * @param int $index
     * @param NumberInterface $value
     * @return $this
     */
    public function set($index, NumberInterface $value)
    {
        if (array_key_exists($index, $this->data)) {
            $this->data[$index] = $value;

            return $this;
        }

        throw new \InvalidArgumentException('Cannot set value for index that does not exist in tuple.');
    }

    public function all()
    {
        return $this->data;
    }

}