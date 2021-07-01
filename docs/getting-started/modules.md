# Modules

Modules are packages available within composer that provide additional functionality to the Fermat library. These packages generally cover use cases that are niche or specific enough to be necessary for only particular kinds of programs.

The modules can be included and required in any combination. While some modules depend on other modules, those dependencies are described and handled automatically by the composer package definitions.

!!! note "All Modules Support Arbitrary Precision"
    Fermat is at its core built around arbitrary precision. As such, all modules for Fermat also support arbitrary precision and using them will not change the behavior of the various value objects provided in Fermat

!!! warning "The `MissingPackage` Exception"
    There a few functions within the core Fermat library which can perform operations which require one or more of these modules to be installed. When one of these functions is called without the module being installed, a `MissingPackage` exception is thrown if the operation being performed absolutely requires that module.

    An example of this would be creating a distribution from a `NumberCollection`. This is directly supported by the `NumberCollection` class, but requires the Fermat Stats module to be installed.

    The Fermat library is unit tested for both scenarios, with and without modules installed, so any functions within Fermat that require a module have that behavior maintained, tested, and supported.

## Available Modules

These are the modules that are currently available on [Packagist.org](https://packagist.org/) and can be required directly in your project's `composer.json` file.

### Fermat Stats

!!! note "Stable"
    The Fermat Stats module has a released stable version and is available to use in production environments. To do so, require v1.0 like so:

    `composer require samsara/fermat-stats:^1.0`

The Fermat Stats modules provides various statistics functions and operations. This module has no other dependencies and can be required as a stand-alone addition to Fermat.

Many statistical functions are extremely complex, and so the scale setting on your objects tends to have a much larger impact on performance within this module than elsewhere within Fermat or its modules.

!!! see-also "See Also"
    The [Stats](../modules/stats/about.md) section of this documentation contains more detailed information about this module and its behavior.

### Fermat Coordinate Systems

!!! note "Stable"
    The Fermat Coordinate Systems module has a released stable version and is available to use in production environments. To do so, require v1.0 like so:

    `composer require samsara/fermat-coordinate-systems:^1.0`

The Fermat Coordinate Systems module provides various coordinate objects that are aware of their geometric and algebraic relations to each other. This module has no other dependencies and can be required as a stand-alone addition to Fermat.

Coordinates and coordinate systems have defined concepts of dimensionality, and certain coordinate systems are only available with a specific number of dimensions. Each dimension is represented by an `ImmutableDecimal` object which contains the value for that dimension.

!!! see-also "See Also"
    The [Coordinate Systems](../modules/coordinate-systems/about.md) section of this documentation contains more detailed information about this module and its behavior.

### Fermat Complex Numbers

!!! caution "Unstable"
    The Fermat Complex Numbers module does **not** have a released stable version and should not be used in a production environment. Imaginary numbers are supported directly by Fermat, but combining imaginary and real numbers into complex numbers is handled by this module.

    To include this module anyway, require the current development state like so:

    `composer require samsara/fermat-complex-numbers:dev-master`

The Fermat Complex Numbers module provides support for complex numbers and the mathematical operations that can be performed on them. This is the easiest module to accidentally result in a `MissingPackage` exception, as doing operations such as `add()` with two `Decimal` instances in Fermat can result in a complex number in certain situations.

This is unlikely to happen accidentally however, unless your application deals with both imaginary and real numbers at different points.

!!! note "See Also"
    The [Complex Numbers](../modules/complex-numbers/about.md) section of this documentation contains more detailed information about this module and its behavior.

### Fermat Algebra Expressions

!!! caution "Unstable"
    The Fermat Algebra Expressions module does **not** have a released stable version and should not be used in a production environment.

    To include this module anyway, require the current development state like so:

    `composer require samsara/fermat-algebra-expressions:dev-master`

The Fermat Algebra Expressions module provides support for objects which represent entire algebraic functions, instead of only specific values. This is particularly useful in situations where you want to know the derivatives or integrals of simple algebraic expressions, such a polynomials.

!!! note "See Also"
    The [Algebra Expressions](../modules/algebra-expressions/about.md) section of this documentation contains more detailed information about this module and its behavior.