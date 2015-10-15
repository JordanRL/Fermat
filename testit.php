<?php

require './tests/bootstrap.php';

$numberInBase10 = new \Samsara\Fermat\Values\ImmutableNumber(100.1);

echo $numberInBase10->getValue().PHP_EOL;

$oneHundredInNewBase = $numberInBase10->convertToBase(2);

echo $oneHundredInNewBase->getValue().PHP_EOL;

$oneHundredInNewBaseFromNewBase = $oneHundredInNewBase->convertToBase(16);

echo $oneHundredInNewBaseFromNewBase->getValue().PHP_EOL;