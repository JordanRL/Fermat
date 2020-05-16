# Installation

The Fermat library is available on [Packagist](https://packagist.org/packages/samsara/fermat), and can be installed with composer:

`composer require samsara/fermat ^2.0`

!!! note "Dependencies"
    Fermat requires the following packages:
    
    - `ircmaxell/random-lib`: Provides cross-platform random number generation
    - `riimu/kit-baseconversion`: Provides the base conversion library used internally
    - `samsara/common`: Provides the exception model used in Fermat
    
    It also requires the [BCMath](https://www.php.net/manual/en/book.bc.php) extension for PHP, however since 7.0 this extension has been included by default in distributions.

!!! tip "Improve Performance With Suggested Extensions"
    Fermat suggests that you also install the `ext-ds` extension and the `ext-gmp` extension. When present, these help reduce memory usage and computation time.
    
# Basic Usage

A basic usage of the Fermat library is straightforward and simple to use quickly.

```php
<?php

use Samsara\Fermat\Values\ImmutableDecimal;

// __construct($value, $precision = 10, $base = 10);
$five = new ImmutableDecimal(5, 20);

echo $five->pow('1.2')->sin()->getValue();
// Prints: 0.57733662664006904181
echo $five->getValue();
// Prints: 5
```

Once you have your number objects created, you can continue using them with your desired precision.

!!! see-also "See Also"
    The "Types & Values" section contains extensive detail about the exact ways that the value objects can be used.