# Concepts

Fermat has some vocabulary and concepts that are unique to this library, and they are documented here.

## Providers

A provider in Fermat is a static class which provides a specific functionality to the entire library. It makes this interface available using static methods and wherever possible is permissive about the values it accepts as arguments. What is meant by that is you can, in most cases, pass an implementation of NumberInterface, a numeric string, an int, or a float. Please note that there are exceptions to this general principle and consult the method documentation.

The current list of providers, documented in more detail in the section for Providers, is:

- `ArithmeticProvider`
- `ConstantProvider`
- `PolyfillProvider`
- `SequenceProvider`
- `SeriesProvider`
- `StatsProvider`
- `TrigonometryProvider`

!!! see-also "See Also"
    The Providers included in Fermat are documented in more detail under "Reference".

## Types

A type in Fermat is an implementation of a class of number or math concept. These are (with the exception of `Tuple` and `NumberCollection`) abstract classes that are meant to be extended into classes which can be instantiated. This is mostly to provide both mutable and immutable versions of each type. A `Tuple` is meant to be inherently immutable, while a `NumberCollection` is mean to inherently mutable.

The current list of types is:

- `ComplexNumber`
- `Coordinate`
- `Decimal`
- `Expression`
- `Fraction`
- `Matrix`
- `NumberCollection`
- `Tuple`

!!! see-also "See Also"
    The Types included in Fermat are documented in more detail under "Types & Values".

## Values

A value in Fermat is a usable implementation that can be directly worked with to perform math operations of some kind. These represent concrete concepts or types of values that have specific intended behavior and usage.

The current list of values is:

- `Algebra`
    - `PolynomialFunction`
- `Geometry`
    - `CoordinateSystems`
        - `CartesianCoordinate`
        - `CylindricalCoordinate`
        - `PolarCoordinate`
        - `SphericalCoordinate`
- `ImmutableComplexNumber`
- `ImmutableDecimal`
- `ImmutableFraction`
- `ImmutableMatrix`
- `MutableComplexNumber`
- `MutableDecimal`
- `MutableFraction`
- `MutableMatrix`

!!! see-also "See Also"
    The Values included in Fermat are documented in more detail under "Types & Values".