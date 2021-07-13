# Comparing Fermat To Alternatives

This page covers the situations in which Fermat provides clear advantages over alternative ways to get arbitrary precision math in PHP.

## Advantages Compared To Extensions

There are several advantages to using Fermat compared to the two arbitrary precision extensions (ext-bcmath and ext-decimal) directly.

### Support For BCMath Is Already Part Of Fermat

At its core, Fermat utilizes the BCMath extension (which is included by default in modern installations of PHP) to perform the base calculations. You don't gain anything by using the BCMath library directly, as this would result in needing to manually and directly manage things like changes in scale.

All of the BCMath operations are still accessible using Fermat with the `ArithmeticProvider`, which functions as a formatting wrapper for the BCMath library.

### Fermat Provides More Complex Math Operations

While ext-bcmath and ext-decimal provide the speed of a PHP extension, this benefit only applies when performing simple arithmetic. When such simple operations are being performed, Fermat is similarly fast. The power of Fermat is its support for both arbitrary precision and more complex operations, such as [basic](trigonometry/basic.md), [hyperbolic](trigonometry/hyperbolic.md), and [inverse](trigonometry/inverse.md) trigonometry functions, $`e^x`$ and $`ln(x)`$ functions, and [fractions](types-and-values/fractions.md).

### Fermat Provides Even More Math Through Modules

On top of the extra functionality that Fermat provides in comparison to ext-bcmath and ext-decimal, more functionality is available through [Fermat Modules](getting-started/modules.md). Some of these, such as the statistics functions provided by [Fermat Stats](modules/stats/about.md), are not available via PHP extensions even for non-arbitrary math.

### Fermat Provides a Consistent Developer Experience

By providing objects which have a fluent interface and are aware of how the rest of the library works, the developer experience is incredibly consistent. You can always use the `add()` method to add two numbers. You can always ask for the tangent with `tan()`. If the library needs additional help to complete your requested operation, the [exception model](getting-started/exceptions.md) makes it easy for your program to recover and in many cases even retry the operation.

## Advantages Compared to Other Libraries

There are other libraries that provide arbitrary precision math, or provide complex mathematical functions. This is the only PHP library that provides both.

### Compared to 'brick/math'

This library does a fantastic job of providing a similarly fluent and consistent developer experience for dealing with arbitrary precision math, however it is limited to only the basic arithmetic which can be performed via the ext-bcmath extension.

Additionally, while the `brick/math` library provides only immutable values, (which to be fair are almost always better for performing mathematical operations, see [Mutable vs. Immutable](getting-started/mutability.md)), Fermat supports both mutable and immutable varieties of its values.

The lack of more complex mathematical operations in `brick/math` also prevents it from supporting some of the extra features available in Fermat through [modules](getting-started/modules.md). Statistics functions require both $`e^x`$ and $`ln(x)`$ implementations in order to be possible for instance, while math with complex numbers requires implementations of both $`sin(x)`$ and $`cos(x)`$.

Currently, Fermat does not offer a module for financial mathematics or money transactions (though this will be added in the future). Because of this, if your application needs arbitrary precision *and* does calculations with numbers that represent currencies for which you want helper functions, the brick/math library along with brick/money may be preferrable.

### Compared with 'markbaker/complex' and 'markbaker/matrix'

Two of the [Fermat modules](getting-started/modules.md) concern dealing with [complex numbers](modules/complex-numbers/about.md) and [linear algebra](modules/linear-algebra/about.md). The packages offered by Mark Baker are very widely used (over 30 million installs) and still actively developed and supported.

The Fermat library only offers an important improvement to these libraries if your application is affected by issues arising from floating point precision. Though Fermat does offer a way to skip the arbitrary precision calculations the library makes with [calculation modes](configuration/calculation-modes.md), it still may be worth it to many developers to use the library which has more eyes on it.

Despite this, some developers might find the common and unified fluent interface and consistent developer experience offered by Fermat to be worth the trade-off. As Fermat becomes more mature and stable, and the calculation mode offers more control over how efficient the calculations are, it might be more compelling to use the fluent objects offered by Fermat that can seamlessly jump between different types of math and different math paradigms while retaining a consistent experience for the developer.