# Concepts

Fermat has some vocabulary and concepts that are unique to this library, and they are documented here.

## Providers

A provider in Fermat is a static class which provides a specific functionality to the entire library. It makes this interface available using static methods and wherever possible is permissive about the values it accepts as arguments. What is meant by that is you can, in most cases, pass an implementation of NumberInterface, a numeric string, an int, or a float. Please note that there are exceptions to this general principle and consult the method documentation.

The current list of providers, documented in more detail in the section for Providers, is:

- [ArithmeticProvider](../roster/latest/Fermat Core/Provider/ArithmeticProvider.md)
- [CalculationModeProvider](../roster/latest/Fermat Core/Provider/CalculationModeProvider.md)
- [ConstantProvider](../roster/latest/Fermat Core/Provider/ConstantProvider.md)
- [RandomProvider](../roster/latest/Fermat Core/Provider/RandomProvider.md)
- [RoundingProvider](../roster/latest/Fermat Core/Provider/RoundingProvider.md)
- [SequenceProvider](../roster/latest/Fermat Core/Provider/SequenceProvider.md)
- [SeriesProvider](../roster/latest/Fermat Core/Provider/SeriesProvider.md)
- [TrigonometryProvider](../roster/latest/Fermat Core/Provider/TrigonometryProvider.md)

## Types

A type in Fermat is an implementation of a class of number or math concept. These are (with the exception of `Tuple` and `NumberCollection`) abstract classes that are meant to be extended into classes which can be instantiated. This is mostly to provide both mutable and immutable versions of each type. A `Tuple` is meant to be inherently immutable, while a `NumberCollection` is mean to inherently mutable.

The current list of types is:

- [Decimal](../roster/latest/Fermat Core/Types/Decimal.md)
- [Fraction](../roster/latest/Fermat Core/Types/Fraction.md)
- [NumberCollection](../roster/latest/Fermat Core/Types/NumberCollection.md)
- [Tuple](../roster/latest/Fermat Core/Types/Tuple.md)

## Values

A value in Fermat is a usable implementation that can be directly worked with to perform math operations of some kind. These represent concrete concepts or types of values that have specific intended behavior and usage.

The current list of values is:

- [ImmutableDecimal](../roster/latest/Fermat Core/Values/ImmutableDecimal.md)
- [ImmutableFraction](../roster/latest/Fermat Core/Values/ImmutableFraction.md)
- [MutableDecimal](../roster/latest/Fermat Core/Values/MutableDecimal.md)
- [MutableFraction](../roster/latest/Fermat Core/Values/MutableFraction.md)
    
## Scale

The basis of this library is being able to provide answers at any requested scale. Scale, as used in this library, is the number of digits after the decimal point which are returned. This is in contrast to *significant figures* or *precision*, which represent the numbers of digits returned after the decimal point after trimming all the leading zeros.

There are two main reasons for providing scale as the main way of controlling how precise the answer is:

1. It ensures that the string size of values with the same scale are comparable.
2. It is far easier to implement some of the converging series calculations within this library, such as those for trigonometry functions, if scale is used instead of precision.

In actual fact, significant figures have much less meaning in the context of a Taylor series or MacLauren series. If precision was used, the library would have to make more assumptions about the intent of calling code, and those assumptions would be less transparent.

!!! caution "Scale Does Not Increase With New Operations"
    Unlike significant figures, the scale returned does not change as the number of decimal digits are multiplied or divided.
    
    This means that multiplying two numbers that each have 10 digits after the decimal will also return a number with 10 digits after the decimal. This can be fixed by setting the scale of the argument value to the sum of the two scales.
    
!!! caution "Scale Is Only Applied To The Base-10 Form"
    Scale is tracked and managed in base-10. This means that if a number has a base smaller than 10, it will return more digits than the scale would suggest after base conversion, while a number with a base larger than 10 will return fewer digits after the base conversion.