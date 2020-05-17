Fractions represent numbers which have a rational representation. They can be equivalent to either a `float` or an `integer`, and their `$precision` setting is determined by the precision setting of the `Decimal` instances that make up their numerator and denominator.

# Abstract Class: Fraction

The following interfaces and traits are available on classes which extend `Fraction`

## Interfaces

###### Hashable

--8<-- "has-interface/hashable.md"

###### BaseConversionInterface

--8<-- "has-interface/base-comparison.md"

###### NumberInterface

--8<-- "has-interface/number.md"

###### SimpleNumberInterface

--8<-- "has-interface/simple-number.md"

###### FractionInterface

The `FractionInterface` extends `SimpleNumberInterface` and adds the methods that are common to all fraction values. This includes `simplify()`, accessors for the numerator and denominator, and the `asDecimal()` method that returns the `Fraction` as an instance of `DecimalInterface`.

!!! note "ImmutableDecimals Are Returned From asDecimal()"
    While the interface only defines the `DecimalInterface` as a return value, the concrete classes returned by all included implementations are instances of `ImmutableDecimal`.

While some other functions can be done on fractions in pure mathematics, such as trigonometry functions, in practice computers are not well-equipped to handle the algorithms for them without actually performing the division implied by the fraction. Thus, to use these types of functions an explicit call to `asDecimal()` must first be made on classes that implement `Fraction`.

!!! see-also "See Also"
    The page for [Types & Values > Decimals](decimals.md) contains more information on the usage of `Decimal` values.

## Traits

###### ArithmeticSimpleTrait

--8<-- "uses-trait/arithmeticsimple.md"

###### ComparisonTrait

--8<-- "uses-trait/comparison.md"

## Abstract Methods

The following abstract methods must be implemented on classes which extend `Fraction`

###### abstract protected function setValue(ImmutableDecimal $numerator, ImmutableDecimal $denominator)

This method controls the behavior of setting the `$value` property, and its different implementations represent the main difference between mutable and immutable versions. For classes that extend `Fraction`, the arguments are limited to the concrete class `ImmutableDecimal`.

# Available Value Objects

The following implementations of `Fraction` are included with Fermat. These classes may be provided for any argument which accepts a `FractionInterface` as a value.

## ImmutableFraction

A number which can be represented as a fraction. This value is immutable, and all methods called on instances of the class will return a new instance of the same class while leaving the existing instance at its original value.

## MutableFraction

A number which can be represented as a fraction. This value is mutable, and all methods called on instances of the class will return the same instance after modification, while the previous value will be lost.