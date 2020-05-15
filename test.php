<?php

require_once './vendor/autoload.php';

use Samsara\Fermat\Values\ImmutableDecimal;

$num = new ImmutableDecimal('5', 20);

echo $num->pow('1.2')->sin()->getValue().PHP_EOL;