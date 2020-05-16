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

!!! potential-bugs "You Might Not Expect"
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

!!! tip "For Example"
    A zero matrix two rows and three columns would look like:
    
    `[0 0 0]`  
    `[0 0 0]`

###### Matrices::onesMatrix(string $type, int $rows, int $columns)

This factory method returns an instance of the specified matrix type with the given dimensions where all values in the matrix are the number one.

!!! tip "For Example"
    A ones matrix two rows and three columns would look like:
    
    `[1 1 1]`  
    `[1 1 1]`

###### Matrices::identityMatrix(string $type, int $size)

This factory method returns a square matrix where the dimensions match the integer given in `$size`. This matrix is an identity matrix, which is often used in matrix math, where the diagonal consists of ones, and all other values are zero.

!!! tip "For Example"
    An identity matrix of size three would look like:
    
    `[1 0 0]`  
    `[0 1 0]`  
    `[0 0 1]`

###### Matrices::cofactorMatrix(string $type, int $size)

This factory method returns a square matrix where the dimensions match the integer given in `$size`. The matrix is filled with alternating values of `1` and `-1` in a checkerboard pattern, starting with positive 1 in position [0, 0].

When multiplied by another matrix, this will swap the sign of every other value in the matrix.

!!! tip "For Example"
    A cofactor matrix of size three would look like:
    
    `[+ - +]`  
    `[- + -]`  
    `[+ - +]`

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

The value of the constant pi (π) pre-computed to 100 decimal places.

###### TAU

The value of the constant tau (τ) pre-computed to 100 decimal places. This is equivalent to 2π.

###### E

The value of Euler's constant (e) pre-computed to 100 decimal places.

###### GOLDEN_RATIO

The value of the Golden Ratio (φ) pre-computed to 100 decimal places.

###### LN_10

The value of the natural logarithm of 10 pre-computed to 100 decimal places.

###### I_POW_I

The value of i^i pre-computed to 100 decimal places.