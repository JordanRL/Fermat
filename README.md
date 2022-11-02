# Fermat

[![CircleCI](https://dl.circleci.com/status-badge/img/gh/JordanRL/Fermat/tree/master.svg?style=shield)](https://dl.circleci.com/status-badge/redirect/gh/JordanRL/Fermat/tree/master) [![Test Coverage](https://api.codeclimate.com/v1/badges/ce0c5761a8f0d3d37cf2/test_coverage)](https://codeclimate.com/github/JordanRL/Fermat/test_coverage) [![Maintainability](https://api.codeclimate.com/v1/badges/ce0c5761a8f0d3d37cf2/maintainability)](https://codeclimate.com/github/JordanRL/Fermat/maintainability) [![Latest Stable Version](https://poser.pugx.org/samsara/fermat/v/stable)](https://packagist.org/packages/samsara/fermat) [![Total Downloads](https://poser.pugx.org/samsara/fermat/downloads)](https://packagist.org/packages/samsara/fermat) [![License](https://poser.pugx.org/samsara/fermat/license)](https://packagist.org/packages/samsara/fermat)

**This project is unit tested against 8.1, and merges are not accepted unless the tests pass.**

## Installation

To install, simply require the package using composer:

    composer require "samsara/fermat:^2.1"
    
Or include it in your `composer.json` file:

```json
{
    "require": {
        "samsara/fermat": "^2.1"
    }
}
```

The project namespace is `Samsara\Fermat\Core\*`. You can view the project on [Packagist](https://packagist.org/packages/samsara/fermat).

### Modules

Modules are the namespaces outside of `Samsara\Fermat\Core` and provide functionality beyond integer, decimal, and rational numbers.

All of these modules depend on the Core namespace, while some depend on each other to various degrees.

- `Samsara\Fermat\Complex`: Provides complex number functionality. **NOTE:** Imaginary numbers are directly supported by `Core`. Complex numbers are numbers that have both a real part and an imaginary part.
- `Samsara\Fermat\Coordinates`: Provides different coordinate systems that can be used to characterize points and their relations to each other.
- `Samsara\Fermat\Expressions`: Provides various expressions, generally algebraic, that can be handled as a function instead of as a value.
- `Samsara\Fermat\LinearAlgebra`: Provides for math involving matrices and vectors.
- `Samsara\Fermat\Stats`: Provides for math involving statistics *and* probabilities.

## Documentation

The `Samsara\Fermat\Core\Numbers` factory class provides a way to use the Value classes in Fermat without being as specific as those classes may require. Consider the following code:

```php
<?php

use Samsara\Fermat\Core\Core\Numbers;

$five = Numbers::make(Numbers::IMMUTABLE, 5);
$ten = Numbers::make(Numbers::IMMUTABLE, '10');

echo $five->add($ten); // Prints: "15"
```

Note that the `make()` method allows you to provide both an int and a string as the value. In fact, it also allows you to provide a float. The first argument is the specific class that will be used for the value, the second argument is the value itself. The third and fourth arguments are optional and represent the scale (in number of decimal places) and the base of the number respectively. The scale and base arguments will only accept integer values.

If you do not specify a scale value, and you are using the default values, it automatically has a scale of either 10, or the string length of the input value, whichever is greater.

Here is an example of using the factory method to make a value that is in a base other than base10:

```php
<?php

use Samsara\Fermat\Core\Core\Numbers;
use Samsara\Fermat\Core\Core\Enums\NumberBase;

// Value in base5
$five = Numbers::make(Numbers::IMMUTABLE, '10', null, NumberBase::Five); 
// Value in base10
$ten = Numbers::make(Numbers::IMMUTABLE, '10'); 

echo $ten->add($five); // Prints: "15" (The sum in base10)
echo $five->add($ten); // Prints: "30" (The sum in base5)
```

You can also use a `Fraction` and `Number` together:

```php
<?php

use Samsara\Fermat\Core\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Core\Values\ImmutableFraction;

$five = new ImmutableDecimal(5);
$oneQuarter = new ImmutableFraction(1, 4);

echo $five->add($oneQuarter); // Prints: "5.25"
// The asDecimal() method is called on $oneQuarter

echo $oneQuarter->add($five); // Prints: "21/4"
// Calls getValue() on $five and instantiates a new ImmutableFraction
```

You can read the full documentation for Fermat [here](https://jordanrl.github.io/Fermat/).

## Contributing

Please ensure that pull requests meet the following guidelines:

- New files created in the pull request must have a corresponding unit test file, or must be covered within an existing test file.
- Your merge may not drop the project's test coverage below 80%.
- Your merge may not drop the project's test coverage by MORE than 5%.
- Your merge must pass CI build tests for PHP 8.1.

For more information, please see the section on [Contributing](CONTRIBUTING.md)
