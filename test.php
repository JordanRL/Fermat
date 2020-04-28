<?php

require_once './vendor/autoload.php';

use Samsara\Fermat\Values\ImmutableDecimal;

$num = new ImmutableDecimal('0.5');

echo $num->arcsin(12)->getValue().PHP_EOL;