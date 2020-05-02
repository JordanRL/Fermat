<?php

namespace Samsara\Fermat\Types\Base\Interfaces\Numbers;

interface ComplexNumberInterface extends NumberInterface
{

    public function getRealPart(): SimpleNumberInterface;

    public function getImaginaryPart(): SimpleNumberInterface;

    public function getValue(): string;

}