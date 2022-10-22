<?php

namespace Samsara\Fermat\Complex\Types\Base\Interfaces\Numbers;

use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface;
use Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\SimpleNumberInterface;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Complex
 */
interface ComplexNumberInterface extends NumberInterface
{

    public function getRealPart(): SimpleNumberInterface;

    public function getImaginaryPart(): SimpleNumberInterface;

    public function getValue(): string;

}