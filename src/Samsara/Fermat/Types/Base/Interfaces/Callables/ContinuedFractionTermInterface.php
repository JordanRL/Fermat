<?php


namespace Samsara\Fermat\Types\Base\Interfaces\Callables;


use Samsara\Fermat\Values\ImmutableDecimal;

interface ContinuedFractionTermInterface
{

    public function __invoke(int $n): ImmutableDecimal;

}