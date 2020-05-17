Decimals represent numbers which have a decimal representation. They can be equivalent to either a `float` or an `integer`, and their `$scale` setting determines how many places after the decimal are calculated for operations which would affect them.

# Abstract Class: Decimal

The following interfaces and traits are available on classes which extend `Decimal`

## Interfaces

###### Hashable

--8<-- "has-interface/hashable.md"

###### BaseConversionInterface

--8<-- "has-interface/base-conversion.md"

###### NumberInterface

--8<-- "has-interface/number.md"

###### SimpleNumberInterface

--8<-- "has-interface/simple-number.md"

###### DecimalInterface

The `DecimalInterface` extends `SimpleNumberInterface` and adds the methods that are common to all decimal values. This includes trigonometric operations, integer operations such as `factorial()` or `isPrime()`, integer and float comparisons and conversions, rounding and truncating functions, and log functions.

While some of these can be done on fractions in pure mathematics, such as trigonometry functions, in practice computers are not well-equipped to handle the algorithms for them without actually performing the division implied by the fraction. Thus, to use these types of functions an explicit call to `asDecimal()` must first be made on classes that implement `Fraction`.

!!! see-also "See Also"
    The page for [Types & Values > Fractions](fractions.md) contains more information on the limitations of fraction representations within Fermat.

## Traits

###### ArithmeticSimpleTrait

--8<-- "uses-trait/arithmeticsimple.md"

###### ComparisonTrait

--8<-- "uses-trait/comparison.md"

###### IntegerMathTrait

The `IntegerMathTrait` provides the implementations of all integer math methods for any class which implements the `DecimalInterface`. This includes methods such as `factorial()` and `isPrime()`.

###### TrigonometryTrait

The `TrigonometryTrait` provides the implementations for all basic trigonometry and hyperbolic trigonometry functions.

###### InverseTrigonometryTrait

The `InverseTrigonometryTrait` provides the implementations for all inverse trigonometric functions, sometimes referred to as "arc functions". These are sometimes abbreviated in programming languages as `a`, such as `atan` which is equivalent to `arctan` which is equivalent to `inverseTangent`.

###### LogTrait

The `LogTrait` provides the implementations for the `log`, `ln`, and `exp` functions.

###### ScaleTrait

The `ScaleTrait` provides the implementations for all rounding and truncating functions for classes which implement `DecimalInterface`.

## Abstract Methods

The following abstract methods must be implemented on classes which extend `Decimal`

###### abstract protected function setValue(string $value, int $scale = null, int $base = 10)

This method controls the behavior of setting the `$value` property, and its different implementations represent the main difference between mutable and immutable versions.

###### abstract public function continuousModulo($mod): DecimalInterface

This method comes from `DecimalInterface` and must be implemented by the extending class. This is because it might be undesirable for this method to be mutable, even for a mutable class. It takes a decimal value as its input, and returns the remainder of a division operation, even if the number provided is not an integer.

# Available Value Objects

The following implementations of `Decimal` are included with Fermat. These classes may be provided for any argument which accepts a `DecimalInterface` as a value.

## ImmutableDecimal

A number which can be represented as a decimal and has a maximum scale of $`2^{63}`$ digits. This value is immutable, and all methods called on instances of the class will return a new instance of the same class while leaving the existing instance at its original value.

## MutableDecimal

A number which can be represented as a decimal and has a maximum scale of $`2^{63}`$ digits. This value is mutable, and all methods called on instances of the class will return the same instance after modification, while the previous value will be lost.