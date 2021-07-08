<?php


namespace Samsara\Fermat\Renderer\Components\Symbols;

use Samsara\Fermat\Renderer\Components\Interfaces\ComponentInterface;


class ThetaSymbol implements ComponentInterface
{

    public function getOutput(): string
    {
        return '\\theta';
    }
}