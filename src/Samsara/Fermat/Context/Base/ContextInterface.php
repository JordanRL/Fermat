<?php

namespace Samsara\Fermat\Context\Base;

interface ContextInterface
{

    public function random($min = 0, $max = PHP_INT_MAX);

}