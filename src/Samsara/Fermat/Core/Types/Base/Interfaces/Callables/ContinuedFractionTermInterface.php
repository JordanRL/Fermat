<?php


namespace Samsara\Fermat\Core\Types\Base\Interfaces\Callables;


use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 *
 */
interface ContinuedFractionTermInterface
{

    /**
     * @param int $n
     * @return ImmutableDecimal
     */
    public function __invoke(int $n): ImmutableDecimal;

}