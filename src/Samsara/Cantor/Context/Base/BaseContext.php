<?php

namespace Samsara\Cantor\Context\Base;

use Samsara\Cantor\Values\Base\NumberInterface;

abstract class BaseContext
{

    protected $contextBase;

    /**
     * @var string|NumberInterface
     */
    protected $numberType;

    public function setBase($base)
    {
        $this->contextBase = $base;

        return $this;
    }

    public function changeBase($base)
    {
        return $this->transformBaseContext($base)->setBase($base);
    }

    /**
     * Must be implemented by each context. This method transforms the base of ALL NumberInterface objects which are
     * stored as class properties within the context so that the actual math is preserved no matter what base it's in.
     *
     * @param   int $base
     * @return      $this
     */
    abstract protected function transformBaseContext($base);

}