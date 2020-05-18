# Limitations of Native Inequality Operators

For values that cannot be represented as an `integer` or `float` accurately, using the native inequality operators will result in erroneous results. In some cases, this might also result in underflow and overflow of the native types.

Because of this, all comparisons, including equality comparisons which would normally be `==` or `===` in PHP, should be performed using the comparison methods provided on all objects which extend the `Number` abstract class.

!!! warning "Complex Number Limitations"
    The `ComplexNumber` abstract class, and all of its child classes, only implement the `isEqual()` method. This is because inequality is poorly defined for complex numbers. There is no sensible and consistent way to evaluate the statement `(2+2i) >= (1+1i)`, even though one might expect this to return true.
    
    The issue is that the inequality methods must return a `boolean`, and even in the cases where it might be argued that either the `true` case or `false` case is well-defined, the opposite case is always ambiguous under any definition of inequality for complex numbers.
    
    This makes the return values of such statements meaningless in the context of complex numbers.
    
!!! potential-bugs "You Might Not Expect"
    The `isEqual()` method on the `ComplexNumber` class and all of its children checks first whether or not the compared value is also a complex number. This means that the following comparison will return `false`, even though one might expect it to return `true`:
    
    `2+0i == 2`
    
    This ambiguity is unlikely to occur in normal usage of the Fermat library, since all math operations which can lead to a zero value for either the real part or the imaginary part will return an instance of `ImmutableDecimal` instead. The only way to see this behavior is to manually instantiate a complex number with a zero value for one of its parts.
    
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
    As part of integration with the `ext-ds` extension, this method has been implemented to satisfy the `Hashable` interface. This is mainly so that objects which are instances of the `Number` abstract class can be used as array keys.

# Inequality

These methods are only available on numbers that implement the `SimpleNumberInterface`. They are safe to use between different types of classes that implement the `SimpleNumberInterface`, and will perform the necessary conversions to return an answer.

###### isGreaterThan(mixed $value); isLessThan(mixed $value)

###### isGreaterThanOrEqualTo(mixed $value)

###### isLessThanOrEqualTo(mixed $value)

Any `$value` which is a valid input for `Numbers::makeOrDont()` can be provided to these methods. They are analogous to the corresponding comparison operators in PHP, but are safe to use with the arbitrary scale values found in this library.

# Sorting Comparison <=>

The spaceship operator `<=>`, which returns `1`, `0`, or `-1` to provide sorting order information, would result in many of the same issues described for the `isEqual()` method. This is handled internally by the BCMath extension.

###### compare(mixed $value)

Any `$value` which is a valid input for `Numbers::makeOrDont()` can be provided to this method. Returns `1` if the current object is greater than `$value`, `0` if they are equal, and `-1` if the current object is less than `$value`.

!!! note "Internally Referenced By Other Comparisons"
    All other comparison methods reference this method. This ensures that any return values of all possible comparison methods will remain consistent with each other no matter what implementation of `compare()` is used.
    
    The only exception is `ComplexNumber`, which implements `isEqual()` but not `compare()`.