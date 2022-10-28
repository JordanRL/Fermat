<?php

namespace Samsara\Fermat\Core\Enums;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Core
 */
enum RandomMode
{
    case Speed;
    case Entropy;
    case Secure;
}