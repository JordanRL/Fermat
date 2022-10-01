Fermat provides factory classes to make it easier to get instances of the various Value classes. The available factories are:

- [Collections](../roster/latest/Fermat Core/Collections.md)
- [Numbers](../roster/latest/Fermat Core/Numbers.md)

All factories are classes that have only static methods and constants. 

# The Collections Factory Class

The [Collections](../roster/latest/Fermat Core/Collections.md) factory class currently has no methods or constants, and exists as a placeholder.

# The Numbers Factory Class

The [Numbers](../roster/latest/Fermat Core/Numbers.md) factory class provides a way to use the Value classes which implement the [SimpleNumberInterface](../roster/latest/Fermat Core/Types/Base/Interfaces/Numbers/SimpleNumberInterface.md) in Fermat without being as specific as those classes may require. Consider the following code:

### Available Factory Methods

The following factory methods are available on the `Numbers` class. For full signature descriptions, please see the Source Reference section for the [Numbers](../roster/latest/Fermat Core/Numbers.md) class.

!!! signature "Numbers::make()"

This factory method returns an instance of `DecimalInterface` or `FractionInterface`, depending on the `$type` given and the `$value` provided.

!!! tip "Type Can Be An Instance"
    Instead of providing a fully qualified class name for `$type`, you can provide an instance of a supported object. The `make()` function will attempt to force the `$value` into that type.

!!! fail "Type Must Be A Supported FQCN or Class"
    If `$type` is the fully qualified class name or instance of an object other than `ImmutableDecimal`, `MutableDecimal`, `ImmutableFraction`, or `MutableFraction`, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 

!!! signature "Numbers::makeFromBase10()"

This factory method will create a base-10 instance of `$type` using the provided `$value`, then convert that value in the `$base` provided. This allows you to provide a `$value` in base-10, but get an instance in a different base.

!!! signature "Numbers::makeOrDont()"

This factory method will coerce the given `$value` into the requested `$type`. Unlike using [direct instantiation](direct-instantiation.md), this factory will perform all the correct conversions on the various possible values necessary to ensure a valid instance is constructed.

If the provided `$value` already matches the requested `$type`, then it is returned without modification. This makes the `makeOrDont()` factory ideal for accepting any possible valid constructor value as an input while also guaranteeing that your implementation is working with a particular value.

This is how the math operations such as `add($num)` are able to accept virtually any input directly.

!!! note "Arrays of Values"
    An array can be provided as the `$value` argument to this function. If it is, then a recursive call on `Numbers::makeOrDont()` is made. This will be done at any level of nested arrays.

!!! tip "Low Cost Function Call"
    This factory method returns the provided value after only making a call to `is_object()` and a single use of `instanceof` if the provided `$value` matches the requested `$type`.
    
    In general, it is written to build the requested `$type` in the most efficient way possible given the provided inputs.
    
    This makes calls to this factory method very low cost from both a memory and computation perspective if you need the value to be a guaranteed instance of a particular class.
    
!!! fail "Mixed Argument Limitations"
    The `$values` argument is listed in this documentation as `mixed`. In fact, the valid input types are:
    
    - An `integer`
    - A `float`
    - A `string` that contains only a single number in base 10
    - A `string` that contains only a single number in base 10 with the `i` character at the end, denoting an imaginary value
    - An `object` that implements `NumberInterface`
    
    If the provided `$value` matches none of these, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 

!!! signature "Numbers::makeFractionFromString()"

This factory method will take a string as its input and provide an instance of either `ImmutableFraction` or `MutableFraction` depending on the value given for `$type`.

!!! fail "Type Must Be A Supported FQCN"
    If `$type` is the fully qualified class name of an object other than `ImmutableFraction` or `MutableFraction`, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 
    
!!! fail "Value Must Contain at Most One Fraction Bar '/'"
    If `$value` contains more than one fraction bar, which is assumed to be represented by the character `/`, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 

!!! signature "Numbers::makePi()"

If no `$scale` is given, then the value is returned with a scale of 100. If a scale of 100 or less is requested, then the instance is constructed from the `Numbers::PI` constant. If a scale of greater than 100 is requested, then a call is made to `ConstantProvider::makePi()` which computes digits of pi using the most efficient computational method currently available.

!!! fail "Scale Must Be Positive"
    If a scale of less than 1 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 

!!! signature "Numbers::makeTau()"

If no `$scale` is given, then the value is returned with a scale of 100. If a scale of 100 or less is requested, then the instance is constructed from the `Numbers::TAU` constant. If a scale of greater than 100 is requested, then a call is made to `Numbers::makePi()` which uses the methods described above, after which the result is multiplied by 2.

!!! fail "Scale Must Be Positive"
    If a scale of less than 1 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown.

!!! signature "Numbers::make2Pi()"

This factory method is an alias for `Numbers::makeTau()`.

!!! fail "Scale Must Be Positive"
    If a scale of less than 1 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown.

!!! signature "Numbers::makeE()"

If no `$scale` is given, then the value is returned with a scale of 100. If a scale of 100 or less is requested, then the instance is constructed from the `Numbers::E` constant. If a scale of greater than 100 is requested, then a call is made to `ConstantProvider::makeE()` which uses a fast converging series to calculate digits of e.

!!! fail "Scale Must Be Positive"
    If a scale of less than 1 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown.

!!! signature "Numbers::makeGoldenRatio()"

If no `$scale` is given, then the value is returned with a scale of 100. If a scale of 100 or less is requested, then the instance is constructed from the `Numbers::GOLDEN_RATION` constant.

!!! fail "Scale Must Be 1-100"
    If a scale of less than 1 or greater than 100 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown.

!!! signature "Numbers::makeNaturalLog10()"

If no `$scale` is given, then the value is returned with a scale of 100. If a scale of 100 or less is requested, then the instance is constructed from the `Numbers::LN_10` constant. If a scale of greater than 100 is requested, then an exception is thrown.

!!! fail "Scale Must Be 1-100"
    If a scale of less than 1 or greater than 100 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown.

!!! signature "Numbers::makeOne()"

If `$scale` is null, then the instance returned will have a scale of 100.

!!! signature "Numbers::makeZero()"

If `$scale` is null, then the instance returned will have a scale of 100.