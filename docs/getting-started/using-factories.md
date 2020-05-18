Fermat provides factory classes to make it easier to get instances of the various Value classes. The available factories are:

- `Samsara\Fermat\Collections`
- `Samsara\Fermat\ComplexNumbers`
- `Samsara\Fermat\Matrices`
- `Samsara\Fermat\Numbers`

All factories are classes that have only static methods and constants. 

# The Collections Factory Class

The `Samsara\Fermat\Collections` factory class currently has no methods or constants, and exists as a placeholder.

# The ComplexNumbers Factory Class

The `Samsara\Fermat\ComplexNumbers` factory class allows you to create instances of the Value classes which implement the `ComplexNumberInterface`.

### Available Constants

The following constants are available on the `ComplexNumbers` class.

###### IMMUTABLE_COMPLEX

The fully qualified class name of the `ImmutableComplexNumber` class.

###### MUTABLE_COMPLEX

The fully qualified class name of the `MutableComplexNumber` class.

### Available Factory Methods

The following factory methods are available on the `ComplexNumbers` class.

###### ComplexNumbers::make(string $type, string|array|NumberCollectionInterface $value)

This factory method returns the requested type of complex number with the given value. If the value is a `string`, it is assumed to be in the format: `REAL+IMAGINARY` or `REAL-IMAGINARY`

!!! note "Note"
    In string format, the input for this factory method **MAY** have a minus sign in front of the real part, but **MUST** have either a plus or minus sign in front of the imaginary part.
    
    The sign is applied to the number that follows and is stored in the `ImmutableDecimal` for that number.

You may also provide either an `array` or a `NumberCollection` that have exactly two values which implement the `SimpleNumberInterface`.

!!! warning "Warning"
    The real part must have a key of `0`, and the imaginary part must have a key of `1` in the given `array` or `NumberCollection`.

# The Matrices Factory Class

The `Samsara\Fermat\Matrices` factory class provides access to several pre-built matrices that may be useful in common situations.

### Available Constants

The following constants are available on the `Matrices` class.

###### IMMUTABLE_MATRIX
 
The fully qualified class name of the `ImmutableMatrix` class.

###### MUTABLE_MATRIX 

The fully qualified class name of the `MutableMatrix` class.

### Available Factory Methods

The following factory methods are available on the `Matrices` class.

###### Matrices::zeroMatrix(string $type, int $rows, int $columns)

This factory method returns an instance of the specified matrix type with the given dimensions where all values in the matrix are the number zero.

!!! example "For Example"
    A zero matrix of two rows and three columns would look like:
    
    ```
    [0 0 0]  
    [0 0 0]
    ```

###### Matrices::onesMatrix(string $type, int $rows, int $columns)

This factory method returns an instance of the specified matrix type with the given dimensions where all values in the matrix are the number one.

!!! example "For Example"
    A ones matrix of two rows and three columns would look like:
    
    ```
    [1 1 1]  
    [1 1 1]
    ```

###### Matrices::identityMatrix(string $type, int $size)

This factory method returns a square matrix where the dimensions match the integer given in `$size`. This matrix is an identity matrix, which is often used in matrix math, where the diagonal consists of ones, and all other values are zero.

!!! example "For Example"
    An identity matrix of size three would look like:
    
    ```
    [1 0 0]  
    [0 1 0]  
    [0 0 1]
    ```

###### Matrices::cofactorMatrix(string $type, int $size)

This factory method returns a square matrix where the dimensions match the integer given in `$size`. The matrix is filled with alternating values of `1` and `-1` in a checkerboard pattern, starting with positive 1 in position [0, 0].

When multiplied by another matrix, this will swap the sign of every other value in the matrix.

!!! example "For Example"
    A cofactor matrix of size three would look like:
    
    ```
    [+ - +]  
    [- + -]  
    [+ - +]
    ```

# The Numbers Factory Class

The `Samsara\Fermat\Numbers` factory class provides a way to use the Value classes which implement the `SimpleNumberInterface` in Fermat without being as specific as those classes may require. Consider the following code:

### Available Constants

The following constants are available on the `Numbers` class.

###### IMMUTABLE
 
The fully qualified class name of the `ImmutableDecimal` class.

###### MUTABLE
 
The fully qualified class name of the `MutableDecimal` class.

###### IMMUTABLE_FRACTION
 
The fully qualified class name of the `ImmutableFraction` class.

###### MUTABLE_FRACTION
 
The fully qualified class name of the `MutableFraction` class.

###### PI

The value of the constant pi ($`\pi`$) pre-computed to 100 decimal places.

###### TAU

The value of the constant tau ($`\tau`$) pre-computed to 100 decimal places. This is equivalent to 2π.

###### E

The value of Euler's constant ($`e`$) pre-computed to 100 decimal places.

###### GOLDEN_RATIO

The value of the Golden Ratio ($`\varphi`$) pre-computed to 100 decimal places.

###### LN_10

The value of the natural logarithm of 10 pre-computed to 100 decimal places.

###### I_POW_I

The value of $`i^i`$ pre-computed to 100 decimal places.

### Available Factory Methods

The following factory methods are available on the `Numbers` class.

###### Numbers::make(string $type, mixed $value, ?int $scale = null, int $base = 10)

This factory method returns an instance of `DecimalInterface` or `FractionInterface`, depending on the `$type` given and the `$value` provided.

!!! tip "Type Can Be An Instance"
    Instead of providing a fully qualified class name for `$type`, you can provide an instance of a supported object. The `make()` function will attempt to force the `$value` into that type.

!!! warning "Type Must Be A Supported FQCN or Class"
    If `$type` is the fully qualified class name or instance of an object other than `ImmutableDecimal`, `MutableDecimal`, `ImmutableFraction`, or `MutableFraction`, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 

###### Numbers::makeFromBase10(string $type, mixed $value, ?int $scale = null, int $base = 10)

This factory method will created a base-10 instance of `$type` using the provided `$value`, then convert that value in the `$base` provided. This allows you to provide a `$value` in base-10, but get an instance in a different base.

###### Numbers::makeOrDont(string $type, mixed $value, ?int $scale = null, int $base = 10)

This factory method will coerce the given `$value` into the requested `$type`. Unlike using [direct instantiation](direct-instantiation.md), this factory will perform all the correct conversions on the various possible values necessary to ensure a valid instance is constructed.

If the provided `$value` already matches the requested `$type`, then it is returned without modification. This makes the `makeOrDont()` factory ideal for accepting any possible valid constructor value as an input while also guaranteeing that your implementation is working with a particular value.

This is how the math operations such as `add($num)` are able to accept virtually any input directly.

!!! note "Arrays of Values"
    An array can be provided as the `$value` argument to this function. If it is, then a recursive call on `Numbers::makeOrDont()` is made. This will be done at any level of nested arrays.

!!! tip "Low Cost Function Call"
    This factory method returns the provided value after only making a call to `is_object()` and a single use of `instanceof` if the provided `$value` matches the requested `$type`.
    
    In general, it is written to build the requested `$type` in the most efficient way possible given the provided inputs.
    
    This makes calls to this factory method very low cost from both a memory and computation perspective if you need the value to be a guaranteed instance of a particular class.
    
!!! warning "Mixed Argument Limitations"
    The `$values` argument is listed in this documentation as `mixed`. In fact, the valid input types are:
    
    - An `integer`
    - A `float`
    - A `string` that contains only a single number in base 10
    - A `string` that contains only a single number in base 10 with the `i` character at the end, denoting an imaginary value
    - An `object` that implements `NumberInterface`
    
    If the provided `$value` matches none of these, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 
    
###### Numbers::makeFractionFromString(string $type, string $value, int $base = 10)

This factory method will take a string as its input and provide an instance of either `ImmutableFraction` or `MutableFraction` depending on the value given for `$type`.

!!! warning "Type Must Be A Supported FQCN"
    If `$type` is the fully qualified class name of an object other than `ImmutableFraction` or `MutableFraction`, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 
    
!!! warning "Value Must Contain at Most One Fraction Bar '/'"
    If `$value` contains more than one fraction bar, which is assumed to be represented by the character `/`, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 

###### Numbers::makePi(?int $scale = null)

This factory method will return the number pi (π) as an instance of `ImmutableNumber` to the requested `$scale`. If no `$scale` is given, then the value is returned with a scale of 100. If a scale of 100 or less is requested, then the instance is constructed from the `Numbers::PI` constant. If a scale of greater than 100 is requested, then a call is made to `ConstantProvider::makePi()` which computes digits of pi using the most efficient computational method currently available.

!!! warning "Scale Must Be Positive"
    If a scale of less than 1 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 

###### Numbers::makeTau(?int $scale = null)

This factory method will return the number tau (τ) as an instance of `ImmutableNumber` to the requested `$scale`. If no `$scale` is given, then the value is returned with a scale of 100. If a scale of 100 or less is requested, then the instance is constructed from the `Numbers::TAU` constant. If a scale of greater than 100 is requested, then a call is made to `Numbers::makePi()` which uses the methods described above, after which the result is multiplied by 2.

!!! warning "Scale Must Be Positive"
    If a scale of less than 1 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown.

###### Numbers::make2Pi(?int $scale = null)

This factory method is an alias for `Numbers::makeTau()`.

!!! warning "Scale Must Be Positive"
    If a scale of less than 1 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown.

###### Numbers::makeE(?int $scale = null)

This factory method will return Euler's number (e) as an instance of `ImmutableNumber` to the requested `$scale`. If no `$scale` is given, then the value is returned with a scale of 100. If a scale of 100 or less is requested, then the instance is constructed from the `Numbers::E` constant. If a scale of greater than 100 is requested, then a call is made to `ConstantProvider::makeE()` which uses a fast converging series to calculate digits of e.

!!! warning "Scale Must Be Positive"
    If a scale of less than 1 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown.

###### Numbers::makeGoldenRatio(?int $scale = null)

This factory method will return the golden ratio (φ) as an instance of `ImmutableNumber` to the requested `$scale`. If no `$scale` is given, then the value is returned with a scale of 100. If a scale of 100 or less is requested, then the instance is constructed from the `Numbers::GOLDEN_RATION` constant.

!!! warning "Scale Must Be 1-100"
    If a scale of less than 1 or greater than 100 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown.

###### Numbers::makeNaturalLog10(?int $scale = null)

This factory method will return the natural log of 10 as an instance of `ImmutableNumber` to the requested `$scale`. If no `$scale` is given, then the value is returned with a scale of 100. If a scale of 100 or less is requested, then the instance is constructed from the `Numbers::LN_10` constant. If a scale of greater than 100 is requested, then an exception is thrown.

!!! warning "Scale Must Be 1-100"
    If a scale of less than 1 or greater than 100 is requested, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown.

###### Numbers::makeOne(?int $scale = null)

This factory method will return the natural log of 10 as an instance of `ImmutableNumber` with a value of `1` and a scale setting of `$scale`. If `$scale` is null, then the instance returned will have a scale of 100.

###### Numbers::makeZero(?int $scale = null)

This factory method will return the natural log of 10 as an instance of `ImmutableNumber` with a value of `0` and a scale setting of `$scale`. If `$scale` is null, then the instance returned will have a scale of 100.

### Static Methods

The `Numbers` factory class also has two static methods that work as a global variable for the Fermat library.

###### Numbers::getDefaultCalcMode()

This static method returns the current value of the protected parameter `Numbers::$defaultCalcMode`. By default, this value is set to `Selectable::CALC_MODE_PRECISION`, resulting in the arbitrary scale implementations being used for all math functions.

!!! caution "For Internal Use"
    This function is meant to be called within the constructors of values that implement the `NumberInterface` and which use the provided arithmetic traits. It is likely to have limited utility outside of these situations.

###### Numbers::setDefaultCalcMode(int $mode)

This static method sets the protected parameter `Numbers::$defaultCalcMode` to the provided `$mode`. The Fermat library assumes that only values which are constants on the `Selectable` class are used as inputs for this function.

Using other values for `$mode` may be possible in the event you are extending the Fermat classes with your own implementations, however an unknown `$mode` will cause the classes provided in this library to fall back to `Selectable::CALC_MODE_PRECISION`.

This behavior could be changed by overriding the methods defined in the `ArithmeticSelectionTrait`.

!!! see-also "See Also"
    For more information on the calculation modes available in Fermat, see the page on [Calculation Modes](calculation-modes.md). 
    
    For more information on extending these values, please see the documentation in the "Extending" section.