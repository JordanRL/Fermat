You can also directly instantiate the Value classes if you wish, and sometimes it is desirable to do so.

!!! see-also "Mutable vs. Immutable"
    This section details the concrete implementations that are available for the various values in Fermat. Many of these implementations have a `Mutable` and an `Immutable` version.
    
    For more detailed information about the differences between these, and the situations that each might be useful in, please see the page on [mutability](mutability.md).

# Values of Decimal

These classes extend the `Decimal` abstract class, which comes with the following interfaces, traits, and constructor.

### Interfaces

- `NumberInterface`
- `BaseConversionInterface`
- `SimpleNumberInterface`
- `DecimalInterface`

### Traits

- `ArithmeticSimpleTrait`
    - `ArithmeticSelectionTrait`
    - `ArithmeticPrecisionTrait`
    - `ArithmeticNativeTrait`
- `ComparisonTrait`
- `IntegerMathTrait`
- `TrigonometryTrait`
- `InverseTrigonometryTrait`
- `LogTrait`
- `PrecisionTrait`

###### __construct(int|float|numeric $value, $precision = 10, $base = 10)

The constructor will take an `integer`, a `float`, or any `numeric string` as its input value. The precision and base must be given as integers, and can be omitted where they will take their default values of 10. This means that by default instances of `Decimal` will be in base-10 and calculate 10 digits of precision for all operations.

!!! potential-bugs "You Might Not Expect"
    If an instance of `Decimal` is provided, it will be treated as a string and will construct correctly. However, it will not inherit the `$precision` or `$base` settings for the instance provided as a `$value`. If the instance provided is in a base other than 10, the `$base` provided to the constructor should match that value, or you will eventually get exceptions and potentially PHP fatals.
    
    Providing an instance of `Fraction` or `ComplexNumber` will appear to build the new instance correctly, but will result in a PHP fatal error on calls to any methods on the new instance.

## ImmutableNumber

A number which can be represented as a decimal and has a maximum precision of 2^63 digits. This value is immutable, and all methods called on instances of the class will return a new instance of the same class while leaving the existing instance at its original value.

## MutableNumber

A number which can be represented as a decimal and has a maximum precision of 2^63 digits. This value is mutable, and all methods called on instances of the class will return the same instance after modification, while the previous value will be lost.

# Values of Fraction

Used to represent numbers in a fraction format. The '/' character is used in string representations to denote the fraction bar, and is used to create instances from strings. In these cases, it is assumed that the numerator is to the left of the fraction bar, and the denominator is the right.

The numerator and denominator must also be whole numbers. If an operator results in non-whole values for either the numerator or denominator, the `Fraction` is converted to a `Decimal` using the `asDecimal()` method on the `FractionInterface`.

These classes extend the `Fraction` abstract class, which comes with the following interfaces, traits, and constructor.

### Interfaces

- `NumberInterface`
- `BaseConversionInterface`
- `SimpleNumberInterface`
- `FractionInterface`

### Traits

- `ArithmeticSimpleTrait`
    - `ArithmeticSelectionTrait`
    - `ArithmeticPrecisionTrait`
    - `ArithmeticNativeTrait`
- `ComparisonTrait`

###### __construct(int|float|numeric|DecimalInterface $numerator, int|float|numeric|DecimalInterface $denominator, $base = 10)

The constructor will take an `integer`, a `float`, any `numeric string`, or an instance of `DecimalInterface` as its input value. The base must be given as integers, and can be omitted where it will take the default value of 10. This means that by default instances of `Fraction` will be in base-10.

!!! potential-bugs "Rounding"
    In the constructor, non-integer values for the numerator or denominator are automatically rounded to the nearest integer using the "half up" method.

!!! potential-bugs "You Might Not Expect"
    If an instance of `Decimal` is provided, it will be coerced into an `ImmutableDecimal`. This will leave the original instance unaffected by operations performed on the `Fraction`, even if an instance of `MutableDecimal` was originally provided.

## ImmutableFraction

A number which can be represented as a fraction and has a maximum precision of 2^63 digits. This value is immutable, and all methods called on instances of the class will return a new instance of the same class while leaving the existing instance at its original value.

## MutableNumber

A number which can be represented as a decimal and has a maximum precision of 2^63 digits. This value is mutable, and all methods called on instances of the class will return the same instance after modification, while the previous value will be lost.

# Values of ComplexNumber