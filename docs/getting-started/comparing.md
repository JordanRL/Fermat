# Limitations of Native Inequality Operators

For values that cannot be represented as an `integer` or `float` accurately, using the native inequality operators will result in erroneous results. In some cases, this might also result in underflow and overflow of the native types.

Because of this, all comparisons, including equality comparisons which would normally be `==` or `===` in PHP, should be performed using the comparison methods provided on all objects which extend the [Number](../roster/latest/Fermat Core/Types/Base/Number.md) abstract class.
    
# Equality

There are two types of equality that can be compared using this library: equality of value, and equality of representation. In virtually all cases equality of value is what is intended, and this is accomplished with the `isEqual()` method.

Equality of representation is accomplished with the `equals()` method, and only returns true if the value being compared has the same value *and* is an instance of the same class.

###### isEqual(mixed $value)

Any `$value` which is a valid input for `Numbers::makeOrDont()` can be provided here. Returns `true` if the values are the same, `false` otherwise.

!!! note "Scale Only Affects the Result for Significant Figures"
    Two objects with different scale settings will only return `false` if one of them has significant figures beyond the scale limit of the other.
    
    For instance, `Numbers::makeOne(5)` and `Numbers::makeOne(10)` will return true when compared using this method, even though internally they would be represented by `1.00000` and `1.0000000000`.

###### equals(object $value)

Returns `true` if the `$value` is an instance of the same class *and* it has the same value, `false` otherwise.

!!! note "Implemented As Part of the Hashable Interface"
    As part of integration with the `ext-ds` extension, this method has been implemented to satisfy the `Hashable` interface. This is mainly so that objects which are instances of the [Number](../roster/latest/Fermat Core/Types/Base/Number.md) abstract class can be used as array keys.

# Inequality

These methods are only available on numbers that implement the [SimpleNumberInterface](../roster/latest/Fermat Core/Types/Base/Interfaces/Numbers/SimpleNumberInterface.md). They are safe to use between different types of classes that implement the [SimpleNumberInterface](../roster/latest/Fermat Core/Types/Base/Interfaces/Numbers/SimpleNumberInterface.md), and will perform the necessary conversions to return an answer.

!!! signature "isGreaterThan(mixed $value)"

!!! signature "isLessThan(mixed $value)"

!!! signature "isGreaterThanOrEqualTo(mixed $value)"

!!! signature "isLessThanOrEqualTo(mixed $value)"

Any `$value` which is a valid input for `Numbers::makeOrDont()` can be provided to these methods. They are analogous to the corresponding comparison operators in PHP, but are safe to use with the arbitrary scale values found in this library.

# Sorting Comparison <=>

The spaceship operator `<=>`, which returns `1`, `0`, or `-1` to provide sorting order information, would result in many of the same issues described for the `isEqual()` method. This is handled internally by the BCMath extension.

!!! signature "compare(mixed $value)"

Any `$value` which is a valid input for `Numbers::makeOrDont()` can be provided to this method. Returns `1` if the current object is greater than `$value`, `0` if they are equal, and `-1` if the current object is less than `$value`.

!!! note "Internally Referenced By Other Comparisons"
    All other comparison methods reference this method. This ensures that any return values of all possible comparison methods will remain consistent with each other no matter what implementation of `compare()` is used.