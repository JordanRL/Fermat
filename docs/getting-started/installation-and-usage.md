# Installation

The Fermat library is available on [Packagist](https://packagist.org/packages/samsara/fermat), and can be installed with composer:

`composer require "samsara/fermat:^2.1"`

Or by including it in your composer.json file:

```json
{
  "require": {
    "samsara/fermat": "^2.1"
  }
}
```

!!! note "Dependencies"
    Fermat requires the following packages:
    
    - `samsara/common`: Provides the exception model used in Fermat
    
    It also requires the [BCMath](https://www.php.net/manual/en/book.bc.php) and [GMP](https://www.php.net/manual/en/book.gmp) extensions.

!!! tip "Improve Performance With Suggested Extensions"
    Fermat suggests that you also install the `ext-decimal` extension and the `ext-ds` extension. When present, these help reduce memory usage and computation time.

    In particular, the [Decimal](http://php-decimal.io/#introduction) extension results in performance increases ranging from 5x to 100x depending on the operation.
    
# Basic Usage

A basic usage of the Fermat library is straightforward and simple to use quickly.

``` php
<?php

use Samsara\Fermat\Core\Values\ImmutableDecimal;

// __construct(
//     $value, 
//     $scale = 10, 
//     $base = NumberBase::Ten, 
//     $baseTenInput = true
// );
$five = new ImmutableDecimal(5, 20);

echo $five->pow('1.2')->sin()->getValue();
// Prints: 0.57733662664006904181
echo $five->getValue();
// Prints: 5
```

Once you have your number objects created, you can continue using them with your desired scale.

!!! note "Fluency"
    Both immutable and mutable instances can be used with a [fluent interface](https://designpatternsphp.readthedocs.io/en/latest/Structural/FluentInterface/README.html).
    
    With mutable objects, this is due to the class being designed with a fluent interface inherently. With immutable objects, this is due to a new instance of the immutable object being returned.
    
    This means that each method call on an immutable object which returns an object represents a new instance being created and returned, a new zval being created by PHP, and a new set of memory being allocated.

!!! see-also "See Also"
    The "Source Reference" navigation tab contains detailed information about all the objects in Fermat.