<?php

require_once './vendor/autoload.php';

use Samsara\Fermat\Values\ImmutableNumber;

$num = new ImmutableNumber('0.5');

echo $num->arcsin(12)->getValue().PHP_EOL;