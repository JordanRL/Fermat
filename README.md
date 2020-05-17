# Fermat

[![Build Status](https://travis-ci.org/JordanRL/Fermat.svg?branch=master)](https://travis-ci.org/JordanRL/Fermat) [![Code Coverage](https://scrutinizer-ci.com/g/JordanRL/Fermat/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/JordanRL/Fermat/?branch=master) [![Latest Stable Version](https://poser.pugx.org/samsara/fermat/v/stable)](https://packagist.org/packages/samsara/fermat) [![Total Downloads](https://poser.pugx.org/samsara/fermat/downloads)](https://packagist.org/packages/samsara/fermat) [![Latest Unstable Version](https://poser.pugx.org/samsara/fermat/v/unstable)](https://packagist.org/packages/samsara/fermat) [![License](https://poser.pugx.org/samsara/fermat/license)](https://packagist.org/packages/samsara/fermat)

**This project is unit tested against 7.2+, and merges are not accepted unless the tests pass.**

## Installation

To install, simply require the package using composer:

    composer require samsara/fermat "1.*"
    
Or include it in your `composer.json` file:

```json
{
    "require": {
        "samsara/fermat": "1.*"
    }
}
```

The project namespace is `Samsara\Fermat\*`. You can view the project on [Packagist](https://packagist.org/packages/samsara/fermat).

## Documentation

The `Samsara\Fermat\Numbers` factory class provides a way to use the Value classes in Fermat without being as specific as those classes may require. Consider the following code:

```php
<?php

use Samsara\Fermat\Numbers;

$five = Numbers::make(Numbers::IMMUTABLE, 5);
$ten = Numbers::make(Numbers::IMMUTABLE, '10');

echo $five->add($ten); // Prints: "15"
```

Note that the `make()` method allows you to provide both an int and a string as the value. In fact, it also allows you to provide a float. The first argument is the specific class that will be used for the value, the second argument is the value itself. The third and fourth arguments are optional and represent the scale (in number of decimal places) and the base of the number respectively. The scale and base arguments will only accept integer values.

If you do not specify a scale value, and you are using the default values, it automatically has a scale of either 10, or the string length of the input value, whichever is greater.

Here is an example of using the factory method to make a value that is in a base other than base10:

```php
<?php

use Samsara\Fermat\Numbers;

$five = Numbers::make(Numbers::IMMUTABLE, '10', null, 5); // Value in base5
$ten = Numbers::make(Numbers::IMMUTABLE, '10'); // Value in base10

echo $ten->add($five); // Prints: "15" (The sum in base10)
echo $five->add($ten); // Prints: "30" (The sum in base5)
```

You can also use a `Fraction` and `Number` together:

```php
<?php

use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Values\ImmutableFraction;

$five = new ImmutableDecimal(5);
$oneQuarter = new ImmutableFraction(1, 4);

echo $five->add($oneQuarter); // Prints: "5.25"
// The asDecimal() method is called on $oneQuarter

echo $oneQuarter->add($five); // Prints: "21/4"
// Calls getValue() on $five and instantiates a new ImmutableFraction
```

You can read the full documentation for Fermat [here](https://github.com/JordanRL/Fermat/wiki/01.-Introduction).

## Contributing

Please ensure that pull requests meet the following guidelines:

- New files created in the pull request must have a corresponding unit test file, or must be covered within an existing test file.
- Your merge may not drop the project's test coverage below 85%.
- Your merge may not drop the project's test coverage by MORE than 5%.
- Your merge must pass Travis-CI build tests for PHP 7.X.

For more information, please see the section on [Contributing](CONTRIBUTING.md)
