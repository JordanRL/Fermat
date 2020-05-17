Decimals represent numbers which have a decimal representation. They can be equivalent to either a `float` or an `integer`, and their `$precision` setting determines how many places after the decimal are calculated for operations which would affect them.

# Abstract Class: Decimal

The following interfaces and traits are available on classes which extend `Decimal`

## Interfaces

###### Hashable

The `Hashable` interface is part of `ext-ds`, and implementing it enables a class to be used as an array key in the various types provided by `ext-ds`.

###### BaseConversionInterface

`BaseConversionInterface` enables two methods: `convertToBase()` and `getBase()`, which do exactly what someone would expect them to.

!!! note "Base Conversion is Done Just-In-Time"
    Internally, the values of objects which implement the `BaseConversionInterface` always store the number in base-10, since this is the only base that arithmetic can actually be performed in by any of the associated extensions.
    
    Base conversion happens when a call is made to `getValue()`. Even on objects which have a base other than base-10, this can be avoided by calls to `getAsBaseTenNumber()` and `getAsBaseTenRealNumber()`.

###### NumberInterface

`NumberInterface` contains the base arithmetic methods that are a component of all numbers in mathematics. This includes the basics of addition, subtraction, multiplication, and division, as well as pow and square root.

It also provides the `isEqual()` method, to enable equality comparison, as well as `getPrecision()`. Some classes which implement the `NumberInterface` don't actually accept precision as an argument, but instead contain objects that do. `Fraction` is an example of such a class, as both its numerator and denominator are instances of `ImmutableDecimal`.

In addition, the `is` and `as` methods for `Real`, `Imaginary`, and `Complex` are provided by this interface. 

###### SimpleNumberInterface

The `SimpleNumberInterface` extends `NumberInterface` and adds the methods that are common to all non-complex numbers. This includes things like being positive or negative, inequality comparison, and getting the value as a base-10 real number.

###### DecimalInterface

The `DecimalInterface` extends `SimpleNumberInterface` and adds the methods that are common to all decimal values. This includes trigonometric operations, integer operations such as `factorial()` or `isPrime()`, integer and float comparisons and conversions, rounding and truncating functions, and log functions.

While some of these can be done on fractions in pure mathematics, such as trigonometry functions, in practice computers are not well-equipped to handle the algorithms for them without actually performing the division implied by the fraction. Thus, to use these types of functions an explicit call to `asDecimal()` must first be made on classes that implement `Fraction`.

!!! see-also "See Also"
    The page for [Types & Values > Fractions](fractions.md) contains more information on the limitations of fraction representations within Fermat.

## Traits

###### ArithmeticSimpleTrait

--8<--
uses-arithmeticsimpletrait.md
--8<--

###### ComparisonTrait

The `ComparisonTrait` provides the implementations of all comparison methods for any class which implements the `SimpleNumberInterface`.

###### IntegerMathTrait

The `IntegerMathTrait` provides the implementations of all integer math methods for any class which implements the `DecimalInterface`. This includes methods such as `factorial()` and `isPrime()`.

###### TrigonometryTrait

The `TrigonometryTrait` provides the implementations for all basic trigonometry and hyperbolic trigonometry functions.

###### InverseTrigonometryTrait

The `InverseTrigonometryTrait` provides the implementations for all inverse trigonometric functions, sometimes referred to as "arc functions". These are sometimes abbreviated in programming languages as `a`, such as `atan` which is equivalent to `arctan` which is equivalent to `inverseTangent`.

###### LogTrait

The `LogTrait` provides the implementations for the `log`, `ln`, and `exp` functions.

###### PrecisionTrait

The `PrecisionTrait` provides the implementations for all rounding and truncating functions for classes which implement `DecimalInterface`.

## Abstract Methods

The following abstract methods must be implemented on classes which extend `Decimal`

###### abstract protected function setValue(string $value, int $precision = null, int $base = 10)

This method controls the behavior of setting the `$value` property, and its different implementations represent the main difference between mutable and immutable versions.

###### abstract public function continuousModulo($mod): DecimalInterface

This method comes from `DecimalInterface` and must be implemented by the extending class. This is because it might be undesirable for this method to be mutable, even for a mutable class. It takes a decimal value as its input, and returns the remainder of a division operation, even if the number provided is not an integer.

# Available Value Objects

The following implementations of `Decimal` are included with Fermat. These classes may be provided for any argument which accepts a `DecimalInterface` as a value.

## ImmutableDecimal

A number which can be represented as a decimal and has a maximum precision of $`2^{63}`$ digits. This value is immutable, and all methods called on instances of the class will return a new instance of the same class while leaving the existing instance at its original value.

## MutableDecimal

A number which can be represented as a decimal and has a maximum precision of $`2^{63}`$ digits. This value is mutable, and all methods called on instances of the class will return the same instance after modification, while the previous value will be lost.