desc

# Abstract Class: Decimal

The following interfaces and traits are available on classes which extend `Decimal`

## Interfaces

###### Hashable



###### BaseConversionInterface



###### NumberInterface



###### SimpleNumberInterface



###### DecimalInterface



## Traits

###### ArithmeticSimpleTrait

Imports

- `ArithmeticSelectionTrait`
- `ArithmeticScaleTrait`
- `ArithmeticNativeTrait`

The `ArithmeticSimpleTrait` provides the implementations for all arithmetic functions that exist on values that implement the `SimpleNumberInterface`. The additional imported traits within this trait provide the various calculation modes that are used internally depending on the mode of object executing the method call.

!!! note "Accepts Complex Numbers as Arguments"
    While the `ArithmeticSimpleTrait` can accept implementations of `ComplexNumber` as arguments, it cannot be used in implementations of `ComplexNumber`.
    
!!! see-also "See Also"
    More detailed information on this trait is available on the [Arithmetic > Simple Numbers](../arithmetic/simple.md) page.

###### ComparisonTrait



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