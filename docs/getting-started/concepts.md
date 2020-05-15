# Concepts

Fermat has some vocabulary and concepts that are unique to this library, and they are documented here.

## Providers

A provider in Fermat is a static class which provides a specific functionality to the entire library. It makes this interface available using static methods and wherever possible is permissive about the values it accepts as arguments. What is meant by that is you can, in most cases, pass an implementation of NumberInterface, a numeric string, an int, or a float. Please note that there are exceptions to this general principle and consult the method documentation.

The current list of providers, documented in more detail in the section for Providers, is:

- ArithmeticProvider
- ConstantProvider
- PolyfillProvider
- SequenceProvider
- SeriesProvider
- StatsProvider
- TrigonometryProvider

## Types

A type in Fermat is an implementation of a class of number or math concept. These are (with the exception of Tuple and NumberCollection) abstract classes that are meant to be extended into classes which can be instantiated. This is mostly to provide both mutable and immutable versions of each type. Tuples are not treated in this way as a Tuple is inherently immutable.

The current list of types is:

- ComplexNumber
- Coordinate
- Decimal
- Expression
- Fraction
- Matrix
- NumberCollection
- Tuple

## Values

A value in Fermat is a usable implementation that can be directly worked with to perform math operations of some kind. These represent concrete concepts or types of values that have specific intended behavior and usage.

The current list of values is:

- Algebra
  - PolynomialFunction
- Geometry
  - CoordinateSystems
    - CartesianCoordinate
    - CylindricalCoordinate
    - PolarCoordinate
    - SphericalCoordinate
- ImmutableComplexNumber
- ImmutableDecimal
- ImmutableFraction
- ImmutableMatrix
- MutableComplexNumber
- MutableDecimal
- MutableFraction
- MutableMatrix

# Using Values

There are two main ways of using this library: through direct instantiation and through the `Samsara\Fermat\Numbers` factory class.



## Direct Instantiation

You can also directly instantiate the Value classes if you wish, and sometimes it is desirable to do so.

### ImmutableNumber and MutableNumber

These classes extend the `Samsara\Fermat\Types\Decimal` abstract class, and their constructors have the following signature:

`__construct(int|float|numeric $value, $precision = 10, $base = 10)`

**These classes implement the `SimpleNumberInterface` and `DecimalInterface`**

### ImmutableFraction and MutableFraction

These classes extend the `Samsara\Fermat\Types\Fraction` abstract class, and their constructors have the following signature:

`__construct(int|float|numeric|DecimalInterface $numerator, int|float|numeric|DecimalInterface $denominator, $base = 10)`

Note that if either $numerator or $denominator are not whole numbers, they will be rounded. Both the numerator and denominator are stored internally by `Fraction` as instances of `ImmutableNumber`, and they will be coerced into this Value.

**These classes implement the `SimpleNumberInterface` and `FractionInterface`**

### CartesianCoordinate

This class extends the `Samsara\Fermat\Types\Coordinate` class, and its constructor has the following signature:

`__construct(array $data)`

In this case, the array is assumed to be one-dimensional, the keys are assumed to represent the names of various axes, and the values are assumed to represent the value of the coordinate on that axis. The values in the array have the same rules as $numerator and $denominator for the `Fraction` class (i.e. they can be of type int|float|numeric|DecimalInterface) and they will be coerced into the `ImmutableNumber` Value before being stored internally by the Tuple.

**This class implements the `CoordinateInterface`**