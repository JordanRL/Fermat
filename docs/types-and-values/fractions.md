Fractions represent numbers which have a rational representation. They can be equivalent to either a `float` or an `integer`, and their `$scale` setting is determined by the scale setting of the `Decimal` instances that make up their numerator and denominator.

# Abstract Class: Fraction

The following interfaces and traits are available on classes which extend `Fraction`

## Interfaces

--8<-- "has-interface/hashable.md"

--8<-- "has-interface/base-conversion.md"

--8<-- "has-interface/number.md"

--8<-- "has-interface/simple-number.md"

--8<-- "has-interface/fraction.md"

## Traits

--8<-- "uses-trait/arithmeticsimple.md"

--8<-- "uses-trait/comparison.md"

## Abstract Methods

The following abstract methods must be implemented on classes which extend `Fraction`

!!! signature "abstract protected function setValue(ImmutableDecimal $numerator, ImmutableDecimal $denominator)"
    $numerator
    :   The new numerator value, given as an instance of **ImmutableDecimal**
    
    $denominator
    :   The new denominator value, given as an instance of **ImmutableDecimal**
    
    return
    :   An instance of the current class with the given arguments set as properties

This method controls the behavior of setting the `$value` property, and its different implementations represent the main difference between mutable and immutable versions. For classes that extend `Fraction`, the arguments are limited to the concrete class `ImmutableDecimal`.

# Available Value Objects

The following implementations of `Fraction` are included with Fermat. These classes may be provided for any argument which accepts a `FractionInterface` as a value.

## ImmutableFraction

A number which can be represented as a fraction. This value is immutable, and all methods called on instances of the class will return a new instance of the same class while leaving the existing instance at its original value.

## MutableFraction

A number which can be represented as a fraction. This value is mutable, and all methods called on instances of the class will return the same instance after modification, while the previous value will be lost.